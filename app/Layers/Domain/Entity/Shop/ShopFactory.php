<?php

namespace App\Layers\Domain\Entity\Shop;

use App\Layers\Domain\ValueObject\Image\StoreImage;
use App\Layers\Presentation\Requests\Shop\Shop\UpdateRequest;

class ShopFactory
{
    /**
     * @param $request
     * @return ShopEntity
     */
    public function makeNew($request): ShopEntity
    {
        return ShopEntity::make(
            id: null,
            name: $request->store_name,
            category_id: $request->store_category_id,
            description: $request->store_description ?? null,
            logo: StoreImage::make(
                file: $request->file('store_logo'),
            ),
            postal_code: $request->postal_code ?? null,
            address: $request->address ?? null,
        );
    }

    /**
     * @param $model
     * @return ShopEntity
     */
    public function makeByModel($model): ShopEntity
    {
        return ShopEntity::make(
            id: $model->id,
            name: $model->name,
            category_id: $model->category_id,
            description: $model->description,
            logo: StoreImage::make(
                url: $model->logo,
            ),
            postal_code: $model->postal_code,
            address: $model->address,
            created_at: $model->created_at,
            updated_at: $model->updated_at,
            deleted_at: $model->deleted_at,
        );
    }

    /**
     * @param ShopEntity $store_entity
     * @param UpdateRequest $request
     * @return ShopEntity
     */
    public function makeUpdate(ShopEntity $store_entity, UpdateRequest $request): ShopEntity
    {
        return ShopEntity::make(
            id: $store_entity->getId(),
            name: $request->input('name'),
            category_id: $request->input('category_id'),
            description: $request->input('description') ?? null,
            logo: StoreImage::make(
                file: $request->file('logo'),
                url: $store_entity->getLogo()->getCurrentUrl(),
            ),
            postal_code: $request->input('postal_code') ?? null,
            address: $request->input('address') ?? null,
        );
    }
}
