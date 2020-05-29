<?php declare (strict_types=1);

namespace Oyhdd\Admin\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\Utils\Str;

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
}
