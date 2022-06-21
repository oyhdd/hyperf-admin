<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form\Field;

class Password extends Text
{
    /**
     * Render this filed.
     */
    public function render()
    {
        $this->prepend('<i class="fa fa-eye-slash fa-fw"></i>')
            ->defaultAttribute('type', 'password');

        return parent::render();
    }
}
