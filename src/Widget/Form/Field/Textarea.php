<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form\Field;

use Oyhdd\Admin\Widget\Form\Field;

class Textarea extends Field
{
    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = 'widget.form.textarea';

    /**
     * Default rows of textarea.
     *
     * @var int
     */
    protected $rows = 5;

    /**
     * Set rows of textarea.
     *
     * @param int $rows
     *
     * @return $this
     */
    public function rows($rows = 5)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->defaultAttribute('name', $this->elementName())
            ->defaultAttribute('rows', $this->rows)
            ->defaultAttribute('placeholder', $this->placeholder())
            ->defaultAttribute('style', 'resize:vertical;');

        return parent::render();

    }
}
