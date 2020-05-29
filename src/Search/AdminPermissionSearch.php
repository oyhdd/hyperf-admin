<?php

declare (strict_types=1);

namespace Oyhdd\Admin\Search;

use Oyhdd\Admin\Model\AdminPermission;

class AdminPermissionSearch extends AdminPermission
{
    public function search(array $params = []) {
        return AdminPermission::get();
    }
}