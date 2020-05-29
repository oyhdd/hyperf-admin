<?php declare (strict_types=1);

namespace Oyhdd\Admin\Model\Widget;

use Oyhdd\Admin\Model\BaseModel;

class Form
{
    protected $model;
    protected $column;
    protected $label;

    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return \Oyhdd\Admin\Model\BaseModel\BaseModel
     */
    public function text(string $column, string $label = '')
    {
        $this->column = $column;
        $this->label  = $label;

        $value = $this->model->{$column} ?? '';
        $placeholder = trans('admin.input') . " " . (empty($label) ? $column : $label);
        $input = <<<INPUT
            <div class="input-group-prepend">
                <span class="input-group-text bg-white">
                    <i class="fa fa-pencil-alt"></i>
                </span>
            </div>
            <input type="text" class="form-control" placeholder="{$placeholder}" id="{$column}" name="{$column}" value="{$value}" #required#>
INPUT;
        $label = $this->getLabel($column, $label);
        $this->{$column} = compact('label', 'input');
        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return \Oyhdd\Admin\Model\BaseModel\BaseModel
     */
    public function textarea(string $column, string $label = '')
    {
        $this->column = $column;
        $this->label  = $label;

        $value = $this->model->{$column} ?? '';
        $placeholder = trans('admin.input') . " " . (empty($label) ? $column : $label);
        $input = <<<INPUT
            <textarea name="{$column}" class="form-control" id="{$column}" rows="5" placeholder="{$placeholder}" #required#>{$value}</textarea>
INPUT;
        $label = $this->getLabel($column, $label);
        $this->{$column} = compact('label', 'input');
        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return \Oyhdd\Admin\Model\BaseModel\BaseModel
     */
    public function password(string $column, string $label = '')
    {
        $this->column = $column;
        $this->label  = $label;
        $ori_column = str_replace('_confirmation', '', $column);
        $value = $this->model->{$ori_column} ?? '';
        $placeholder = trans('admin.input') . " " . (empty($label) ? $column : $label);
        $input = <<<INPUT
            <div class="input-group-prepend">
                <span class="input-group-text bg-white">
                    <i class="fa fa-eye-slash fa-fw"></i>
                </span>
            </div>
            <input type="password" name="{$column}" class="form-control" id="{$column}" placeholder="{$placeholder}" value="{$value}" #required#>
INPUT;
        $label = $this->getLabel($column, $label);
        $this->{$column} = compact('label', 'input');
        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return \Oyhdd\Admin\Model\BaseModel\BaseModel
     */
    public function display(string $column, string $label = '')
    {
        $this->column = $column;
        $this->label  = $label;

        $value = $this->model->{$column} ?? '';
        $input = <<<INPUT
            <input type="text" class="form-control" disabled="disabled" id="{$column}" name="{$column}" value="{$value}">
INPUT;

        $label = $this->getLabel($column, $label);
        $this->{$column} = compact('label', 'input');
        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return \Oyhdd\Admin\Model\BaseModel\BaseModel
     */
    public function listbox(string $column, string $label = '')
    {
        $this->column = $column;
        $this->label  = $label;

        $input = <<<INPUT
            <select class="duallistbox" multiple="multiple" id="{$column}" name="{$column}[]" #required#>
                #options#
            </select>
INPUT;

        $label = $this->getLabel($column, $label);
        $this->{$column} = compact('label', 'input');
        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return \Oyhdd\Admin\Model\BaseModel\BaseModel
     */
    public function multipleSelect(string $column, string $label = '')
    {
        $this->column = $column;
        $this->label  = $label;
        $placeholder = trans('admin.select') . " " . (empty($label) ? $column : $label);
        $input = <<<INPUT
            <select class="form-control select2" multiple="multiple" data-placeholder="{$placeholder}" id="{$column}" name="{$column}[]" #required#>
                #options#
            </select>
INPUT;

        $label = $this->getLabel($column, $label);
        $this->{$column} = compact('label', 'input');
        return $this;
    }

    /**
     * @param array options
     *
     * @return \Oyhdd\Admin\Model\BaseModel\BaseModel
     */
    public function options(array $options = [], array $select = [])
    {
        $html = "";
        foreach ($options as $key => $value) {
            $selected = in_array($key, $select) ? 'selected' : '';
            $html .= "<option value='{$key}' {$selected}>{$value}</option>";
        }
        $this->{$this->column}['input'] = str_replace('#options#', $html, $this->{$this->column}['input']);
        return $this;
    }

    public function rules(string $rules)
    {
        $rules = explode('|', trim($rules, '|'));
        foreach ($rules as $rule) {
            switch ($rule) {
                case 'required':
                    $this->{$this->column}['label'] = str_replace('#required#', 'asterisk', $this->{$this->column}['label']);
                    $this->{$this->column}['input'] = str_replace('#required#', 'required', $this->{$this->column}['input']);
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
        return "<label for='{$column}' class='#required# control-label col-form-label text-right col-sm-3'>{$value}</label>";
    }
}
