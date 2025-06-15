<?php

namespace App\Layers\Infrastructure\Repository;

use App\Layers\Domain\Entity\Category\CategoryEntity;
use App\Layers\Domain\Entity\Customer\CustomerEntity;
use App\Layers\Presentation\Requests\Customer\Category\ListRequest as CustomerListRequest;
use App\Layers\Presentation\Requests\Shop\Category\ListRequest;
use App\Models\Menu\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{
    /**
     * @param int $id
     * @return Category|null
     */
    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    /**
     * @param ListRequest $request
     * @param $store_id
     * @return mixed
     */
    public function get($request, $store_id)
    {
        return Category::where('store_id', $store_id)
            ->orderBy('id')
            //->orderBy('order')
            ->limit($request->input('limit'))
            ->offset($request->input('offset'))
            ->get();
    }

    /**
     * @param int $store_id
     * @return int
     */
    public function getTotal(int $store_id): int
    {
        return Category::where('store_id', $store_id)->count();
    }

    /**
     * @param CategoryEntity $entity
     * @param int $order
     * @return bool
     */
    public function existsOrder(CategoryEntity $entity, int $order): bool
    {
        $query = Category::where('store_id', $entity->getStoreId())
            ->where('order', $order);

        if (!is_null($entity->getId())) {
            // 更新時は自分自身以外のレコードが存在するかを確認する
            $query->where('id', '!=', $entity->getId());
        }

        return $query->exists();
    }

    /**
     * @param CategoryEntity $category_entity
     * @return Category
     */
    public function create(CategoryEntity $category_entity): Category
    {
        return Category::create([
            'store_id' => $category_entity->getStoreId(),
            'name' => $category_entity->getName(),
            'order' => $category_entity->getOrder(),
        ]);
    }

    /**
     * @param CategoryEntity $category_entity
     * @return void
     */
    public function update(CategoryEntity $category_entity): void
    {
        Category::where('id', $category_entity->getId())
            ->update([
                'name' => $category_entity->getName(),
                'order' => $category_entity->getOrder(),
            ]);
    }

    /**
     * @param CategoryEntity $entity
     * @return void
     */
    public function delete(CategoryEntity $entity): void
    {
        Category::where('id', $entity->getId())->delete();
    }

    /**
     * @param CustomerEntity $customer_entity
     * @param CustomerListRequest $request
     * @return Collection
     */
    public function getForCustomer(
        CustomerEntity      $customer_entity,
        CustomerListRequest $request,
    ): Collection {
        return Category::query()
            ->where('store_id', $customer_entity->getStoreId())
            ->limit($request->input('limit'))
            ->offset($request->input('offset'))
            ->get();
    }
}
