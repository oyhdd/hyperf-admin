<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Model;

use App\Model\Model;

class BaseModel extends Model
{
    use ExportTrait;

    /**
     * Create a new Model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->init();
        parent::__construct($attributes);

    }

    protected function init()
    {
    }

    public function tree(array $selectedIds = [])
    {
        $models = $this->toArray();
        $list = [];
        foreach ($models as $model) {
            $list[] = [
                'id' => $model['id'],
                'text' => $model['name'],
                'parent' => $model['parent_id'] ?: '#',
                'state' => [
                    'disabled' => false,
                    'selected' => in_array($model['id'], $selectedIds),
                ]
            ];
        }

        return $list;
    }

}
