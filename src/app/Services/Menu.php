<?php

namespace VCComponent\Laravel\Menu\Services;

use Illuminate\Support\Facades\Cache;
use VCComponent\Laravel\Menu\Entities\Menu as Entity;

class Menu
{
    protected $cache        = false;
    protected $cacheMinutes = 60;

    public function __construct()
    {
        if (config('menu.cache')['enabled'] === true) {
            $this->cache     = true;
            $this->timeCache = config('menu.cache')['minutes'] ? config('menu.cache')['minutes'] * 60 : $this->cacheMinutes * 60;
        }
    }

    public function getMenu($position)
    {
        $cacheName = "menu " . $position;
        if ($this->cache === true) {
            if (Cache::has('menuManager') && Cache::get('menuManager')->count() !== 0) {
                return Cache::get('menuManager');
            }
            return Cache::remember($cacheName, $this->timeCache, function () use ($position) {
                return Entity::select('id')->where('name', $position)->with('menuItems')->first();
            });
        }
        return Entity::select('id')->where('name', $position)->with('menuItems')->first();
    }
}
