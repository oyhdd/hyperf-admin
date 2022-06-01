<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Model\DataProvider;

use App\Model\Model;
use Hyperf\Database\Model\Builder;
use Hyperf\Paginator\UrlWindow;

/**
 * $dataProvider = new ModelDataProvider(AdminUser::query());
 *
 * // get the posts in the current page
 * $posts = $dataProvider->getModels();
 */
class ModelDataProvider implements DataProviderInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $filterColumns;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var string field
     */
    protected $sort;

    /**
     * @var string  asc,desc
     */
    protected $sort_type;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $perPageList = [10, 20, 30, 50, 100];

    public function __construct(Model $model, array $params = [])
    {
        $this->model = $model;
        unset($params['_pjax']);

        $this->params        = $params;
        $this->perPage       = intval($params['_perPage'] ?? 10);
        $this->page          = intval($params['_page'] ?? 1);
        $this->sort          = $params['_sort'] ?? '';
        $this->sort_type     = $params['_sort_type'] ?? '';
        $this->filterColumns = $params['filterColumns'] ?? [];
        $this->perPageList   = $params['perPageList'] ?? $this->perPageList;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $startTime = microtime(true);

        $query = clone $this->model->query();
        if (!empty($this->sort) && in_array($this->sort_type, ['asc', 'desc'])) {
            $query->orderBy($this->sort, $this->sort_type);
        } else {
            $query->orderBy($this->model->getKeyName(), 'asc');
        }

        if (!empty($this->filterColumns)) {
            foreach ($this->filterColumns as $column) {
                if (!empty($this->params[$column])) {
                    $query->where($column, $this->params[$column]);
                }
            }
        }

        // \Hyperf\Paginator\LengthAwarePaginator::class
        $paginator = $query->paginate($this->perPage, ['*'], '_page', $this->page);
        $list = $paginator->toArray();
        $list['data'] = $paginator->items();
        $list['elements'] = $this->elements($paginator);
        $list['perPageList'] = $this->perPageList;

        $endTime = microtime(true);
        $list['query_time'] = round(($endTime - $startTime) * 1000, 3);

        return $list;
    }

    /**
     * Retrieve all input data from request, include query parameters, parsed body and json body.
     * @return $this
     */
    public function getParams()
    {
        return $this->params;
    }

    public function setFilterColumns(array $filterColumns)
    {
        $this->filterColumns = $filterColumns;

        return $this;
    }

    /**
     * Add a column sortable to column header.
     *
     * @param string $column
     * @param string $cast  asc,desc
     *
     * @return $this
     */
    public function sortable(string $column, string $cast)
    {
        $this->sort      = $column;
        $this->sort_type = $cast;

        return $this;
    }

    protected function elements($paginator)
    {
        $paginator->appends($this->params);
        $paginator->appends('_ha_no_animation', '1');
        $window = UrlWindow::make($paginator);
        return array_filter([
            $window['first'],
            is_array($window['slider']) ? '...' : null,
            $window['slider'],
            is_array($window['last']) ? '...' : null,
            $window['last'],
        ]);
    }

}