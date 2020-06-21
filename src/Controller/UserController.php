<?php declare(strict_types=1);

namespace Oyhdd\Admin\Controller;

use Hyperf\HttpServer\Annotation\{Controller, RequestMapping, Middleware};
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\HttpMessage\Cookie\Cookie;
use Illuminate\Hashing\BcryptHasher;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;
use Oyhdd\Admin\Middleware\AuthMiddleware;
use Oyhdd\Admin\Search\AdminUserSearch;

/**
 * @Controller(prefix="admin/user")
 */
class UserController extends AdminController
{
    /**
     * @Inject
     * @var AdminUserSearch
     */
    protected $adminUserSearch;

    /**
     * @Inject
     * @var BcryptHasher
     */
    protected $hash;

    /**
     * @Inject()
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;

    /**
     * @RequestMapping(path="login")
     *
     * 登录
     * @param  string   $username   用户名
     * @param  string   $password   密码
     * @param  int      $remember   记住我: 1是 0否
     * @return view
     */
    public function login()
    {
        if ($this->request->isMethod("POST")) {
            $username = htmlspecialchars($this->request->input('username', ''));
            $password = htmlspecialchars($this->request->input('password', ''));

            $user = AdminUserSearch::where(['username' => $username, 'status' => AdminUserSearch::STATUS_ENABLE])->first();
            if (!empty($user) && $this->hash->check($password, $user->password)) {
                $token = 'Bearer '.(string) $this->jwt->getToken(['id' => $user->id]);
                $cookie = new Cookie('Authorization', $token);
                return $this->response->withCookie($cookie)->redirect('/admin');
            }
            $this->admin_toastr("用户名或者密码错误", 'error', 0);
        } elseif ($tokenObj = $this->getTokenObj()) {
            $userId = $tokenObj->getClaim('id');
            $user = AdminUserSearch::where('id', $userId)->where('status', AdminUserSearch::STATUS_ENABLE)->first();
            if (!empty($user)) {
                $request = $this->request->withAttribute('user', $user);
                Context::set(ServerRequestInterface::class, $request);
                return $this->response->redirect('/admin');
            }
            $this->admin_toastr("登录态失效，请重新登录", 'error');
            return $this->response->redirect('/admin/user/logout');
        }

        return $this->render('admin.user.login', [], true);
    }

    /**
     * @RequestMapping(path="logout")
     *
     * 退出
     * @return view
     */
    public function logout()
    {
        $token = $this->request->cookie('Authorization', '');

        try {
            $this->jwt->logout($token);
        } catch (\Throwable $t) {
        }

        return $this->response->redirect('/admin/user/login');
    }

    /**
     * @RequestMapping(path="lock")
     *
     * 锁屏
     * @param  string   $password   密码
     * @return view
     */
    public function lock()
    {
        $tokenObj = $this->getTokenObj();
        if (empty($tokenObj) || empty($userId = $tokenObj->getClaim('id', ''))) {
            return $this->response->redirect('/admin/user/login');
        }

        $user = AdminUserSearch::where('id', $userId)->where('status', AdminUserSearch::STATUS_ENABLE)->first();
        if (empty($user)) {
            return $this->response->redirect('/admin/user/login');
        }

        $token = 'Bearer '.(string) $this->jwt->getToken(['id' => $userId, 'lock' => true]);
        $cookie = new Cookie('Authorization', $token);

        return $this->render('admin.user.lock', ['_user' => $user->toArray()], true)->withCookie($cookie);
    }

    /**
     * @RequestMapping(path="unlock")
     *
     * 解锁
     * @param  string   $password   密码
     * @return view
     */
    public function unlock()
    {
        $password = htmlspecialchars($this->request->input('password', ''));
        $tokenObj = $this->getTokenObj();
        if (empty($tokenObj) || empty($userId = $tokenObj->getClaim('id', ''))) {
            return $this->response->redirect('/admin/user/login');
        }

        $user = AdminUserSearch::find($userId);
        if (empty($user)) {
            return $this->response->redirect('/admin/user/login');
        }

        if ($this->hash->check($password, $user->password)) {
            $token = 'Bearer '.(string) $this->jwt->getToken(['id' => $user->id]);
            $cookie = new Cookie('Authorization', $token);
            return $this->response->redirect('/admin')->withCookie($cookie);
        }

        $this->admin_toastr("密码错误", 'error', 0);
        return $this->response->redirect('/admin/user/lock');
    }

    /**
     * @RequestMapping(path="setting/saveCustomizeStyle")
     * @Middleware(AuthMiddleware::class)
     *
     * 自定义网站样式
     * @param  string   $selector   页面元素
     * @param  array    $styles     样式[class_name => enable]
     * @return array
     */
    public function saveCustomizeStyle()
    {
        $selector = htmlspecialchars($this->request->input('selector', ''));
        $styles = $this->request->input('styles', '');
        if (empty($selector) || empty($styles)) {
            return [];
        }
        $user = $this->getUser();
        $customize_style = !empty($user->customize_style) ? json_decode($user->customize_style, true) : [];
        foreach ($styles as $class_name => $enable) {
            $customize_style[$selector][$class_name] = intval($enable);
        }

        $user->customize_style = json_encode($customize_style);
        $user->save();

        return [];
    }

    /**
     * @RequestMapping(path="setting/clearCustomizeStyle")
     * @Middleware(AuthMiddleware::class)
     *
     * 清除自定义网站样式
     * @param  string   $selector   页面元素
     * @return array
     */
    public function clearCustomizeStyle()
    {
        $selector = htmlspecialchars($this->request->input('selector', ''));
        if (empty($selector)) {
            return [];
        }

        $user = $this->getUser();
        $customize_style = !empty($user->customize_style) ? json_decode($user->customize_style, true) : [];
        if ($selector === 'all') {
            $customize_style = [];
        } elseif (isset($customize_style[$selector])) {
            $customize_style[$selector] = [];
        }

        $user->customize_style = json_encode($customize_style);
        if ($user->save()) {
            $this->admin_toastr("重置样式成功", 'success');
            return [];
        }

        return [];
    }

    /**
     * @RequestMapping(path="error")
     * @Middleware(AuthMiddleware::class)
     *
     * 错误跳转页
     * @param  string   $error
     * @return array
     */
    public function error()
    {
        $error = htmlspecialchars($this->request->input('error', ''));
        $code = intval($this->request->input('code', 500));
        if ($code == 0) {
            $code = 500;
        }

        return $this->render('common.error', compact('code', 'error'));
    }

    /**
     *
     * @RequestMapping(path="", methods="get")
     * @Middleware(AuthMiddleware::class)
     *
     * Lists all models.
     * @return mixed
     */
    public function index()
    {
        $params = $this->request->all();

        $dataProvider = $this->adminUserSearch->search($params);
        $searchModel = $this->adminUserSearch->searchForm($params);

        return $this->render('admin.user.index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'params'       => $params,
        ]);
    }

    /**
     * @RequestMapping(path="create")
     * @Middleware(AuthMiddleware::class)
     *
     * Creates a new model.
     * @return mixed
     */
    public function create()
    {
        $model = new AdminUserSearch();

        if ($this->request->isMethod('post')) {
            $params = $this->request->all();
            $validator = $this->validationFactory->make(
                $params,
                [
                    'password' => 'required|min:6|confirmed',
                    'username' => 'required',
                    'name' => 'required',
                ]
            );
            $params['password'] = $this->hash->make($params['password']);
            if ($validator->fails()){
                $this->admin_toastr($validator->errors()->first(), 'error', 5);
            } elseif ($model->fill($params) && $model->save()) {
                $model->roles()->sync($this->request->input('roles'));
                $model->permissions()->sync($this->request->input('permissions'));
                $this->admin_toastr("Create Success", 'success', 2);
                return $this->redirect("admin/user");
            }
        }

        return $this->render('admin.user.create', [
            'model' => $model,
        ]);
    }

    /**
     * @RequestMapping(path="{id}/edit")
     * @Middleware(AuthMiddleware::class)
     *
     * Updates an existing model.
     * @param  int $id
     * @return mixed
     */
    public function edit($id)
    {
        $model = AdminUserSearch::findOrFail($id);

        if ($this->request->isMethod('post')) {
            $params = $this->request->all();
            $validator = $this->validationFactory->make(
                $params,
                [
                    'password' => 'required|min:6|confirmed',
                    'username' => 'required',
                    'name' => 'required',
                ]
            );
            if ($validator->fails()){
                $this->admin_toastr($validator->errors()->first(), 'error', 5);
            } else {
                if ($params['password'] != $model->password) {
                    $params['password'] = $this->hash->make($params['password']);
                }
                if ($model->fill($params) && $model->save()) {
                    $model->roles()->sync($this->request->input('roles'));
                    $model->permissions()->sync($this->request->input('permissions'));
                    $this->admin_toastr("Edit Success", 'success', 2);
                    return $this->redirect("admin/user/{$id}");
                }
            }
        }

        return $this->render('admin.user.edit', [
            'model' => $model,
        ]);
    }

    /**
     * @RequestMapping(path="setting")
     * @Middleware(AuthMiddleware::class)
     *
     * Updates an existing model.
     * @param  int $id
     * @return mixed
     */
    public function setting()
    {
        $model = $this->getUser();

        if ($this->request->isMethod('post')) {
            $params = $this->request->all();
            $validator = $this->validationFactory->make(
                $params,
                [
                    'password' => 'required|min:6|confirmed',
                    'username' => 'required',
                    'name' => 'required',
                ]
            );
            if ($validator->fails()){
                $this->admin_toastr($validator->errors()->first(), 'error', 5);
            } else {
                if ($params['password'] != $model->password) {
                    $params['password'] = $this->hash->make($params['password']);
                }
                if ($model->fill($params) && $model->save()) {
                    $this->admin_toastr("Edit Success", 'success', 2);
                }
            }
        }

        return $this->render('admin.user.edit', [
            'model' => $model,
        ]);
    }

    /**
     * @RequestMapping(path="{id}", methods="get")
     * @Middleware(AuthMiddleware::class)
     *
     * Displays a single model.
     * @param  int $id
     * @return mixed
     */
    public function show(int $id)
    {
        $model = AdminUserSearch::findOrFail($id);

        return $this->render('admin.user.show', [
            'model' => $model,
        ]);
    }

    /**
     * @RequestMapping(path="{id}/delete", methods="post")
     * @Middleware(AuthMiddleware::class)
     *
     * Deletes an existing model.
     * @param  int $id
     * @return mixed
     */
    public function delete($id)
    {
        if ($id != 1 && AdminUserSearch::where('id', $id)->delete()) {
            $this->admin_toastr("Delete Success", 'success', 2);
        } else {
            $this->admin_toastr("Delete Fail", 'error', 5);
        }

        return $this->response();
    }

}