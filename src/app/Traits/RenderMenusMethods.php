<?php
namespace VCComponent\Laravel\Menu\Traits;

use Illuminate\Support\Facades\Cache;
trait RenderMenusMethods
{

    public function RenderMenus($page, $position, $menuType)
    {
        $main_menu = $this->where(['status' => 1, 'page' => $page, 'position' => $position])->with('menuItems')->latest()->first();
        echo view('render_menu::menu.render_menu', ['main_menu' => $main_menu, 'menuType' => $menuType]);
    }
}
