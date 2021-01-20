<?php

namespace VCComponent\Laravel\Menu\Transformers;

use League\Fractal\TransformerAbstract;
use VCComponent\Laravel\Menu\Entities\Menu;

class MenuTransformer extends TransformerAbstract
{
    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform(Menu $model)
    {
        return [
            'id'         => (int) $model->id,
            'name'       => $model->name,
            'status'     => $model->status,
            'page'       => $model->page,
            'position'   => $model->position,
            'page_vn'     => $this->translatePage($model->page),
            'position_vn' => $this->translatePosition($model->page, $model->position),
            'timestamps' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }

    public function includeMenus($model)
    {
        return $this->collection($model->menus, new ItemMenuTransformer);
    }

    public function translatePage($page)
    {
        foreach (config('menu.page') as $key => $value) {
            if ($page === $key) {
                return $value['label'];
            }
        }
    }

    public function translatePosition($page, $position)
    {
        foreach (config('menu.page') as $key => $value) {
            if ($page === $key) {
                foreach ($value['position'] as $key => $value) {
                    if ($position === $key) {
                        return $value;
                    }
                }
            }
        }
    }
}
