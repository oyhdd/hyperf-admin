<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Widget\Form\Field;

use Closure;
use Hyperf\Utils\Contracts\Arrayable;
use Oyhdd\Admin\Widget\Form\Field;

class Tree extends Field
{
    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = 'widget.form.tree';

    /**
     * @var bool
     */
    protected $expand = false;

    /**
     * @var array
     */
    protected $nodes = [];

    /**
     * @var int
     */
    protected $rootParentId = 0;

    /**
     * @var bool
     */
    protected $readOnly = false;

    /**
     * @var array
     */
    protected $columnNames = [
        'id'     => 'id',
        'text'   => 'name',
        'parent' => 'parent_id',
    ];

    /**
     * @var array
     */
    protected $options = [
        'plugins' => ['checkbox', 'types'],
        'core'    => [
            'check_callback' => true,
            'themes' => [
                'name'       => 'proton',
                'responsive' => true,
                'ellipsis'   => true,
            ],
            'dblclick_toggle' => false,
        ],
        'checkbox' => [
            'keep_selected_style' => false,
            'three_state'         => true,
            'cascade_to_disabled' => false,
            'whole_node'          => true,
        ],
        'types' => [
            'default'  => [
                'icon' => false,
            ],
        ],
    ];

    /**
     * @param bool $expand
     *
     * @return $this
     */
    public function expand(bool $expand = true)
    {
        $this->expand = $expand;

        return $this;
    }

    public function readOnly(bool $value = true)
    {
        $this->readOnly = true;

        return $this;
    }

    public function setIdColumn(string $name)
    {
        $this->columnNames['id'] = $name;

        return $this;
    }

    public function setTitleColumn(string $name)
    {
        $this->columnNames['text'] = $name;

        return $this;
    }

    public function setParentColumn(string $name)
    {
        $this->columnNames['parent'] = $name;

        return $this;
    }

    public function rootParentId($id)
    {
        $this->rootParentId = $id;

        return $this;
    }

    /**
     * @param array|Arrayable|Closure  $nodes  exp:
     *                                    {
     *                                        "id": "1",
     *                                        "parent": "#",
     *                                        "text": "Dashboard",
     *                                        // "state": {"selected": true, "disabled": false}
     *                                    }
     * @return $this
     */
    public function nodes($nodes)
    {
        if ($nodes instanceof Closure) {
            $this->nodes = $nodes->call($this->form->model());
        } else {
            $this->nodes = (array) $nodes;
        }

        if ($this->nodes instanceof Arrayable) {
            $this->nodes = $this->nodes->toArray();
        }

        return $this;
    }

    protected function formatNodes()
    {
        $value = $this->value();
        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        }

        $idColumn = $this->columnNames['id'];
        $textColumn = $this->columnNames['text'];
        $parentColumn = $this->columnNames['parent'];

        $value = array_column($value, $idColumn);
        if (empty($this->nodes)) {
            return;
        }

        $parentIds = $nodes = [];
        foreach ($this->nodes as &$v) {
            if (empty($v[$idColumn])) {
                continue;
            }

            $parentId = $v[$parentColumn] ?? '#';
            if (empty($parentId) || $parentId == $this->rootParentId) {
                $parentId = '#';
            } else {
                if (!isset($parentIds[$parentId])) {
                    $parentIds[$parentId] = true;
                }
                if (empty($v['state']['disabled'])) {
                    $parentIds[$parentId] = false;
                }
            }

            $v['state'] = $v['state'] ?? [];

            if ($value && in_array($v[$idColumn], $value)) {
                $v['state']['selected'] = true;
            }

            if ($this->readOnly) {
                $v['state']['disabled'] = true;
            }

            $nodes[] = [
                'id'     => $v[$idColumn],
                'text'   => $v[$textColumn] ?? null,
                'parent' => $parentId,
                'state'  => $v['state'],
            ];
        }
        foreach ($nodes as $key => $node) {
            if (isset($parentIds[$node['id']])) {
                $nodes[$key]['state']['disabled'] = $parentIds[$node['id']];
            }
        }

        $this->nodes = &$nodes;
    }

    /**
     * Render this filed.
     */
    public function render()
    {
        $this->formatNodes();

        $this->addVariables([
            'expand'  => $this->expand,
            'nodes'   => json_encode($this->nodes),
            'options' => json_encode($this->options),
        ]);
        
        return parent::render();
    }
}