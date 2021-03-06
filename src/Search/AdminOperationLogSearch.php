<?php

declare (strict_types=1);

namespace Oyhdd\Admin\Search;

use Hyperf\Contract\LengthAwarePaginatorInterface;
use Oyhdd\Admin\Model\Widget\Form;
use Oyhdd\Admin\Model\{AdminUser, AdminOperationLog};

class AdminOperationLogSearch extends AdminOperationLog
{
    /**
     * 允许自动条件筛选的字段
     * @return array
     */
    protected function filter(): array
    {
        return ['id', 'user.id'];
    }
    /**
     * 列表搜索数据
     * @param  array  $params   参数
     * @return Hyperf\Contract\LengthAwarePaginatorInterface
     */
    public function search(array $params = []): LengthAwarePaginatorInterface
    {
        $query = $this->filterWhere($params);

        $dataProvider = $query->orderByDesc('id')
            ->with('user')
            ->paginate(intval($params['page_size'] ?? config('admin.page_size')));

        return $dataProvider;
    }

    /**
     * 列表搜索框
     * @param  array  $params   参数
     * @param  bool   $expand   是否展开搜索框
     * @return Oyhdd\Admin\Model\Widget\Form
     */
    public function searchForm(array $params = [], bool $expand = false): Form
    {
        $form = parent::searchForm($params, $expand);

        $form->row(function() use ($form, $params) {
            return [
                $form->column(6, $form->select('user.id', '操作人')
                    ->options(AdminUser::all()->pluck('username', 'id')->toArray(), [$params['user']['id'] ?? ''])
                )
            ];
        });

        return $form;
    }
}