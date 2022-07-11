<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Controller;

use Carbon\Carbon;
use Hyperf\HttpMessage\Cookie\Cookie;
use HyperfExt\Hashing\Hash;

class AuthController extends AdminController
{
    /**
     * Login
     */
    public function login()
    {
        // Handle a login request.
        if ($this->request->isMethod('POST')) {
            $username = htmlspecialchars($this->request->input('username', ''));
            $password = htmlspecialchars($this->request->input('password', ''));
            $remember = boolval($this->request->input('remember', 0));

            $user = $this->getModel()->findByUsername($username);
            if (!empty($user) && Hash::check($password, $user->password)) {
                $token = '';
                if ($remember) {
                    $user->remember();
                    $token = sprintf("%s:%s", $user->id, Hash::make($user->remember_token));
                }

                $this->session->set('admin_user', $user);
                return $this->response->withCookie($this->getTokenCookie($token))->redirect(admin_url());
            }

            admin_toastr(trans('admin.auth_failed'), 'error');
        } elseif ($token = $this->request->cookie('token')) {
            // Auto Login by remember_token
            list($id, $remember_token) = explode(':', $token);
            $user = $this->getModel()->findById(intval($id));
            if (!empty($user->remember_token) && Hash::check($user->remember_token, $remember_token)) {
                $user->remember();
                $this->session->set('admin_user', $user);
                $token = sprintf("%s:%s", $user->id, Hash::make($user->remember_token));

                return $this->response->withCookie($this->getTokenCookie($token))->redirect(admin_url());
            }

            return $this->logout();
        }

        return $this->renderFull('admin.auth.login');
    }

    /**
     * User setting
     */
    public function setting()
    {
        $model = admin_user();

        if ($this->request->isMethod('post')) {
            $params = $this->request->all();
            if (!empty($params['password'])) {
                if ($params['password'] !== $params['password_confirmation']) {
                    return admin_toastr(trans('admin.password_confirm_failed'), 'error');
                }
                $params['password'] = Hash::make($params['password']);
            } else {
                unset($params['password']);
            }
            if ($model->fill($params) && $model->save()) {
                admin_toastr(trans('admin.update_succeeded'));

                return $this->redirect('auth/setting');
            }
            admin_toastr(trans('admin.update_failed'), 'error');
        }

        return $this->render('admin.auth.setting', [
            'model' => $model,
        ]);
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->invalidate();

        return $this->response->withCookie($this->getTokenCookie())->redirect(admin_url('auth/login'));
    }

    /**
     * Lock
     */
    public function lock()
    {
        $user = admin_user();
        $user->lock();

        $content = $this->renderFull('admin.auth.lock');
        $token = sprintf("%s:%s", $user->id, Hash::make($user->remember_token));

        return $this->response->withCookie($this->getTokenCookie($token))->raw($content);
    }

    /**
     * Unlock
     */
    public function unlock()
    {
        $password = htmlspecialchars($this->request->input('password', ''));

        $user = admin_user();
        if (!empty($user) && Hash::check($password, $user->password)) {
            $user->unlock()->remember();
            $this->session->set('admin_user', $user);
            $token = sprintf("%s:%s", $user->id, Hash::make($user->remember_token));

            return $this->response->withCookie($this->getTokenCookie($token))->redirect(admin_url());
        }

        admin_toastr(trans('admin.invalid_password'), 'error');
        return $this->response->redirect(admin_url('auth/lock'));
    }

    /**
     * Generate a cookie with Token
     */
    protected function getTokenCookie(string $token = '')
    {
        return new Cookie(
            'token',
            $token,
            Carbon::now()->addSeconds(config('admin.ttl'))->getTimestamp(),
            // admin_url(),
            // config('session.options.domain') ?? $this->request->getUri()->getHost(),
            // strtolower($this->request->getUri()->getScheme()) === 'https',
            // true,
            // false,
            // Cookie::SAMESITE_STRICT
        );
    }

    /**
     * @return \Oyhdd\Admin\Model\AdminUser
     */
    private function getModel()
    {
        return make(config('admin.database.user_model'));
    }

}