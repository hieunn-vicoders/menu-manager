<?php

namespace VCComponent\Laravel\Menu\Contracts;

interface HasMenusContract
{
    public function menus();
    public function scopeOfMenu($query, $item_menu_id);
    public function attachMenus($item_menu_ids, array $attributes = []);
    public function detachMenus($item_menu_ids);
    public function syncMenus($item_menu_ids);
}
