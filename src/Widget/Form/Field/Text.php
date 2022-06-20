<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form\Field;

use Oyhdd\Admin\Widget\Form\Field;

class Text extends Field
{
    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = 'widget.form.text';

    protected $prepend;

    protected $append;

    public function prepend($string)
    {
        if (is_null($this->prepend)) {
            $this->prepend = $string;
        }

        return $this;
    }

    public function append($string)
    {
        if (is_null($this->append)) {
            $this->append = $string;
        }

        return $this;
    }

    /**
     * Render this filed.
     */
    public function render()
    {
        $this->prepend('<i class="fa fa-pencil fa-fw"></i>')
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('name', $this->elementName())
            ->defaultAttribute('value', $this->value())
            ->defaultAttribute('placeholder', $this->placeholder());

        $this->addVariables([
            'prepend' => $this->prepend,
            'append'  => $this->append,
        ]);

        return parent::render();
    }
}