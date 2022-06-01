<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Grid;

use Illuminate\Support\Collection;

class Actions
{
    /**
     * Default actions.
     *
     * @var array
     */
    protected $actions = [
        'view'   => true,
        'edit'   => true,
        'delete' => true,
    ];

    /**
     * @var Collection
     */
    protected $appends;

    /**
     * @var Collection
     */
    protected $prepends;

    /**
     * Create a new Actions instance.
     */
    public function __construct()
    {
        $this->appends = new Collection();
        $this->prepends = new Collection();
    }

    /**
     * Disable view action.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableView(bool $disable = true)
    {
        return $this->setAction('view', !$disable);
    }

    /**
     * @return bool
     */
    public function showView()
    {
        return $this->actions['view'];
    }

    /**
     * Disable edit.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableEdit(bool $disable = true)
    {
        return $this->setAction('edit', ! $disable);
    }

    /**
     * @return bool
     */
    public function showEdit()
    {
        return $this->actions['edit'];
    }

    /**
     * Disable delete.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableDelete(bool $disable = true)
    {
        return $this->setAction('delete', !$disable);
    }

    /**
     * @return bool
     */
    public function showDelete()
    {
        return $this->actions['delete'];
    }

    /**
     * @param string $key
     * @param bool $disable
     *
     * @return $this
     */
    protected function setAction(string $key, bool $value)
    {
        $this->actions[$key] = $value;

        return $this;
    }

    /**
     * Append a actions.
     *
     * @param string|\Closure
     *
     * @return $this
     */
    public function append($action)
    {
        $this->appends->push($action);

        return $this;
    }

    /**
     * Prepend a actions.
     *
     * @param string|\Closure
     *
     * @return $this
     */
    public function prepend($action)
    {
        $this->prepends->push($action);

        return $this;
    }

    /**
     * Render append tools.
     *
     * @return string
     */
    public function renderAppend()
    {
        $appends = $this->appends->implode(' ');
        $this->appends = new Collection();

        return $appends;
    }

    /**
     * Render append tools.
     *
     * @return string
     */
    public function renderPrepend()
    {
        $prepends = $this->prepends->implode(' ');
        $this->prepends = new Collection();

        return $prepends;
    }
}
