<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget;

use App\Model\Model;
use Closure;

trait ModelTrait
{
    /**
     * @param Model $model
     *
     * @return $this
     */
    public function setModel($model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Format the label value.
     *
     * @param array $arguments
     *
     * @return string
     */
    protected function formatLabel($label = '')
    {
        if (!empty($label)) {
            return $label;
        }

        $label = trans($this->model->getTable() . '.fields.' . $this->column);
        return str_replace([$this->model->getTable() . '.fields.', '_'], ['', ' '], $label);
    }


    /**
     * Get or set value of current field.
     *
     * @param mixed $value
     *
     * @return $this|mixed
     */
    public function value($value = null)
    {
        if (is_null($value)) {
            return $this->value ?? $this->default();
        }

        $this->value = $value;

        return $this;
    }

    /**
     * Get or set default value of current field.
     *
     * @param mixed $default
     *
     * @return $this|mixed
     */
    public function default($default = null)
    {
        if (is_null($default)) {
            if (!is_null($this->default)) {
                return $this->default;
            }
            if (in_array($this->column, $this->model->getHidden())) {
                return '';
            }
            return $this->model->{$this->column} ?? '';
        }

        $this->default = $default;

        return $this;
    }


    /**
     * Field display callback.
     *
     * @param \Closure $callback
     *
     * @return $this
     */
    public function as(Closure $callback)
    {
        $value = $this->value = $callback->call($this->model);
        if (is_array($value)) {
            $value = collect($value);
        }
        $this->value = $value;

        return $this;
    }
}
