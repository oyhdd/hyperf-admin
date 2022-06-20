<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form\Field;

use Oyhdd\Admin\Widget\Form\Field;

class Tree extends Field
{
    /**
     * @param string $column
     * @param string $label
     *
     * @return $this
     */
    public function render(string $column, string $label = '')
    {
        $label = $label ?: trans($this->model->getTable() . '.fields.' . $column);
        $this->column = $column;
        $data = '[{"id":1,"text":"Auth management","parent":"#","state":[]},{"id":2,"text":"Users","parent":1,"state":{"selected":true,"disabled":false}},{"id":3,"text":"Roles","parent":1,"state":{"selected":true}},{"id":4,"text":"Permissions","parent":3,"state":{"selected":true}},{"id":5,"text":"Menu","parent":3,"state":{"selected":true}},{"id":6,"text":"Extension","parent":1,"state":{"selected":true}},{"id":7,"text":"\u666e\u901a\u7528\u6237","parent":"#","state":{"selected":true}}]';
        $expand = true;

        $input = view('widget.form.tree', compact('column', 'data', 'expand'))->render();

        $label = $this->getLabel($column, $label);
        $this->fields[$column] = compact('label', 'input');

        return $this;
    }
}