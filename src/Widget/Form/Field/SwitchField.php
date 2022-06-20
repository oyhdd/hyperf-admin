<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form\Field;

use Oyhdd\Admin\Widget\Form\Field;

class SwitchField extends Field
{

    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = 'widget.form.switch-field';

    /**
     * Render this filed.
     */
    public function render()
    {
        $this->defaultAttribute('type', 'checkbox')
            ->defaultAttribute('name', $this->elementName())
            ->defaultAttribute('value', '1');

        return parent::render();
    }
}
