<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Model;


use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Cache\Listener\DeleteListenerEvent;
use Hyperf\Database\Model\Events\Saved;
use Hyperf\DbConnection\Db;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @property int $id 
 * @property string $key 
 * @property string $value 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AdminSite extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'vDb::beginTransaction();
    try{
    
        // Do something...
    
        Db::commit();
    } catch(\Throwable $ex){
        Db::rollBack();
    }alue'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    protected function init()
    {
        $this->setTable(config('admin.database.site_table'));
    }

    public static $colorScheme = [
        'skin-black'        => 'skin-black',
        'skin-black-light'  => 'skin-black-light',
        'skin-blue'         => 'skin-blue',
        'skin-blue-light'   => 'skin-blue-light',
        'skin-green'        => 'skin-green',
        'skin-green-light'  => 'skin-green-light',
        'skin-purple'       => 'skin-purple',
        'skin-purple-light' => 'skin-purple-light',
        'skin-red'          => 'skin-red',
        'skin-red-light'    => 'skin-red-light',
        'skin-yellow'       => 'skin-yellow',
        'skin-yellow-light' => 'skin-yellow-light',
    ];

    public static $animationType = [
        "bounce"            => "bounce",
        "flash"             => "flash",
        "pulse"             => "pulse",
        "rubberBand"        => "rubberBand",
        "shake"             => "shake",
        "swing"             => "swing",
        "tada"              => "tada",
        "wobble"            => "wobble",
        "jello"             => "jello",
        "heartBeat"         => "heartBeat",
        "bounceIn"          => "bounceIn",
        "bounceInDown"      => "bounceInDown",
        "bounceInLeft"      => "bounceInLeft",
        "bounceInRight"     => "bounceInRight",
        "bounceInUp"        => "bounceInUp",
        "fadeIn"            => "fadeIn",
        "fadeInDown"        => "fadeInDown",
        "fadeInDownBig"     => "fadeInDownBig",
        "fadeInLeft"        => "fadeInLeft",
        "fadeInLeftBig"     => "fadeInLeftBig",
        "fadeInRight"       => "fadeInRight",
        "fadeInRightBig"    => "fadeInRightBig",
        "fadeInUp"          => "fadeInUp",
        "fadeInUpBig"       => "fadeInUpBig",
        "flip"              => "flip",
        "flipInX"           => "flipInX",
        "flipInY"           => "flipInY",
        "lightSpeedIn"      => "lightSpeedIn",
        "rotateIn"          => "rotateIn",
        "rotateInDownLeft"  => "rotateInDownLeft",
        "rotateInDownRight" => "rotateInDownRight",
        "rotateInUpLeft"    => "rotateInUpLeft",
        "rotateInUpRight"   => "rotateInUpRight",
        "slideInUp"         => "slideInUp",
        "slideInDown"       => "slideInDown",
        "slideInLeft"       => "slideInLeft",
        "slideInRight"      => "slideInRight",
        // "slideOutRight"     => "slideOutRight",
        "zoomIn"            => "zoomIn",
        "zoomInDown"        => "zoomInDown",
        "zoomInLeft"        => "zoomInLeft",
        "zoomInRight"       => "zoomInRight",
        "zoomInUp"          => "zoomInUp",
        // "hinge"             => "hinge",
        "jackInTheBox"      => "jackInTheBox",
        "rollIn"            => "rollIn",
    ];

    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @Cacheable(prefix="ha:admin_site", ttl=604800, listener="admin_site_list")
     *
     * @return array
     */
    public function getAll(): array
    {
        return self::query()->pluck('value', 'key')->toArray();
    }

    /**
     * Flush cache
     */
    public function flushCache()
    {
        $this->dispatcher->dispatch(new DeleteListenerEvent("admin_site_list", []));
    }

    /**
     * Flush cache after update database
     */
    public function saved(Saved $event)
    {
        $this->flushCache();
    }

    /**
     * Save the model to the database.
     * 
     * @param array $data
     *
     * @return bool
     */
    public function saveData(array $data): bool
    {
        Db::beginTransaction();

        try{
            $oldData = $this->getAll();
            foreach ($data as $key => $value) {
                if (isset($oldData[$key]) && $oldData[$key] != $value) {
                    $this->query()->where('key', $key)->update(['value' => $value]);
                }
            }

            Db::commit();
            $this->flushCache();
        } catch(\Throwable $th){
            Db::rollBack();

            return false;
        }

        return true;
    }
}