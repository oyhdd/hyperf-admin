<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Model;

use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Di\Annotation\Inject;
use Psr\EventDispatcher\EventDispatcherInterface;
use Hyperf\Cache\Listener\DeleteListenerEvent;
use Hyperf\Database\Model\Events\Saved;
use Hyperf\Database\Model\Relations\BelongsToMany;

/**
 * @property int $id 
 * @property int $parent_id 
 * @property int $order 
 * @property string $title 
 * @property string $icon 
 * @property string $uri 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AdminMenu extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'order', 'title', 'icon', 'uri'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'parent_id' => 'integer', 'order' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    protected function init()
    {
        $this->setTable(config('admin.database.menu_table'));
    }

    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    
    /**
     * A Menu belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        $relatedModel = config('admin.database.role_model');
        $pivotTable = config('admin.database.role_menu_table');

        return $this->belongsToMany($relatedModel, $pivotTable, 'menu_id', 'role_id')->withTimestamps();
    }

    /**
     * Get the menu list
     *
     * @param  string   $uri
     * @return array
     */
    public function getMenuTree(string $uri = ''): array
    {
        $uri = $uri ?: '/';
        $user = admin_user();
        $tree = [];
        $items = $this->getAll();
        if (empty($items)) {
            return $tree;
        }

        $roleIds = [];
        if (! $isAdmin = $user->isAdministrator()) {
            $roleIds = $user->roles()->pluck('id')->toArray();
        }

        $items = array_column($items, null, 'id');
        foreach ($items as $id => $item) {
            if (!$isAdmin && !empty($item['roles'])) {
                $menuRoleIds = array_column($item['roles'], 'id');
                if (empty(array_intersect($menuRoleIds, $roleIds))) {
                    continue;
                }
            }

            unset($items[$id]['roles']);

            if (!isset($items[$id]['active'])) {
                $items[$id]['active'] = false;
            }
            if ($uri === $item['uri']) {
                $items[$id]['active'] = true;
            }
            if (isset($items[$item['parent_id']])) {
                if ($items[$id]['active']) {
                    $items[$item['parent_id']]['active'] = true;
                }
                $items[$item['parent_id']]['children'][] = &$items[$id];
            } elseif ($item['parent_id'] == 0) {
                $tree[] = &$items[$id];
            }
        }

        return $tree;
    }

    /**
     * @Cacheable(prefix="ha:admin_menu", ttl=604800, listener="admin_menu_list")
     *
     * @return array
     */
    public function getAll(): array
    {
        return self::query()->with('roles')->orderBy('order')->get()->toArray();
    }

    /**
     * Flush cache of the Menu List
     */
    public function flushCache()
    {
        $this->dispatcher->dispatch(new DeleteListenerEvent("admin_menu_list", []));
    }

    /**
     * Flush cache after update database
     */
    public function saved(Saved $event)
    {
        $this->flushCache();
    }
}