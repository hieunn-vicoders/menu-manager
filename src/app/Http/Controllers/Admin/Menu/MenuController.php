<?php

namespace VCComponent\Laravel\Menu\Http\Controllers\Admin\Menu;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use VCComponent\Laravel\Menu\Entities\ItemMenu;
use VCComponent\Laravel\Menu\Entities\Menu;
use VCComponent\Laravel\Menu\Http\Controllers\ApiController;
use VCComponent\Laravel\Menu\Repositories\MenuRepository;
use VCComponent\Laravel\Menu\Transformers\MenuTransformer;
use VCComponent\Laravel\Menu\Validators\ItemMenuValidator;
use VCComponent\Laravel\Menu\Validators\MenuValidator;

class MenuController extends ApiController
{

    private $repository;
    private $validator;

    public function __construct(MenuRepository $repository, MenuValidator $validator, ItemMenuValidator $ItemMenuValidator)
    {
        $this->repository        = $repository;
        $this->validator         = $validator;
        $this->ItemMenuValidator = $ItemMenuValidator;

        if (!empty(config('menu.auth_middleware.admin'))) {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToUse($user)) {
                throw new \Exception('Permission denied !');
            }

            foreach(config('menu.auth_middleware.admin') as $middleware){
                $this->middleware($middleware['middleware'], ['except' => $middleware['except']]);
            }
        }

    }

    public function index(Request $request)
    {
        $query = new Menu;

        $constraints = (array) json_decode($request->get('constraints'));
        if (count($constraints)) {
            $query = $query->where($constraints);
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query  = $query->where(function ($q) use ($request, $search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        if ($request->has('order_by')) {
            $orderBy = (array) json_decode($request->get('order_by'));
            if (count($orderBy) > 0) {
                foreach ($orderBy as $key => $value) {
                    $query = $query->orderBy($key, $value);
                }
            }
        } else {
            $query = $query->orderBy('updated_at', 'desc');
        }

        $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $menu     = $query->paginate($per_page);

        $transformer = new MenuTransformer;

        return $this->response->paginator($menu, $transformer);
    }

    public function store(Request $request)
    {
        if ($request->has('name')) {
            $menu       = new Menu;
            $menu->name = $request->get('name');
            $menu->save();
            return $this->response->item($menu, new MenuTransformer);
        } else {
            throw new NotFoundHttpException('name not found');
        }
    }

    public function show($id, Request $request)
    {

        $menu = Menu::find($id);

        if (!$menu) {
            throw new BadRequestHttpException('Menu not found');
        }

        return $this->response->item($menu, new MenuTransformer(['menus']));
    }

    public function additems(Request $request, $id)
    {
        $menu  = $this->repository->find($id);
        $datas = $request->all();

        foreach ($datas as $data) {
            $this->ItemMenuValidator->isValid($data, 'RULE_CREATE');
            $item = new ItemMenu($data);

            $menu->menus()->save($item);
        }

        $menu = $this->repository->find($id);
        return $this->response->item($menu, new MenuTransformer(['menus']));
    }

    public function addItem(Request $request, $id)
    {
        $this->ItemMenuValidator->isValid($request, 'RULE_CREATE');

        $data = $request->all();
        $item = new ItemMenu($data);
        $menu = $this->repository->find($id);
        $menu->menus()->save($item);
        $menu = $this->repository->find($id);
        return $this->response->item($menu, new MenuTransformer(['menus']));
    }

    public function UpdateSubmenu($items, $parent)
    {
        foreach ($items['menus'] as $key => $item) {
            $menu_item            = ItemMenu::find($item['id']);
            $menu_item->label     = $item['label'];
            $menu_item->link      = $item['link'];
            $menu_item->order_by  = $key;
            $menu_item->parent_id = $parent;
            $menu_item->save();
            if (!empty($item['menus'])) {
                $this->UpdateSubmenu($item, $menu_item->id);
            }
        }
    }

    public function UpdateMenuItem(Request $request)
    {
        if ($request->has('menus')) {
            $menu_id                = $request->get('menus')['id'];
            $item_menus_current_ids = ItemMenu::where('menu_id', $menu_id)->get()->pluck('id');
            $menu_ids               = [];
            $menu_ids               = $this->get_menu_ids($request->get('menus')['menus'], $menu_ids);
            $menu_ids               = collect($menu_ids);
            $diff_ids               = $item_menus_current_ids->diff($menu_ids);

            if ($diff_ids->isNotEmpty()) {
                ItemMenu::destroy($diff_ids);
            }

            foreach ($request->get('menus')['menus'] as $key => $item) {
                $menu_item            = ItemMenu::find($item['id']);
                $menu_item->label     = $item['label'];
                $menu_item->link      = $item['link'];
                $menu_item->order_by  = $key;
                $menu_item->parent_id = 0;
                $menu_item->save();

                if (!empty($item['menus'])) {
                    $this->UpdateSubmenu($item, $menu_item->id);
                }
            }

            $item = Menu::find($request->get('menus')['id']);
            return response()->json($item);
        } else {
            throw new NotFoundHttpException('menus not found');
        }
    }

    public function get_menu_ids($menus, &$menu_ids)
    {
        if (!empty($menus)) {
            foreach ($menus as $value) {
                if ($value['id'] != null) {
                    $menu_ids[] = $value['id'];
                }
                if (isset($value['menus'])) {
                    $menu_ids = $this->get_menu_ids($value['menus'], $menu_ids);
                }
            }
        }
        return $menu_ids;
    }

    public function update(Request $request, $id)
    {
        $this->validator->isValid($request, 'RULE_UPDATE');
        $attributes  = $request->all();
        $menu_update = $this->repository->update($attributes, $id);

        return $this->response->item($menu_update, new MenuTransformer);
    }

    public function delete($id)
    {
        $this->repository->delete($id);
        return $this->success();
    }
}
