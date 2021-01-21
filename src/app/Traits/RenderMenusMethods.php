<?php

namespace VCComponent\Laravel\Menu\Traits;

use VCComponent\Laravel\Menu\Entities\Menu;
use Illuminate\Support\Facades\Cache;

trait RenderMenusMethods
{

    public function render($page, $position, $menu_type)
    {
        $menu_horizontal = Menu::MENU_HORIZONTAL;
        $main_menu = $this->where(['status' => 1, 'page' => $page, 'position' => $position])->with('menuItems')->latest()->first();
        echo view('render_menu::menu.render_menu', ['main_menu' => $main_menu, 'menu_type' => $menu_type, 'menu_horizontal' => $menu_horizontal]);
    }
}
