<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form\Field;

use Oyhdd\Admin\Widget\Form\Field;

class Display extends Field
{
    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = 'widget.form.display';

    /**
     * Render this filed.
     */
    public function render()
    {
        $this->defaultAttribute('value', $this->value())
            ->defaultAttribute('disabled', '1');

        return parent::render();
    }
}
