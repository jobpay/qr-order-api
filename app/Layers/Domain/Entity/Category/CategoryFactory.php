<?php

namespace App\Layers\Domain\Entity\Category;

use App\Layers\Presentation\Requests\Shop\Category\StoreRequest;
use App\Layers\Presentation\Requests\Shop\Category\UpdateRequest;
use Illuminate\Support\Collection;

class CategoryFactory
{
    /**
     * @param StoreRequest $request
     * @param int $store_id
     * @return CategoryEntity
     */
    public function makeNew(StoreRequest $request, int $store_id) : CategoryEntity
    {
        return CategoryEntity::make(
            id: null,
            store_id: $store_id,
            name: $request->name,
            order: $request->order,
            created_at: null,
            updated_at: null,
        );
    }

    /**
     * @param $model
     * @return CategoryEntity
     */
    public function makeByModel($model) : CategoryEntity
    {
        return CategoryEntity::make(
            id: $model->id,
            store_id: $model->store_id,
            name: $model->name,
            order: $model->order,
            created_at: $model->created_at,
            updated_at: $model->updated_at,
        );
    }

    /**
     * @param CategoryEntity $entity
     * @param UpdateRequest $request
     * @return CategoryEntity
     */
    public function makeUpdate(
        CategoryEntity $entity,
        UpdateRequest $request
    ) : CategoryEntity {
        return CategoryEntity::make(
            id: $entity->getId(),
            store_id: $entity->getStoreId(),
            name: $request->input('name', $entity->getName()),
            order: $request->input('order', $entity->getOrder()),
            created_at: $entity->getCreatedAt(),
            updated_at: $entity->getUpdatedAt(),
        );
    }

    /**
     * @param $db_collection
     * @return Collection
     */
    public function makeListFromDbCollection($db_collection) : Collection
    {
        return $db_collection->map(function ($item) {
            return $this->makeByModel($item);
        });
    }
}
