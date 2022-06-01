<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget;

use Hyperf\ViewEngine\Contract\Renderable;
use Illuminate\Support\Collection;

class Tools implements Renderable
{
    /**
     * Collection of tools.
     *
     * @var array
     */
    protected $tools = ['back' => false, 'collapse' => false, 'remove' => false];

    /**
     * Tools should be appends to default tools.
     *
     * @var Collection
     */
    protected $appends;

    /**
     * Create a new Tools instance.
     */
    public function __construct()
    {
        $this->appends = new Collection();
    }

    /**
     * Append a tools.
     *
     * @param string|\Closure
     *
     * @return $this
     */
    public function append($tool)
    {
        $this->appends->push($tool);

        return $this;
    }

    /**
     * Show `back` tool.
     *
     * @return $this
     */
    public function showBack(bool $show = true)
    {
        $this->tools['back'] = $show;

        return $this;
    }

    /**
     * Show `collapse` tool.
     *
     * @return $this
     */
    public function showCollapse(bool $show = true)
    {
        $this->tools['collapse'] = $show;

        return $this;
    }

    /**
     * Show `remove` tool.
     *
     * @return $this
     */
    public function showRemove(bool $show = true)
    {
        $this->tools['remove'] = $show;

        return $this;
    }

    /**
     * Render back button.
     *
     * @return string
     */
    protected function renderBack()
    {
        return '<div class="btn-group" style="margin-right: 5px">
                    <a href="javascript:history.back()" class="btn btn-sm btn-default btn-outline">
                        <i class="fa fa-arrow-left"></i>&nbsp;&nbsp;' . trans('admin.back') . '
                    </a>
                </div>';
    }

    /**
     * Render collapse button.
     *
     * @return string
     */
    protected function renderCollapse()
    {
        return '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
    }

    /**
     * Render remove button.
     *
     * @return string
     */
    protected function renderRemove()
    {
        return '<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>';
    }

    /**
     * Render tools.
     *
     * @return string
     */
    public function render()
    {
        $output = $this->appends->implode(' ');

        foreach ($this->tools as $tool => $enable) {
            if ($enable) {
                $renderMethod = 'render' . ucfirst($tool);

                $output .= $this->$renderMethod();
            }
        }

        return $output;
    }
}
