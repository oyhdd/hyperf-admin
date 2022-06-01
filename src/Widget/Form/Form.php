<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form;

use App\Model\Model;
use Closure;
use Illuminate\Support\Str;
use Oyhdd\Admin\Widget\Box;
use Oyhdd\Admin\Widget\Tools;

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
     * @var array
     */
    protected $fields = [];

    /**
     * Form hidden field.
     *
     * @var array
     */
    protected $hiddenFields = [];

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

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->tools = new Tools();
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
    * @return array
    */
    public function getFields()
    {
        return $this->fields;
    }

    /**
    * @return array
    */
    public function getHiddenFields()
    {
        return $this->hiddenFields;
    }

    /**
     * Set width for field and label.
     *
     * @param int $field
     * @param int $label
     *
     * @return $this
     */
    public function width(int $field = 8, int $label = 2)
    {
        $this->width = [
            'label' => $label,
            'field' => $field,
        ];

        return $this;
    }

    /**
     * Get width for field and label.
     *
     * @return array
     */
    public function getWidth(string $key)
    {
        return $this->width[$key];
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
     * Get or set default value for field.
     *
     * @param mixed $default
     *
     * @return $this|mixed
     */
    public function default($value)
    {
        $this->fields[$this->column]['input'] = str_replace('#value#', "value={$value}", $this->fields[$this->column]['input']);
        $this->fields[$this->column]['input'] = str_replace('#checked#', $value ? 'checked' : '', $this->fields[$this->column]['input']);

        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return $this
     */
    public function html(string $html, string $label = '')
    {
        $label = $label;
        $column = 'column_' . Str::random(8);

        $label = $this->getLabel($column, $label);
        $this->fields[$column] = [
            'label' => $label,
            'input' => $html
        ];

        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return $this
     */
    public function text(string $column, string $label = '')
    {
        $label = $label ?: trans($this->model->getTable() . '.fields.' . $column);
        $this->column = $column;
        $value = '#value#';
        if (isset($this->model->{$column}) && $this->model->{$column} !== '') {
            $value = "value=" . $this->model->{$column};
        }
        $placeholder = trans('admin.input') . " " . ($label ?: $column);

        $input = <<<INPUT
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-pencil fa-fw"></i>
                </div>
                <input type="text" class="form-control" placeholder="{$placeholder}" name="{$column}" {$value} #disabled# #required#>
            </div>
INPUT;
        $label = $this->getLabel($column, $label);
        $this->fields[$column] = compact('label', 'input');

        return $this;
    }

    /**
     * @param string $column
     *
     * @return $this
     */
    public function hidden(string $column, $value)
    {
        $this->column = $column;

        $input = <<<INPUT
            <input type="hidden" name="{$column}" value="{$value}">
INPUT;
        $this->hiddenFields[$column] = compact('input');

        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return $this
     */
    public function number(string $column, string $label = '')
    {
        $label = $label ?: trans($this->model->getTable() . '.fields.' . $column);
        $this->column = $column;
        $value = '#value#';
        if (isset($this->model->{$column}) && $this->model->{$column} !== '') {
            $value = "value=" . $this->model->{$column};
        }
        $placeholder = trans('admin.input') . " " . ($label ?: $column);

        $input = <<<INPUT
            <div class="input-group">
                <input class="form-control {$column}" type="text" name="{$column}" {$value} #min# #max# placeholder="{$placeholder}" style="width: 100px; text-align: center;" #required#>
            </div>
            <script>
                $(function () {
                    $('.{$column}:not(.initialized)')
                        .addClass('initialized')
                        .bootstrapNumber({
                            upClass: 'success',
                            downClass: 'primary',
                            center: true
                        });
                })
            </script>
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
    public function switch(string $column, string $label = '')
    {
        $label = $label ?: trans($this->model->getTable() . '.fields.' . $column);
        $this->column = $column;
        $checked = !empty($this->model->{$column}) ? "checked" : '#checked#';
        $placeholder = trans('admin.input') . " " . ($label ?: $column);
        $id = 'switch_' . Str::random(8);

        $input = <<<INPUT
            <input name="{$column}" type="hidden" value="0" />
            <input id="{$id}" class="{$column}" type="checkbox" value="1" name="{$column}" #disabled# {$checked} />
            <script>
                $(function () {
                    Switchery($('#{$id}')[0], {
                        size: 'small',
                        color: '#00a65a'
                    });
                })
            </script>
INPUT;
        $label = $this->getLabel($column, $label);
        $this->fields[$column] = compact('label', 'input');

        return $this;
    }

    /**
     * Set min value for number field.
     *
     * @param int $value
     *
     * @return $this
     */
    public function min(int $value)
    {
        $this->fields[$this->column]['input'] = str_replace('#min#', "min={$value}", $this->fields[$this->column]['input']);

        return $this;
    }

    /**
     * Set max value for number field.
     *
     * @param int $value
     *
     * @return $this
     */
    public function max(int $value)
    {
        $this->fields[$this->column]['input'] = str_replace('#max#', "max={$value}", $this->fields[$this->column]['input']);

        return $this;
    }

    /**
     * Set disabled attribute of the element.
     *
     * @param bool $disabled
     *
     * @return $this
     */
    public function disabled(bool $disabled = true)
    {
        if ($disabled) {
            $this->fields[$this->column]['input'] = str_replace('#disabled#', 'disabled', $this->fields[$this->column]['input']);
        }

        return $this;
    }

    /**
     * @param string $html
     *
     * @return $this
     */
    public function help(string $html)
    {
        $input = <<<INPUT
            <span class="help-block">
                <i class="fa fa-info-circle"></i>&nbsp;{$html}
            </span>
INPUT;
        $this->fields[$this->column]['input'] .= $input;

        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return $this
     */
    public function textarea(string $column, string $label = '')
    {
        $label = $label ?: trans($this->model->getTable() . '.fields.' . $column);
        $this->column = $column;
        $value = $this->model->{$column} ?? '';
        $placeholder = trans('admin.input') . " " . (empty($label) ? $column : $label);

        $input = <<<INPUT
            <textarea name="{$column}" class="form-control" rows="5" placeholder="{$placeholder}" #disabled# #required#>{$value}</textarea>
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
    public function display(string $column, string $label = '')
    {
        $label = $label ?: trans($this->model->getTable() . '.fields.' . $column);
        $this->column = $column;
        $value = $this->model->{$column} ?? '';

        $input = <<<INPUT
            <input type="text" class="form-control" disabled name="{$column}" value="{$value}">
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

    /**
     * @param string $column
     * @param string $label
     *
     * @return $this
     */
    public function select(string $column, string $label = '')
    {
        $label = $label ?: trans($this->model->getTable() . '.fields.' . $column);
        $this->column = $column;
        $placeholder = trans('admin.select') . " " . (empty($label) ? $column : $label);

        $input = <<<INPUT
            <select class="form-control {$column} select2-hidden-accessible" style="width: 100%;" data-placeholder="{$placeholder}" name="{$column}" #disabled# #required#>
                <option></option>
                #options#
            </select>
            <script>
                $("select.{$column}").select2({"allowClear":true});
            </script>
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
    public function multipleSelect(string $column, string $label = '')
    {
        $label = $label ?: trans($this->model->getTable() . '.fields.' . $column);
        $this->column = $column;
        $placeholder = trans('admin.select') . " " . (empty($label) ? $column : $label);

        $input = <<<INPUT
            <select class="form-control {$column} select2-hidden-accessible" style="width: 100%;" data-placeholder="{$placeholder}"
                name="{$column}[]" multiple="multiple" #disabled# #required#>
                <option></option>
                #options#
            </select>
            <script>
                $("select.{$column}").select2();
            </script>
INPUT;

        $label = $this->getLabel($column, $label);
        $this->fields[$column] = compact('label', 'input');

        return $this;
    }

    /**
     * @param array $options
     * @param array $select     selected while model is empty
     *
     * @return $this
     */
    public function options(array $options = [], array $select = [])
    {
        $html = "";
        foreach ($options as $key => $value) {
            $selected = in_array($key, $select) ? 'selected' : '';
            $html .= "<option value='{$key}' {$selected}>{$value}</option>";
        }
        $this->fields[$this->column]['input'] = str_replace('#options#', $html, $this->fields[$this->column]['input']);

        return $this;
    }

    public function required()
    {
        $this->fields[$this->column]['label'] = str_replace('#required#', 'asterisk', $this->fields[$this->column]['label']);
        $this->fields[$this->column]['input'] = str_replace('#required#', 'required', $this->fields[$this->column]['input']);

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
