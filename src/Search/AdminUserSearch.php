<?php

declare (strict_types=1);

namespace Oyhdd\Admin\Search;

use Oyhdd\Admin\Model\AdminUser;

class AdminUserSearch extends AdminUser
{
    public function search(array $params = []) {
        return AdminUser::with('permissions')->with('roles')->get();
    }
}