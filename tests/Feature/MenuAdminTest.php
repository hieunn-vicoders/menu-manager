<?php

namespace VCComponent\Laravel\Menu\Test\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Menu\Test\TestCase;
use VCComponent\Laravel\Menu\Entities\Menu;
use VCComponent\Laravel\Menu\Entities\ItemMenu;

class MenuAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_get_all_menus_with_paginate_admin()
    {
        $listMenus = [];
        for ($i = 0; $i < 5; $i++) {
            $menu = factory(Menu::class)->create()->toArray();
            unset($menu['updated_at']);
            unset($menu['created_at']);
            array_push($listMenus, $menu);
        }
        $response = $this->call('GET', 'api/admin/menus');
        $response->assertStatus(200);
        $response->assertJson(['data' => $listMenus]);
        $response->assertJsonStructure([
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function should_get_page_list_admin()
    {
        $check = [
            [
                'header' => "header"
            ],
            [
                'footer' => "footer"
            ]
        ];
        $response = $this->call('GET', 'api/admin/menus/get-page-list');
        foreach ($check as $item) {
            $response->assertJsonFragment($item);
        }
        $response->assertStatus(200);

    }
    /**
     * @test
     */
    public function should_get_position_page_list_admin()
    {
        $check = [
            [
                'position-1' => "Vi tri 1"
            ],
            [
                'position-2' => "Vi tri 2"
            ]
        ];
        $response = $this->call('GET', 'api/admin/menus/get-position-list/header');
        $response->assertStatus(200);
        foreach ($check as $item) {
            $response->assertJsonFragment($item);
        }
    }

    /**
     * @test
     */
    public function should_get_menu_item_admin()
    {
        $menu = factory(Menu::class)->create();

        $response = $this->call('GET', 'api/admin/menus/' . $menu->id);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name' => $menu->name,
                'position' => $menu->position,
                'page' => $menu->page,
            ],
        ]);
    }

    /**
     * @test
     */
    public function should_create_menu_by_admin()
    {
        $data = factory(Menu::class)->make()->toArray();

        unset($data['updated_at']);
        unset($data['created_at']);

        $response = $this->json('POST', 'api/admin/menus', $data);
        $response->assertStatus(200);
        $response->assertJson(['data' => [
            'name' => $data['name'],
            'position' => $data['position'],
            'page' => $data['page'],
        ],
        ]);

        $this->assertDatabaseHas('menus', $data);
    }

    /**
     * @test
     */
    public function should_delete_menu_admin()
    {
        $menu = factory(Menu::class)->create()->toArray();
        unset($menu['updated_at']);
        unset($menu['created_at']);

        $this->assertDatabaseHas('menus', $menu);

        $response = $this->call('DELETE', 'api/admin/menus/' . $menu['id']);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDeleted('menus', $menu);
    }

    /**
     * @test
     */
    public function should_create_item_menu_admin()
    {
        $menu = factory(Menu::class)->create();
        $data_fake = factory(ItemMenu::class)->make(['menu_id' => $menu->id, 'label' => ''])->toArray();
        $response = $this->json('POST', 'api/admin/menus/' . $menu->id . '/item', $data_fake);
        $this->assertValidation($response, 500, 'label', 'The label field is required.');

        $data = factory(ItemMenu::class)->make(['menu_id' => $menu->id])->toArray();
        unset($data['updated_at']);
        unset($data['created_at']);
        $response = $this->json('POST', 'api/admin/menus/' . $menu->id . '/item', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('item_menus', $data);
    }

    /**
     * @test
     */
    public function should_create_items_menu_admin()
    {
        $menu = factory(Menu::class)->create();
        $listData = factory(ItemMenu::class,2)->make(['menu_id' => $menu->id])->toArray();
        unset($listData['updated_at']);
        unset($listData['created_at']);
        $response = $this->json('POST', 'api/admin/menus/' . $menu->id . '/items', $listData);
        $response->assertStatus(200);
        foreach ($listData as $data) {
            $this->assertDatabaseHas('item_menus', $data);
        }
    }

    /**
     * @test
     */
    public function should_update_menu_admin()
    {
        $menu = factory(Menu::class)->create();

        unset($menu['updated_at']);
        unset($menu['created_at']);
        $id          = $menu->id;
        $menu->name = '';
        $data = $menu->toArray();
        $response = $this->json('PUT', 'api/admin/menus/' . $id, $data);
        $this->assertValidation($response, 500, 'name', 'The name field is required.');

        $menu->name = 'update name';
        $data = $menu->toArray();
        $response = $this->json('PUT', 'api/admin/menus/' . $id, $data);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name' => $data['name'],
            ],
        ]);
        $this->assertDatabaseHas('menus', $data);
    }

    /**
     * @test
     */
    public function should_save_menu_admin()
    {
        $menu = factory(Menu::class)->create()->toArray();
        $item_menu = factory(ItemMenu::class,3)->create(['menu_id' => $menu['id']]);
        unset($menu['updated_at']);
        unset($menu['created_at']);
        unset($item_menu['updated_at']);
        unset($item_menu['created_at']);
        $id = $menu['id'];
        $data = [
            'menus' => [
                'id' => 1,
                'menus' =>
                    [
                        ['id' => $item_menu[0]->id, 'children' => [], 'label' => $item_menu[0]->label, 'link'=> $item_menu[0]->link, 'menus' => []],
                        ['id' => $item_menu[1]->id, 'children' => [
                            'id' => $item_menu[2]->id, 'children' => [], 'label' => $item_menu[2]->label, 'link'=> $item_menu[2]->link, 'menus' => []
                        ], 'label' => $item_menu[1]->label, 'link'=> $item_menu[1]->link, 'menus' => []]

                    ]
            ]
        ];
        $response = $this->json('POST', 'api/admin/menus/'. $id .'/save', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('menus', $menu);
    }
}
