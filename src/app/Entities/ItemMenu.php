<?php

namespace VCComponent\Laravel\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use VCComponent\Laravel\Menu\Entities\Menu;

class ItemMenu extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table    = 'item_menus';
    protected $fillable = [
        'menu_id',
        'label',
        'link',
        'type',
        'parent_id',
        'order_by',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class)->orderBy('order_by', 'desc');
    }

    public function menus()
    {
        return $this->hasMany(ItemMenu::class, 'parent_id')->orderBy('order_by', 'desc');
    }

    public function subMenus()
    {
        return $this->hasMany(ItemMenu::class, 'parent_id')->orderBy('order_by', 'desc')->with('subMenus:label,link,id');
    }

    public function renderSubmenu()
    {

        $item_id      = $this->id;
        $item_menu_id = $this->menu_id;
        $getSub       = $this->subMenus;
        $html         = '';

        if ($getSub->isNotEmpty()) {
            $html = '<ul class="sub-menu dropdown-menu  sub-menu-' . $item_id . '    ">';

            foreach ($getSub as $item) {
                $html .= '<li id="item-menus-' . $item->id . '" class ="item dropdown-item">';
                $html .= '<a href="' . $item->link . '">';
                $html .= $item->label;
                $html .= '</a>';
                $html .= $item->renderSubmenu();
                $html .= '</li>';
            }
            $html .= '</ul>';
            return $html;
        }
    }
}
