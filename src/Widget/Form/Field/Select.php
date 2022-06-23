<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form\Field;

use Closure;
use Hyperf\Utils\Contracts\Arrayable;
use Oyhdd\Admin\Widget\Form\Field;

class Select extends Field
{
    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = 'widget.form.select';

    /**
     * Options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Set options.
     *
     * @param array|Closure
     *
     * @return $this|mixed
     */
    public function options($options = [])
    {
        if ($options instanceof Arrayable) {
            $this->options = $options->toArray();
        } elseif ($options instanceof Closure) {
            $this->options = $options->call($this, $this->model);
        } else {
            $this->options = (array) $options;
        }

        return $this;
    }

    /**
     * Render this filed.
     */
    public function render()
    {
        $this->defaultAttribute('data-placeholder', $this->placeholder())
            ->defaultAttribute('style', 'width: 100%;');

        $this->addVariables([
            'options' => $this->options,
        ]);

        return parent::render();
    }
}
