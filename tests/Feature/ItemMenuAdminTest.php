<?php

namespace VCComponent\Laravel\Menu\Test\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Menu\Entities\ItemMenu;
use VCComponent\Laravel\Menu\Entities\Menu;
use VCComponent\Laravel\Menu\Test\TestCase;

class ItemMenuAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_get_all_item_menu_admin()
    {
        $token = $this->loginToken();
        $listItemMenus = [];
        for ($i = 0; $i < 5; $i++) {
            $menu = factory(ItemMenu::class)->create()->toArray();
            unset($menu['updated_at']);
            unset($menu['created_at']);
            array_push($listItemMenus, $menu);
        }
        $listIds = array_column($listItemMenus, 'id');
        array_multisort($listIds, SORT_DESC, $listItemMenus);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/admin/item-menus/list');

        $response->assertStatus(200);

        $response->assertJson($listItemMenus);

    }
    /**
     * @test
     */
    public function should_get_all_item_menu_with_paginate_admin()
    {
        $token = $this->loginToken();
        $listItemMenus = [];
        for ($i = 0; $i < 5; $i++) {
            $menu = factory(ItemMenu::class)->create()->toArray();
            unset($menu['updated_at']);
            unset($menu['created_at']);
            array_push($listItemMenus, $menu);
        }
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/admin/item-menus');
        $response->assertStatus(200);
        $listIds = array_column($listItemMenus, 'id');
        array_multisort($listIds, SORT_DESC, $listItemMenus);
        $response->assertJson(['data' => $listItemMenus]);
        $response->assertJson(['per_page' => 15]);
    }

    /**
     * @test
     */
    public function should_get_item_menu_item_admin()
    {
        $token = $this->loginToken();
        $menu = factory(ItemMenu::class)->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/admin/item-menus/' . $menu->id);
        $response->assertStatus(200);
        $response->assertJson([
            'label' => $menu['label'],
            'link' => $menu['link'],
        ]);
    }

    /**
     * @test
     */
    public function should_create_item_menu_admin()
    {
        $token = $this->loginToken();
        $data = factory(ItemMenu::class)->make(['label' => ''])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/admin/item-menus/', $data);
        $this->assertValidation($response, 500, 'label', 'The label field is required.');
        $data = factory(ItemMenu::class)->make()->toArray();
        unset($data['updated_at']);
        unset($data['created_at']);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/admin/item-menus/', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'label' => $data['label'],
            'link' => $data['link'],

        ]);

        $this->assertDatabaseHas('item_menus', $data);
    }

    /**
     * @test
     */
    public function should_delete_menu_admin()
    {
        $token = $this->loginToken();
        $itemMenu = factory(ItemMenu::class)->create()->toArray();
        unset($itemMenu['updated_at']);
        unset($itemMenu['created_at']);

        $this->assertDatabaseHas('item_menus', $itemMenu);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('DELETE', 'api/admin/item-menus/' . $itemMenu['id']);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDeleted('item_menus', $itemMenu);
    }

    /**
     * @test
     */
    public function should_update_menu_admin()
    {
        $itemMenu = factory(ItemMenu::class)->create();
        $token = $this->loginToken();
        unset($itemMenu['updated_at']);
        unset($itemMenu['created_at']);

        $id = $itemMenu->id;
        $itemMenu->link = '';
        $data = $itemMenu->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/admin/item-menus/' . $id, $data);
        $this->assertValidation($response, 500, 'link', 'The link field is required.');

        $itemMenu->link = 'update';
        $data = $itemMenu->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/admin/item-menus/' . $id, $data);
        $response->assertStatus(200);
        $response->assertJson([
            'link' => $data['link'],
        ]);
        $this->assertDatabaseHas('item_menus', $data);
    }
}
