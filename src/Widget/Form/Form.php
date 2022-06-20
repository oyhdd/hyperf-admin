<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form;

use Closure;
use App\Model\Model;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Str;
use Oyhdd\Admin\Widget\Box;
use Oyhdd\Admin\Widget\Form\Field;
use Oyhdd\Admin\Widget\Tools;

/**
 * Class Form.
 *
 * @method Field\Text           text($column, $label = '')
 * @method Field\Textarea       textarea($column, $label = '')
 * @method Field\Hidden         hidden($column, $label = '')
 * @method Field\Html           html($html, $label = '')
 * @method Field\Number         number($column, $label = '')
 * @method Field\SwitchField    switch($column, $label = '')
 * @method Field\Display        display($column, $label = '')
 * @method Field\Select         select($column, $label = '')
 * @method Field\MultipleSelect multipleSelect($column, $label = '')
 */
class Form extends Box
{
    /**
     * Model of the form.
     *
     * @var Model
     */
    protected $model;

    /**
     * @var Tools
     */
    protected $tools;

    /**
     * Form field.
     *
     * @var Collection
     */
    protected $fields;

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
     * @var bool
     */
    protected $ajax = true;

    /**
     * @var bool
     */
    protected $isCreating;

    /**
     * Form action.
     *
     * @var string
     */
    protected $action;

    /**
     * Form method.
     *
     * @var string
     */
    protected $method = 'post';

    /**
     * Current field name.
     *
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $enctype;

    /**
     * Available fields.
     *
     * @var array
     */
    protected static $availableFields = [
        'text'           => Field\Text::class,
        'hidden'         => Field\Hidden::class,
        'html'           => Field\Html::class,
        'number'         => Field\Number::class,
        'switch'         => Field\SwitchField::class,
        'display'        => Field\Display::class,
        'textarea'       => Field\Textarea::class,
        'select'         => Field\Select::class,
        'multipleSelect' => Field\MultipleSelect::class,
    ];

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->tools = new Tools();
        $this->fields = new Collection();
        static::$availableFields = array_merge(static::$availableFields, config('admin.availableFields', []));


    }

    public function model()
    {
        return $this->model;
    }

    /**
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(Field $field)
    {
        $this->getFields()->put($field->elementName(), $field);

        return $this;
    }

    /**
     * Find field class.
     *
     * @param string $method
     *
     * @return bool|mixed
     */
    public static function findFieldClass($method)
    {
        $class = Arr::get(static::$availableFields, $method, '');

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * Set field and label width in current form.
     *
     * @param int $fieldWidth
     * @param int $labelWidth
     *
     * @return $this
     */
    public function width($fieldWidth = 8, $labelWidth = 2)
    {
        $this->getFields()->each(function ($field) use ($fieldWidth, $labelWidth) {
            /* @var Field $field  */
            $field->width($fieldWidth, $labelWidth, true);
        });

        $this->width = [
            'label' => $labelWidth,
            'field' => $fieldWidth,
        ];

        return $this;
    }

    /**
     * Get width of form.
     *
     * @return array
     */
    public function getWidth(string $key)
    {
        return $this->width[$key];
    }

    /**
     * Generate a Field object and add to form builder if Field exists.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Field
     */
    public function __call($method, $arguments)
    {
        if ($className = static::findFieldClass($method)) {
            $column = Arr::get($arguments, 0, '');

            $element = new $className($column, array_slice($arguments, 1), $this);
            $this->pushField($element);

            return $element;
        }

        throw new \Exception("Field type [$method] does not exist.");
    }

    /**
     * Get or set title for form.
     *
     * @param string|null $title
     *
     * @return $this|string
     */
    public function title(?string $title = null)
    {
        if ($title === null) {
            return $this->title ?: ($this->title = $this->isCreating() ? trans('admin.create') : trans('admin.edit'));
        }
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        if (!empty($this->footer)) {
            return parent::getFooter();
        }

        $saveText = trans('admin.save');
        $resetText = trans('admin.reset');
        $footer = <<<FOOTER
            <div class="form-group">
                <div class="col-sm-{$this->width['label']}"></div>
                <div class="col-sm-{$this->width['field']}">
                    <div class="btn-group pull-left">
                        <button type="reset" class="btn btn-warning" data-loading-text="&nbsp;{$saveText}">{$resetText}</button>
                    </div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-primary" data-loading-text="&nbsp;{$saveText}">{$saveText}</button>
                    </div>
                </div>
            </div>
FOOTER;

        $this->setFooter($footer);

        return parent::getFooter();
    }

    /**
     * Set Tools for form.
     *
     * @param Closure $callback
     *
     * @return $this;
     */
    public function tools(Closure $callback)
    {
        $callback->call($this, $this->tools);

        return $this;
    }

    /**
     * Get tools of form.
     *
     * @return $this;
     */
    public function renderTools()
    {
        return $this->tools->render();
    }

    /**
    * @return Collection
    */
    public function getFields()
    {
        return $this->fields;
    }

    public function getEnctype()
    {
        return $this->enctype ? sprintf('enctype="%"', $this->enctype) : '';
    }

    /**
     * Get or set method for form.
     *
     * @param string|null $method  post, get
     *
     * @return $this|string
     */
    public function method(?string $method = null)
    {
        if ($method === null) {
            return $this->method;
        }
        $this->method = $method;

        return $this;
    }

    /**
     * Get or set action for form.
     *
     * @param string|null $action
     *
     * @return $this|string
     */
    public function action(?string $action = null)
    {
        if ($action === null) {
            return $this->action;
        }
        $this->action = admin_url($action);

        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return $this
     */
    public function password(string $column, string $label = '')
    {
        $label = $label ?: trans($this->model->getTable() . '.fields.' . $column);
        $this->column = $column;
        $placeholder = trans('admin.input') . " " . (empty($label) ? $column : $label);

        $input = <<<INPUT
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-eye-slash fa-fw"></i>
                </div>
                <input type="password" name="{$column}" class="form-control" placeholder="{$placeholder}" #required#>
            </div>
INPUT;
        $label = $this->getLabel($column, $label);
        $this->fields[$column] = compact('label', 'input');

        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return $this
     */
    public function listbox(string $column, string $label = '')
    {
        $label = $label ?: trans($this->model->getTable() . '.fields.' . $column);
        $this->column = $column;

        $input = <<<INPUT
            <select class="duallistbox" multiple="multiple" name="{$column}[]" #required#>
                #options#
            </select>
INPUT;

        $label = $this->getLabel($column, $label);
        $this->fields[$column] = compact('label', 'input');

        return $this;
    }

    public function rules(string $rules)
    {
        $rules = explode('|', trim($rules, '|'));
        foreach ($rules as $rule) {
            switch ($rule) {
                case 'required':
                    $this->required();
                    break;

                default:
                    break;
            }
        }

        return $this;
    }

    /**
     * @param string $label
     *
     * @return string
     */
    private function getLabel(string $column, string $label = ''): string
    {
        $value = empty($label) ? $column : $label;
        $width = &$this->width['label'];

        return "<label for='{$column}' class='#required# control-label col-sm-{$width}'>{$value}</label>";
    }

    private function isCreating(): bool
    {
        return $this->isCreating ?? ($this->isCreating = empty($this->model->toArray()));
    }
}
