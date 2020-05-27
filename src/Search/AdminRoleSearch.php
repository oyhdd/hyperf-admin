<?php

declare (strict_types=1);

namespace Oyhdd\Admin\Search;

use Oyhdd\Admin\Model\AdminRole;

class AdminRoleSearch extends AdminRole
{
    public function search(array $params = []) {
        return AdminRole::with('permissions')->get();
    }
}