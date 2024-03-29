<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form;

use Hyperf\ViewEngine\Contract\Renderable;
use function Hyperf\ViewEngine\view;
use App\Model\Model;
use Hyperf\ViewEngine\T;
use Oyhdd\Admin\Widget\ModelTrait;

class Field implements Renderable
{
    use ModelTrait;

    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view;

    /**
     * Column name.
     *
     * @var string
     */
    protected $column;

    /**
     * Element label.
     *
     * @var string
     */
    protected $label;

    /**
     * Placeholder for this field.
     *
     * @var string
     */
    protected $placeholder;

    /**
     * Help block.
     *
     * @var string
     */
    protected $help;

    /**
     * Model of the form.
     *
     * @var Model
     */
    protected $model;

    /**
     * Form element name.
     *
     * @var string
     */
    protected $elementName;

    /**
     * Field default value.
     *
     * @var mixed
     */
    protected $default;

    /**
     * Variables of elements.
     *
     * @var array
     */
    protected $variables = [];

    /**
    * Element attributes.
    *
    * @var array
    */
   protected $attributes = [];

    /**
     * Extends width of form.
     *
     * @var array
     */
   protected $extendsWidth = true;

    /**
     * Input filed required.
     *
     * @var array
     */
   protected $required = false;

    /**
     * Width for label and field.
     *
     * @var array
     */
    protected $width = [
        'label' => 2,
        'field' => 8,
    ];

    /**
     * Field constructor.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [], $model = null)
    {
        $this->setModel($model);

        $this->column = $column;
        $this->label = $this->formatLabel($arguments[0] ?? '');
        $this->id = str_replace('.', '_', 'form_' . $column);
    }

    /**
     * Set default attribute for field.
     *
     * @param string    $attribute
     * @param mixed     $label
     *
     * @return $this
     */
    protected function defaultAttribute($attribute, $value)
    {
        if (!array_key_exists($attribute, $this->attributes)) {
            $this->attribute($attribute, $value);
        }

        return $this;
    }

    /**
     * Render this filed.
     *
     * @return \Hyperf\ViewEngine\Contract\FactoryInterface|\Hyperf\ViewEngine\Contract\ViewInterface
     */
    public function render()
    {
        return view($this->getView(), $this->getVariables())->render();
    }

    /**
     * Get the view variables of this field.
     *
     * @return array
     */
    public function getVariables()
    {
        return array_merge($this->variables, [
            'id'          => $this->id,
            'label'       => $this->label,
            'name'        => $this->elementName(),
            'help'        => $this->help(),
            'value'       => $this->value(),
            'attributes'  => $this->formatAttributes(),
            'placeholder' => $this->placeholder(),
            'width'       => $this->width,
            'column'      => $this->column,
            'required'    => $this->required,
        ]);
    }

    /**
     * Set width for field and label.
     *
     * @param int   $field
     * @param int   $label
     * @param bool  $extendsWidth
     *
     * @return $this
     */
    public function width(int $field = 8, int $label = 2, bool $extendsWidth = false)
    {
        if ($this->extendsWidth) {
            $this->width = [
                'label' => $label,
                'field' => $field,
            ];
            $this->extendsWidth = $extendsWidth;
        }

        return $this;
    }

    /**
     * Get or set placeholder of current field.
     *
     * @param string|null $placeholder
     *
     * @return $this|string
     */
    public function placeholder($placeholder = null)
    {
        if (is_null($placeholder)) {
            return $this->placeholder ?: (trans('admin.input') . ' ' . $this->label);
        }
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get or set element name of current field.
     *
     * @param string|null $name
     *
     * @return $this|string
     */
    public function elementName($name = null)
    {
        if (is_null($name)) {
            return $this->elementName ?: $this->column;
        }
        $this->elementName = $name;

        return $this;
    }

    /**
     * set the input filed required.
     *
     * @param bool $required
     *
     * @return $this
     */
    public function required(bool $required = true)
    {
        $this->required = $required;

        return $this->attribute('required', $required);
    }

    /**
     * Set field as disabled.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disable(bool $disable = true)
    {
        return $this->attribute('disabled', $disable);
    }

    /**
     * Format the field attributes.
     *
     * @return string
     */
    protected function formatAttributes()
    {
        $html = [];

        foreach ($this->attributes as $name => $value) {
            $html[] = sprintf('%s="%s"', $name, T::e($value));
        }

        return implode(' ', $html);
    }

    /**
     * Add html attributes to elements.
     *
     * @param array|string $attribute
     * @param mixed        $value
     *
     * @return $this
     */
    public function attribute($attribute, $value = null)
    {
        if (is_array($attribute)) {
            $this->attributes = array_merge($this->attributes, $attribute);
        } else {
            $this->attributes[$attribute] = (string) $value;
        }

        return $this;
    }

    /**
     * Add variables to field view.
     *
     * @param array $variables
     *
     * @return $this
     */
    protected function addVariables(array $variables = [])
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }

    /**
     * Get or set help block of current field.
     *
     * @param string|null $html
     *
     * @return $this|string
     */
    public function help($html = null)
    {
        if (is_null($html)) {
            return $this->help;
        }

        $this->help = $html;

        return $this;
    }

    /**
     * Set view of this field.
     *
     * @return string
     */
    public function setView(string $view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get view of this field.
     *
     * @return string
     */
    protected function getView()
    {
        return $this->view;
    }
}