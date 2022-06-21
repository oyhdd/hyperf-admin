<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form\Field;

class Number extends Text
{

    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = 'widget.form.number';

    /**
     * Set min value of number field.
     *
     * @param int $value
     *
     * @return $this
     */
    public function min($value)
    {
        $this->attribute('min', $value);

        return $this;
    }

    /**
     * Set max value of number field.
     *
     * @param int $value
     *
     * @return $this
     */
    public function max($value)
    {
        $this->attribute('max', $value);

        return $this;
    }

    /**
     * Render this filed.
     */
    public function render()
    {
        $this->prepend('')->defaultAttribute('style', 'width: 100px; text-align: center;');

        return parent::render();
    }
}
