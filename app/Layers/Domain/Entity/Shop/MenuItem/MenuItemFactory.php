<?php

namespace App\Layers\Domain\Entity\Shop\MenuItem;

use App\Exceptions\DomainException;
use App\Layers\Domain\ValueObject\Image\MenuItemImage;
use App\Layers\Domain\ValueObject\MenuItemStatus;
use App\Layers\Infrastructure\Repository\CategoryRepository;
use App\Layers\Presentation\Requests\Shop\MenuItem\UpdateRequest;
use App\Models\Menu\MenuItem;
use Illuminate\Support\Collection;

class MenuItemFactory
{
    /**
     * @param CategoryRepository $category_repository
     */
    public function __construct(private readonly CategoryRepository $category_repository)
    {
    }

    /**
     * @param $request
     * @param $store_id
     * @return MenuItemEntity
     * @throws DomainException
     */
    public function makeNew($request, $store_id) : MenuItemEntity
    {
        $category = $this->category_repository->find($request->input('category_id'));

        if (is_null($category)) {
            throw new DomainException(['指定されたカテゴリーが登録されていません。']);
        }

        return MenuItemEntity::make(
            id: null,
            store_id: $store_id,
            number: $request->input('number'),
            name: $request->input('name'),
            price: $request->input('price'),
            description: $request->input('description'),
            image: MenuItemImage::make(
                file: $request->file('image'),
            ),
            category: MenuCategoryEntity::make(
                id: $category->id,
                name: $category->name,
            ),
            status: MenuItemStatus::make(
                value: $request->input('status'),
            ),
            option_list: collect($request->input('options'))->map(function ($item) {
                return MenuItemOptionEntity::make(
                    id: null,
                    name: $item['name'],
                    option_value_list: collect($item['values'])->map(function ($item) {
                        return MenuItemOptionValueEntity::make(
                            id: null,
                            order: $item['order'],
                            name: $item['name'],
                            cost: $item['cost'],
                        );
                    }),
                );
            }),
            created_at: null,
            updated_at: null,
        );
    }

    /**
     * @param Menuitem $model
     * @return MenuItemEntity
     * @throws DomainException
     */
    public function makeByModel($model) : MenuItemEntity
    {
        return MenuItemEntity::make(
            id: $model->id,
            store_id: $model->store_id,
            number: $model->number,
            name: $model->name,
            price: $model->price,
            description: $model->description,
            image: MenuItemImage::make(
                url: $model->image,
            ),
            category: MenuCategoryEntity::make(
                id: $model->category->id,
                name: $model->category->name,
            ),
            status: MenuItemStatus::make(
                value: $model->status,
            ),
            option_list: $model->menuitemOptions->map(function ($item) {
                return MenuItemOptionEntity::make(
                    id: $item->id,
                    name: $item->name,
                    option_value_list: $item->menuitemOptionValues->map(function ($item) {
                        return MenuItemOptionValueEntity::make(
                            id: $item->id,
                            order: $item->order,
                            name: $item->value,
                            cost: $item->cost,
                        );
                    }),
                );
            }),
            created_at: $model->created_at,
            updated_at: $model->updated_at,
        );
    }

    /**
     * @param MenuItem $model
     * @param UpdateRequest $request
     * @return MenuItemEntity
     * @throws DomainException
     */
    public function makeByModelAndRequest(
        Menuitem $model,
        UpdateRequest $request
    ) : MenuItemEntity {
        $category = $this->category_repository->find($request->input('category_id'));

        if (is_null($category)) {
            throw new DomainException(['指定されたカテゴリーが登録されていません。']);
        }

        return MenuItemEntity::make(
            id: $model->id,
            store_id: $model->store_id,
            number: $request->input('number'),
            name: $request->input('name'),
            price: $request->input('price'),
            description: $request->input('description'),
            image: MenuItemImage::make(
                file: $request->file('image'),
                url: $model->image,
            ),
            category: MenuCategoryEntity::make(
                id: $category->id,
                name: $category->name,
            ),
            status: MenuItemStatus::make(
                value: $request->input('status'),
            ),
            option_list: collect($request->input('options'))->map(function ($item) {
                return MenuItemOptionEntity::make(
                    id: null,
                    name: $item['name'],
                    option_value_list: collect($item['values'])->map(function ($item) {
                        return MenuItemOptionValueEntity::make(
                            id: null,
                            order: $item['order'],
                            name: $item['name'],
                            cost: $item['cost'],
                        );
                    }),
                );
            }),
            created_at: $model->created_at,
            updated_at: $model->updated_at,
        );
    }

    /**
     * @param $db_collection
     * @return Collection
     * @throws DomainException
     */
    public function makeListFromDbCollection($db_collection) : Collection
    {
        return $db_collection->map(function ($item) {
            return $this->makeByModel($item);
        });
    }
}
