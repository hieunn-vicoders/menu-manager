<?php

namespace VCComponent\Laravel\Menu\Traits;

use VCComponent\Laravel\Menu\Entities\ItemMenu;

trait HasMenusTrait
{
    public function menus()
    {
        return $this->morphToMany(ItemMenu::class, 'item_menuables')->withTimestamps();
    }

    public function scopeOfMenu($query, $item_menu_id)
    {
        return $query->whereHas('menus', function ($q) use ($item_menu_id) {
            $q->where('item_menu_id', $item_menu_id);
        });
    }

    public function attachMenus($item_menu_ids, array $attributes = [])
    {
        $this->menus()->attach($item_menu_ids, $attributes);
    }

    public function detachMenus($item_menu_ids)
    {
        $this->menus()->detach($item_menu_ids);
    }

    public function syncMenus($item_menu_ids)
    {
        $this->menus()->sync($item_menu_ids);
    }
}
