<?php declare (strict_types=1);

namespace Oyhdd\Admin\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\Utils\{Arr, Str};
use Oyhdd\Admin\Model\Widget\Form;

class BaseModel extends Model
{

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'create_time';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'update_time';

    /**
     * status
     */
    const STATUS_DELETED = -1; //删除
    const STATUS_DISABLE = 0;  //未启用
    const STATUS_ENABLE  = 1;  //正常
    public static $status = [
        self::STATUS_DISABLE => '禁用',
        self::STATUS_ENABLE  => '正常'
    ];

    /**
     * 允许自动条件筛选的字段
     * @return array
     */
    protected function filter(): array
    {
        return ['id'];
    }

    /**
     * 获取所有数据
     * @return array
     */
    public static function getAll(array $select = ['*'], array $where = []): array
    {
        return self::select($select)->where($where)->get()->toArray();
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @throws \Hyperf\Database\Model\MassAssignmentException
     * @return false|$this
     */
    public function fill(array $attributes)
    {
        if (empty($attributes)) {
            return false;
        }
        return parent::fill($attributes);
    }

    /**
     * Determine if the current request URI matches a pattern.
     *
     * @param mixed ...$patterns
     */
    public static function isValidaUrl(array $patterns = ['*'], string $url = ''): bool
    {
        if (empty($url)) {
            return true;
        }
        foreach ($patterns as $pattern) {
            if (Str::is($pattern, rawurldecode($url))) {
                return true;
            }
        }

        return false;
    }

    /**
     * 条件过滤
     * @param  array  $params
     * @return Model
     */
    public function filterWhere(array $params = [])
    {
        $_sort = $params['_sort'] ?? [];
        $params = array_to_dot($params, 2);
        $query = static::query();
        $where = Arr::only($params, $this->filter());
        $with = [];

        foreach ($where as $key => $value) {
            if (is_string($value)) {
                $value = is_string($value) ? trim(rawurldecode($value)) : $value;
                if ($value === '') {
                    unset($where[$key]);
                    continue;
                }
            } elseif (is_array($value)) {
                $value = array_filter($value, function ($val) {
                    return ($val === '' || $val === null) ? false : true;
                });
                if (empty($value)) {
                    unset($where[$key]);
                    continue;
                }
            }
            if (count($keys = explode('.', $key)) > 1) {
                // 模型关系查询
                list($relation, $relation_field) = $keys;
                if (!method_exists($this, $relation)) {
                    continue;
                }
                $query = $query->whereHas($relation, function ($query) use ($relation_field, $value) {
                    if (is_array($value)) {
                        $query->whereIn($relation_field, $value);
                    } else {
                        $query->where($relation_field, $value);
                    }
                });
                unset($where[$key]);
            }
        }
        if (!empty($where)) {
            $query->where($where);
        }

        foreach ($_sort ?? [] as $column => $sort) {
            $query->orderBy($column, $sort);
        }
        return $query;
    }

    /**
     * 搜索框
     * @param  array  $params   参数
     * @param  bool   $expand   是否展开
     * @return Model
     */
    public function searchForm(array $params = [], bool $expand = false)
    {
        $model = new static();
        $params = array_to_dot($params, 2);
        $params = Arr::only($params, $this->filter());
        foreach ($params as $key => $value) {
            $key = dot_to_array_str($key);
            if (is_string($value)) {
                $value = is_string($value) ? trim(urldecode($value)) : $value;
                if ($value !== '') {
                    $model->{$key} = $value;
                }
            } elseif (is_array($value)) {
                $value = array_filter($value, function ($val) {
                    return ($val === '' || $val === null) ? false : true;
                });
                if (!empty($value)) {
                    $model->{$key} = $value;
                }
            }
        }
        $form = new Form($model);
        if ($expand || (!empty($model->toArray()) && !$form->getExpandFilter())) {
            $form->expandFilter();
        }

        return $form;
    }
}
