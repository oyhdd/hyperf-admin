<?php declare (strict_types=1);

namespace Oyhdd\Admin\Model\Widget;

use Closure;
use Oyhdd\Admin\Model\BaseModel;

class Form
{
    protected $model;
    protected $column;
    protected $label;
    protected $rows = [];
    protected $expandFilter = false;

    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    /**
     * 显示input输入框
     * @param string $column
     * @param string $label
     */
    public function text(string $column, string $label = '', string $ico = 'fa-pencil-alt')
    {
        $column = dot_to_array_str($column);
        $this->column = $column;
        $this->label  = $label;

        $value = $this->model->{$column} ?? '';
        $placeholder = trans('admin.input') . " " . (empty($label) ? $column : $label);
        $input = <<<INPUT
            <div class="input-group-prepend">
                <span class="input-group-text bg-white">
                    <i class="fa {$ico}"></i>
                </span>
            </div>
            <input type="text" class="form-control" placeholder="{$placeholder}" id="{$column}" name="{$column}" value="{$value}" #required#>
INPUT;
        $label = $this->getLabel($column, $label);
        $this->{$column} = compact('label', 'input');
        return $this;
    }

    /**
     * 显示textarea输入框
     * @param string $column
     * @param string $label
     */
    public function textarea(string $column, string $label = '')
    {
        $column = dot_to_array_str($column);
        $this->column = $column;
        $this->label  = $label;

        $value = $this->model->{$column} ?? '';
        $placeholder = trans('admin.input') . " " . (empty($label) ? $column : $label);
        $input = <<<INPUT
            <textarea name="{$column}" class="form-control" id="{$column}" rows="5" placeholder="{$placeholder}" #required# #>{$value}</textarea>
INPUT;
        $label = $this->getLabel($column, $label);
        $this->{$column} = compact('label', 'input');
        return $this;
    }

    /**
     * 显示密码输入框
     * @param string $column
     * @param string $label
     */
    public function password(string $column, string $label = '')
    {
        $column = dot_to_array_str($column);
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
     * 显示框
     * @param string $column
     * @param string $label
     */
    public function display(string $column, string $label = '')
    {
        $column = dot_to_array_str($column);
        $this->column = $column;
        $this->label  = $label;

        $value = $this->model->{$column} ?? '';
        $input = <<<INPUT
            <input type="text" class="form-control" readonly="readonly" id="{$column}" name="{$column}" value="{$value}">
INPUT;

        $label = $this->getLabel($column, $label);
        $this->{$column} = compact('label', 'input');
        return $this;
    }

    /**
     * 显示提示信息
     * @param string $text
     * @param string $icon
     */
    public function help($text = '', $icon = 'fa-info-circle')
    {

        $input = <<<INPUT
            <label class="col-sm-3 col-form-label"></label>
            <span class="help-block col-sm-7 col-form-label">
                <i class="fa {$icon}"></i>&nbsp;{$text}
            </span>
INPUT;

        $this->{$this->column}['help'] = $input;
        return $this;
    }

    /**
     * 设置默认值
     * @param mixed $value
     */
    public function default($value)
    {
        $input = $this->{$this->column}['input'];
        $input = preg_replace('/value="([^"]*)"/', "value={$value}", $input);
        $input = preg_replace('/#>((.|\n)*?)<\/textarea>/', ">{$value}</textarea>", $input);

        $this->{$this->column}['input'] = $input;
        return $this;
    }

    /**
     * 显示双列表框
     * @param string $column
     * @param string $label
     */
    public function listbox(string $column, string $label = '')
    {
        $column = dot_to_array_str($column);
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
     * 显示单选下拉框
     * @param string $column
     * @param string $label
     */
    public function select(string $column, string $label = '')
    {
        $column = dot_to_array_str($column);
        $this->column = $column;
        $this->label  = $label;
        $placeholder = trans('admin.select') . " " . (empty($label) ? $column : $label);

        $input = <<<INPUT
            <input type="hidden" name="{$column}">
            <select class="form-control select2" id="{$column}" name="{$column}" data-placeholder="{$placeholder}" #required# style="width:100% !important">
                <option value=""></option>
                #options#
            </select>
INPUT;

        $label = $this->getLabel($column, $label);
        $this->{$column} = compact('label', 'input');
        return $this;
    }

    /**
     * 显示多选下拉框
     * @param string $column
     * @param string $label
     */
    public function multipleSelect(string $column, string $label = '')
    {
        $column = dot_to_array_str($column);
        $this->column = $column;
        $this->label  = $label;
        $placeholder = trans('admin.select') . " " . (empty($label) ? $column : $label);
        $input = <<<INPUT
            <select class="form-control select2" multiple="multiple" data-placeholder="{$placeholder}" id="{$column}" name="{$column}[]" #required#  style="width:100% !important">
                #options#
            </select>
            <input type="hidden" name="{$column}[]">
INPUT;

        $label = $this->getLabel($column, $label);
        $this->{$column} = compact('label', 'input');
        return $this;
    }

    /**
     * 下拉框的下拉选项
     * @param array options
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

    /**
     * 输入框规则
     * @param  string $rules
     */
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
     * 删除该输入框
     * @param bool $delete
     */
    public function delete(bool $delete = true)
    {
        if ($delete) {
            $this->{$this->column} = '';
        }
        return $this;
    }

    /**
     * 是否将该输入框设置为hidden
     * @param bool $hidden
     */
    public function hidden(bool $hidden = true)
    {
        if ($hidden) {
            $this->{$this->column}['label'] = '';
            $this->{$this->column}['input'] = str_replace('type="text"', 'type="hidden"', $this->{$this->column}['input']);
        }
        return $this;
    }

    /**
     * 获取label
     * @param string $label
     *
     * @return string
     */
    private function getLabel(string $column, string $label = ''): string
    {
        $column = dot_to_array_str($column);
        $value = empty($label) ? $column : $label;
        return "<label for='{$column}' class='#required# control-label col-form-label col-sm-3'>{$value}</label>";
    }

    /**
     * Add one row
     *
     * @param $content
     *
     * @return $this
     */
    public function row($content)
    {
        if ($content instanceof Closure) {
            $this->rows[] = call_user_func($content);
        } else {
            $this->rows[] = $content;
        }

        return $this;
    }

    /**
     * Add a column.
     *
     * @param int $width
     * @param $content
     */
    public function column(int $width, $content)
    {
        $column = [
            'width' => $width,
            'column' => '',
        ];
        if ($content instanceof Form) {
            $column['column'] = $this->column;
        }
        return $column;
    }

    /**
     * 获取html
     * @return string
     */
    public function render()
    {
        $html = '';
        foreach ($this->rows as $row) {
            $html .= "<div class='row'>";
            foreach ($row as $column) {
                $html .= "<div class='col-sm-{$column['width']}'>";
                if (isset($this->{$column['column']}) && isset($this->{$column['column']}['label']) && isset($this->{$column['column']}['input'])) {
                    $html .= '<div class="form-group row">'
                                .$this->{$column['column']}['label']
                                .'<div class="input-group col-sm-7">'
                                    .$this->{$column['column']}['input']
                                .'</div>'
                                .(isset($this->{$column['column']}['help']) ? $this->{$column['column']}['help'] : '')
                            .'</div>';
                }
                $html .= "</div>";
            }
            $html .= "</div>";
        }

        return $html;
    }

    /**
     * 展开搜索框
     * @param  bool $expand 是否展开
     * @return $this
     */
    public function expandFilter(bool $expand = true)
    {
        $this->expandFilter = $expand;
        return $this;
    }

    /**
     * 获取搜索框展开状态
     * @return bool
     */
    public function getExpandFilter(): bool
    {
        return $this->expandFilter;
    }
}
