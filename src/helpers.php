<?php

if (!function_exists('admin_url')) {
    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_url($path = ''): string
    {
        $prefix = '/' . trim(config('admin.route.prefix'), '/');
        $prefix = ($prefix == '/') ? '' : $prefix;
        $path = trim($path, '/');

        if (is_null($path) || strlen($path) == 0) {
            return $prefix ?: '/';
        }

        return $prefix . '/' . $path;
    }
}

if (!function_exists('admin_url_without_prefix')) {
    /**
     * Get url without prefix
     *
     * @param string $path
     *
     * @return string
     */
    function admin_url_without_prefix(string $path): string
    {
        return substr_replace($path, '', 0, strlen('/' . config('admin.route.prefix') . '/'));
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get the CSRF token value.
     *
     * @return string
     *
     * @throws \Oyhdd\Admin\Exception\RuntimeException
     */
    function csrf_token(): string
    {
        $session = \Hyperf\Utils\ApplicationContext::getContainer()->get(\Hyperf\Contract\SessionInterface::class);

        if (isset($session)) {
            if (! $session->has('_token')) {
                return $session->regenerateToken();
            }
            return $session->token();
        }

        throw new \Oyhdd\Admin\Exception\RuntimeException('Application session store not set.');
    }
}

if (! function_exists('csrf_field')) {
    /**
     * Generate a CSRF token form field.
     */
    function csrf_field()
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('admin_toastr')) {
    /**
     * Flash a toastr message bag to session.
     *
     * @param string    $message
     * @param string    $type      success, info, error, warning
     */
    function admin_toastr(string $message = '', string $type = 'success'): void
    {
        $session = \Hyperf\Utils\ApplicationContext::getContainer()->get(\Hyperf\Contract\SessionInterface::class);
        $toastr = new \Hyperf\Utils\MessageBag(compact('message', 'type'));

        $session->flash('ha-toastr', $toastr);
    }
}

if (!function_exists('get_toastr')) {
    /**
     * Get a toastr message bag from session.
     */
    function get_toastr()
    {
        $session = \Hyperf\Utils\ApplicationContext::getContainer()->get(\Hyperf\Contract\SessionInterface::class);

        return $session->remove('ha-toastr');
    }
}

if (!function_exists('admin_user')) {
    /**
     * @return null|\Oyhdd\Admin\Model\AdminUser
     */
    function admin_user()
    {
        $session = \Hyperf\Utils\ApplicationContext::getContainer()->get(\Hyperf\Contract\SessionInterface::class);
        return $session->get('admin_user');
    }
}

if (!function_exists('is_uri')) {
    /**
     * Determine if the current request URI matches a pattern.
     *
     * @param mixed ...$patterns
     * @param string  $url
     */
    function is_uri($pattern, string $url): bool
    {
        if (\Hyperf\Utils\Str::is($pattern, rawurldecode($url))) {
            return true;
        }

        return false;
    }
}

if (!function_exists('generate_num')) {
    /**
     * Get the unique string
     */
    function generate_num($prefix = '')
    {
        $chars = md5(uniqid(strval(mt_rand()), true));
        $uuid  = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);

        return $prefix . $uuid;
    }
}


if (!function_exists('request')) {
    /**
     * @var Hyperf\HttpServer\Contract\RequestInterface
     */
    function request()
    {
        return \Hyperf\Utils\ApplicationContext::getContainer()->get(\Hyperf\HttpServer\Contract\RequestInterface::class);
    }
}