<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Menu\Entities\Menu;
use VCComponent\Laravel\Menu\Entities\ItemMenu;


$factory->define(Menu::class, function (Faker $faker) {
    return [
        'name'       => $faker->words(rand(1,1), true),
        'status'      => 1,
        'position' => $faker->words(rand(1,1), true),
        'page' => $faker->words(rand(1,1), true),

    ];
});
$factory->define(ItemMenu::class, function (Faker $faker) {
    return [
        'menu_id' => 1,
        'label'       => $faker->words(rand(1,5), true),
        'link' => $faker->words(rand(1,1), true),
        'order_by' => 1,
        'type' => 'menus',
        'parent_id' => 0
    ];
});




