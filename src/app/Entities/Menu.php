<?php

namespace VCComponent\Laravel\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use VCComponent\Laravel\Menu\Entities\ItemMenu;
use VCComponent\Laravel\Menu\Traits\RenderMenusMethods;

class Menu extends Model implements Transformable
{
    use TransformableTrait, RenderMenusMethods; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'page',
        'position',
        'status',
    ];

    public function itemMenu()
    {
        return $this->hasMany(ItemMenu::class, 'menu_name');
    }

    public function menus()
    {
        return $this->hasMany(ItemMenu::class)->where('parent_id', 0);
    }

    public function menuItems()
    {
        return $this->hasMany(ItemMenu::class)->where('parent_id', 0)->with('subMenus')->orderBy('order_by', 'ASC');
    }

    public function ableToUse($user)
    {
        return true;
    }
}
