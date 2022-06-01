<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Controller;

use Carbon\Carbon;
use Illuminate\Hashing\BcryptHasher;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Cookie\Cookie;

class AuthController extends AdminController
{
    /**
     * @Inject
     * @var BcryptHasher
     */
    protected $hash;

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

            $user = $this->getUserModel()->findByUsername($username);
            if (!empty($user) && $this->hash->check($password, $user->password)) {
                $token = '';
                if ($remember) {
                    $user->remember();
                    $token = sprintf("%s:%s", $user->id, $this->hash->make($user->remember_token));
                }

                $this->session->set('admin_user', $user);
                return $this->response->withCookie($this->getTokenCookie($token))->redirect(admin_url());
            }

            admin_toastr(trans('admin.auth_failed'), 'error');
        } elseif ($token = $this->request->cookie('token')) {
            // Auto Login by remember_token
            list($id, $remember_token) = explode(':', $token);
            $user = $this->getUserModel()->findById(intval($id));
            if (!empty($user->remember_token) && $this->hash->check($user->remember_token, $remember_token)) {
                $user->remember();
                $this->session->set('admin_user', $user);
                $token = sprintf("%s:%s", $user->id, $this->hash->make($user->remember_token));

                return $this->response->withCookie($this->getTokenCookie($token))->redirect(admin_url());
            }

            return $this->logout();
        }

        return $this->renderFull('admin.auth.login');
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
        $token = sprintf("%s:%s", $user->id, $this->hash->make($user->remember_token));

        return $this->response->withCookie($this->getTokenCookie($token))->raw($content);
    }

    /**
     * Unlock
     */
    public function unlock()
    {
        $password = htmlspecialchars($this->request->input('password', ''));

        $user = admin_user();
        if (!empty($user) && $this->hash->check($password, $user->password)) {
            $user->unlock()->remember();
            $this->session->set('admin_user', $user);
            $token = sprintf("%s:%s", $user->id, $this->hash->make($user->remember_token));

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
    private function getUserModel()
    {
        return make(config('admin.database.user_model'));
    }

}