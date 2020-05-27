<?php declare (strict_types=1);

namespace Oyhdd\Admin\Model\Action;

use Oyhdd\Admin\Model\BaseModel;

trait Form
{
    protected $column;
    protected $label;
    public $formData = [];

    /**
     * @param string $column
     * @param string $label
     *
     * @return \Oyhdd\Admin\Model\BaseModel\BaseModel
     */
    public function text(string $column, string $label = '', bool $required = false): BaseModel
    {
        $value = $this->{$column} ?? '';
        $requiredText = $required ? 'required' : '';
        $input = <<<INPUT
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-pencil-alt"></i>
                </span>
            </div>
            <input type="text" class="form-control bg-white" id="{$column}" name="{$column}" value="{$value}" {$requiredText}>
INPUT;

        $this->addField($column, $label, $input, $required);
        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return \Oyhdd\Admin\Model\BaseModel\BaseModel
     */
    public function display(string $column, string $label = ''): BaseModel
    {
        $value = $this->{$column} ?? '';
        $input = <<<INPUT
            <input type="text" class="form-control" disabled="disabled" id="{$column}" name="{$column}" value="{$value}">
INPUT;

        $this->addField($column, $label, $input);
        return $this;
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return \Oyhdd\Admin\Model\BaseModel\BaseModel
     */
    public function listbox(string $column, string $label = '', bool $required = false): BaseModel
    {
        $requiredText = $required ? 'required' : '';
        $input = <<<INPUT
            <select class="duallistbox" multiple="multiple" id="{$column}" name="{$column}[]" {$requiredText}>
                duallistbox_options
            </select>
INPUT;

        $this->addField($column, $label, $input, $required);
        return $this;
    }

    /**
     * @param array options
     *
     * @return \Oyhdd\Admin\Model\BaseModel\BaseModel
     */
    public function options(array $options = [], array $select = []): BaseModel
    {
        $html = "";
        foreach ($options as $key => $value) {
            $selected = in_array($key, $select) ? 'selected' : '';
            $html .= "<option value='{$key}' {$selected}>{$value}</option>";
        }
        $this->formData[$this->column]['input'] = str_replace('duallistbox_options', $html, $this->formData[$this->column]['input']);
        return $this;
    }

    public function rules(string $rules): BaseModel
    {
        $rules = explode('|', trim($rules, '|'));
        foreach ($rules as $rule) {
            switch ($rule) {
                case 'required':
                    $this->text($this->column, $this->label, true);
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
    private function getLabel(string $column, string $label = '', bool $required = false): string
    {
        $this->column = $column;
        $this->label = $label;
        $value = empty($label) ? $column : $label;
        $asterisk = $required ? 'asterisk' : '';
        return "<label for='{$column}' class='{$asterisk} control-label col-form-label text-right col-sm-3'>{$value}</label>";
    }

    private function addField(string $column, string $label, string $input = '', bool $required = false)
    {
        $label = $this->getLabel($column, $label, $required);
        $this->formData[$this->column] = compact('label', 'input');
    }
}
