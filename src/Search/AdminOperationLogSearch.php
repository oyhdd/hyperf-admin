<?php

declare (strict_types=1);

namespace Oyhdd\Admin\Search;

use Oyhdd\Admin\Model\AdminOperationLog;

class AdminOperationLogSearch extends AdminOperationLog
{
    public function search(array $params = []) {
        $dataProvider = AdminOperationLog::orderByDesc('id')
            ->with('user')
            ->get();

        return $dataProvider;
    }
}