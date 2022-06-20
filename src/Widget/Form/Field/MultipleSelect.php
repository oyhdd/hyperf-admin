<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form\Field;

use Closure;
use Hyperf\Utils\Contracts\Arrayable;
use Oyhdd\Admin\Widget\Form\Field;

class MultipleSelect extends Select
{
    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = 'widget.form.multiple-select';

    /**
     * Render this filed.
     */
    public function render()
    {
        $this->defaultAttribute('multiple', 'multiple');

        return parent::render();
    }
}
