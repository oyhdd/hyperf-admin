<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Grid;

use App\Model\Model;
use Closure;
use Oyhdd\Admin\Model\DataProvider\DataProviderInterface;
use Oyhdd\Admin\Widget\Box;
use Oyhdd\Admin\Widget\Tools;

class Grid extends Box
{
    /**
     * Model of the form.
     *
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * Current field name.
     *
     * @var string
     */
    protected $column;

    /**
     * @var Tools
     */
    protected $tools;

    /**
     * @var Actions
     */
    protected $actions;

    /**
     * Table filter.
     *
     * @var Filter
     */
    protected $filter;

    /**
     * DataProvider.
     *
     * @var DataProviderInterface
     */
    protected $dataProvider;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var bool
     */
    protected $showFilter = true;

    /**
     * @var bool
     */
    protected $showActions = true;

    /**
     * @var Closure
     */
    protected $actionsCallback;

    /**
     * Model primary key
     * 
     * @var string
     */
    protected $keyName;

    public function __construct(Model $model, DataProviderInterface $dataProvider)
    {
        $this->params = $dataProvider->getParams();
        foreach ($this->params as $key => $value) {
            if (isset($value)) {
                $model->{$key} = $value;
            }
        }
        $this->model        = $model;
        $this->filter       = new Filter($model);
        $this->tools        = new Tools();
        $this->actions      = new Actions();
        $this->dataProvider = $dataProvider;

        $this->setKeyName($model->getKeyName());
    }

    /**
     * Set primary key name.
     *
     * @param string|array $name
     *
     * @return $this
     */
    public function setKeyName($name)
    {
        $this->keyName = $name;

        return $this;
    }

    /**
     * Get or set primary key name.
     *
     * @return string|array
     */
    public function getKeyName()
    {
        return $this->keyName ?: 'id';
    }

    /**
     * Set filter for table.
     *
     * @param Closure $callback
     *
     * @return $this;
     */
    public function filter(Closure $callback)
    {
        $callback->call($this, $this->filter);

        $this->dataProvider->setFilterColumns($this->filter->getFields()->keys()->toArray());

        return $this;
    }

    /**
     * Set actions for table.
     *
     * @param Closure $callback
     *
     * @return $this;
     */
    public function actions(Closure $callback)
    {
        $this->actionsCallback = $callback;

        // $this->actionsCallback->call($this->model, $this->actions);
        return $this;
    }

    public function getData()
    {
        return $this->dataProvider->toArray();
    }

    public function getDataProvider()
    {
        return $this->dataProvider;
    }

    /**
     * Get filter of table.
     *
     * @return Model
     */
    public function getFilter()
    {
        $this->filter->hidden('_ha_no_animation', 1);
        $searchText = trans('admin.search');
        $resetText = trans('admin.reset');
        $labelWidth = $this->filter->getWidth('label');
        $fieldWidth = $this->filter->getWidth('field');
        $footer = <<<FOOTER
            <div class="form-group">
                <div class="col-sm-{$labelWidth}"></div>
                <div class="col-sm-{$fieldWidth}">
                    <div class="form-group">
                        <div class="btn-group pull-left">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="icon fa fa-search"></i>&nbsp;&nbsp;{$searchText}
                            </button>
                        </div>
                        <div class="btn-group pull-left" style="margin-left:5px;">
                            <a class="btn btn-sm btn-default" href="?_pjax=#pjax-container">
                                <i class="icon fa fa-undo"></i>&nbsp;&nbsp;{$resetText}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
FOOTER;

        $this->filter->method('get');
        $this->filter->setFooter($footer);
        return $this->filter;
    }

    /**
     * Set Tools for grid.
     *
     * @param Closure $callback
     *
     * @return $this;
     */
    public function tools(Closure $callback)
    {
        $callback->call($this, $this->tools);

        return $this;
    }

    /**
     * Get tools of grid.
     *
     * @return $this;
     */
    public function renderTools()
    {
        return $this->tools->render();
    }

    /**
     * @return bool
     */
    public function showFilter()
    {
        return $this->showFilter;
    }

    /**
     * @param bool $disable
     */
    public function disableFilter(bool $disable = true)
    {
        $this->showFilter = ! $disable;

        return $this;
    }

    /**
     * @return bool
     */
    public function showActions()
    {
        return $this->showActions;
    }

    /**
     * @param bool $disable
     */
    public function disableActions(bool $disable = true)
    {
        $this->showActions = ! $disable;

        return $this;
    }

    /**
     * Get actions of grid.
     *
     * @return Actions;
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Get actions callback of grid.
     *
     * @return Closure;
     */
    public function getActionsCallback()
    {
        return $this->actionsCallback;
    }

    /**
     * Add column to grid.
     *
     * @param string $column
     * @param string $label
     *
     * @return $this
     */
    public function column(string $column, string $label = '')
    {
        $this->column = $column;
        $title = $label ?: trans($this->model->getTable() . '.fields.' . $column);

        $this->columns[$column] = compact('title');

        return $this;
    }

    /**
     * Set width for column.
     *
     * @param int $width
     *
     * @return $this
     */
    public function width(int $width)
    {
        if (isset($this->columns[$this->column])) {
            $this->columns[$this->column]['width'] = $width;
        }

        return $this;
    }

    /**
     * Add a display callback.
     *
     * @param \Closure $callback
     *
     * @return $this
     */
    public function display(Closure $callback)
    {
        if (isset($this->columns[$this->column])) {
            $this->columns[$this->column]['callback'] = $callback;
        }

        return $this;
    }

    public function label(string $style = 'primary')
    {
        if (isset($this->columns[$this->column])) {
            $this->columns[$this->column]['label'] = $style;
        }

        return $this;
    }

    public function link(Closure $callback)
    {
        if (isset($this->columns[$this->column])) {
            $this->columns[$this->column]['link'] = $callback;
        }

        return $this;
    }

    /**
     * Add a column sortable to column header.
     *
     * @param string $cast  asc,desc
     *
     * @return $this
     */
    public function sortable(string $cast = '')
    {
        if (isset($this->columns[$this->column])) {
            $this->columns[$this->column]['sort'] = $cast;
            if (empty($this->params['_sort'])) {
                $this->dataProvider->sortable($this->column, $cast);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

}
