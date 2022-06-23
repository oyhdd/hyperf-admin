<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form\Field;

use Closure;
use Oyhdd\Admin\Widget\Form\Field;

class Html extends Field
{
    /**
     * Htmlable.
     *
     * @var string|\Closure
     */
    protected $html = '';

    /**
     * @var string
     */
    protected $label = '';

    /**
     * Create a new Html instance.
     *
     * @param mixed $html
     * @param array $arguments
     */
    public function __construct($column, $arguments = [], $form = null)
    {
        $this->setForm($form);
        $this->html = $column;
        $this->label = $this->formatLabel($arguments);
    }

    /**
     * Render html field.
     *
     * @return string
     */
    public function render()
    {
        if ($this->html instanceof Closure) {
            $this->html = $this->html->call($this->model, $this->form);
        }

        return <<<EOT
<div class="form-group">
    <label class="control-label col-sm-{$this->width['label']}">{$this->label}</label>
    <div class="col-sm-{$this->width['field']}">
        {$this->html}
    </div>
</div>
EOT;
    }
}
