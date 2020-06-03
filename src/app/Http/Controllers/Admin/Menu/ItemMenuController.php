<?php

namespace VCComponent\Laravel\Menu\Http\Controllers\Admin\Menu;

use Illuminate\Http\Request;
use VCComponent\Laravel\Menu\Repositories\ItemMenuRepository;
use VCComponent\Laravel\Menu\Transformers\ItemMenuTransformer;
use VCComponent\Laravel\Menu\Validators\ItemMenuValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class ItemMenuController extends ApiController
{
    protected $repository;
    protected $validator;

    public function __construct(ItemMenuRepository $repository, ItemMenuValidator $validator)
    {
        $this->repository  = $repository;
        $this->entity      = $repository->getEntity();
        $this->validator   = $validator;
        $this->transformer = ItemMenuTransformer::class;
    }

    public function index(Request $request)
    {

        $query = $this->entity;

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['label'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $items    = $query->paginate($per_page);

        return $this->response->paginator($items, new $this->transformer());
    }

    public function store(Request $request)
    {
        $this->validator->isValid($request, 'RULE_CREATE');

        $data = $request->all();
        $item = $this->repository->create($data);
        $item->save();
        return $this->response->item($item, new $this->transformer());
    }

    public function show($id)
    {
        $item = $this->repository->find($id);

        return $this->response->item($item, new $this->transformer());
    }

    public function update(Request $request, $id)
    {
        $this->validator->isValid($request, 'RULE_UPDATE');
        $data = $request->all();
        $item = $this->repository->update($data, $id);

        return $this->response->item($item, new $this->transformer());
    }
    public function destroy($id)
    {
        $this->repository->delete($id);
        return $this->success();
    }

    public function getList(Request $request)
    {
        $query = $this->entity;

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['label'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $items = $query->get();

        return $this->response->collection($items, new $this->transformer());
    }
}
