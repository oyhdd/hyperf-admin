<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Show;

use Closure;
use App\Model\Model;
use Hyperf\Utils\Str;
use Oyhdd\Admin\Widget\Box;
use Oyhdd\Admin\Widget\Tools;

class Show extends Box
{
    /**
     * Model of the show.
     *
     * @var Model
     */
    protected $model;

    /**
     * @var Tools
     */
    protected $tools;

    /**
     * Show field.
     *
     * @var array
     */
    protected $fields = [];

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
     * Current field name.
     *
     * @var string
     */
    protected $column;

    /**
     * Current field value.
     *
     * @var string
     */
    protected $value;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->tools = new Tools();
    }

    /**
     * Get or set title for show.
     *
     * @param string|null $title
     *
     * @return $this|string
     */
    public function title(?string $title = null)
    {
        if ($title === null) {
            return $this->title ?: trans('admin.detail');
        }
        $this->title = $title;

        return $this;
    }

    /**
     * Set Tools for show.
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
     * Get tools of show.
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
    public function field(string $column, string $label = '')
    {
        $label = $label ?: trans($this->model->getTable() . '.fields.' . $column);
        $this->column = $column;
        $this->value = $this->getValue();

        $input = <<<INPUT
            <div class="box box-solid box-default no-margin">
                <div class="box-body" style="min-height: 40px;">{$this->value}</div>
            </div>
INPUT;
        $label = $this->getLabel($column, $label);
        $this->fields[$column] = compact('label', 'input');

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
        $input = <<<INPUT
        <div class="box box-solid box-default no-margin">
            <div class="box-body" style="min-height: 40px;">{$value}</div>
        </div>
INPUT;

        $this->fields[$this->column]['input'] = $input;

        return $this;
    }

    public function label(string $style = 'primary')
    {
        $value = collect($this->value)->map(function ($name) use($style) {
            return "<span class='label label-{$style}'>{$name}</span>";
        })->implode(' ');

        $input = <<<INPUT
        <div class="box box-solid box-default no-margin">
            <div class="box-body" style="min-height: 40px;">{$value}</div>
        </div>
INPUT;

        $this->fields[$this->column]['input'] = $input;

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
     * @return $this
     */
    public function textarea()
    {
        $input = <<<INPUT
            <pre class="box box-solid box-default no-margin" style="min-height: 42px;">{$this->value}</pre>
INPUT;
        $this->fields[$this->column]['input'] = $input;

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

        return "<label for='{$column}' class='control-label col-sm-{$width}'>{$value}</label>";
    }

    private function getValue()
    {
        $value = $this->model->{$this->column} ?? '';
        if (is_string($value)) {
            $value = htmlspecialchars($this->model->{$this->column} ?? '');
        }
        return $value;
    }
}
