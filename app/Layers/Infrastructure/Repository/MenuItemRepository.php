<?php

namespace App\Layers\Infrastructure\Repository;

use App\Layers\Domain\Entity\Customer\CustomerEntity;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemEntity;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemOptionEntity;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemOptionValueEntity;
use App\Layers\Presentation\Requests\Customer\MenuItem\ListRequest as CustomerListRequest;
use App\Layers\Presentation\Requests\Shop\MenuItem\ListRequest;
use App\Models\Menu\MenuItem;
use App\Models\Menu\MenuItemOption;
use App\Models\Menu\MenuItemOptionValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MenuItemRepository
{
    /**
     * @param ListRequest $request
     * @param int $store_id
     * @return Collection
     */
    public function get(
        ListRequest $request,
        int $store_id
    ): Collection {
        $query = MenuItem::query()
            ->with(['category', 'menuitemOptions'])
            ->where('store_id', $store_id)
            ->limit($request->input('limit'))
            ->offset($request->input('offset'))
            ->orderBy('id');

        if (!is_null($request->input('category_id'))) {
            $query->where('category_id', $request->input('category_id'));
        }

        if (!is_null($request->input('name'))) {
            $query->where('name', $request->input('name'));
        }

        return $query->get();
    }

    /**
     * @param ListRequest $request
     * @param int $store_id
     * @return int
     */
    public function getTotal(
        ListRequest $request,
        int $store_id
    ): int {
        $query = MenuItem::query()
            ->with(['category', 'menuitemOptions'])
            ->where('store_id', $store_id);

        if (!is_null($request->input('category_id'))) {
            $query->where('category_id', $request->input('category_id'));
        }

        if (!is_null($request->input('name'))) {
            $query->where('name', $request->input('name'));
        }

        return $query->count();
    }

    /**
     * @param CustomerEntity $customer_entity
     * @param CustomerListRequest $request
     * @return Collection
     */
    public function getForCustomer(
        CustomerEntity $customer_entity,
        CustomerListRequest $request,
    ): Collection {
        $query = MenuItem::query()
            ->with(['category', 'menuitemOptions'])
            ->where('store_id', $customer_entity->getStoreId())
            ->limit($request->input('limit'))
            ->offset($request->input('offset'));

        if (!is_null($request->input('category_id'))) {
            $query->where('category_id', $request->input('category_id'));
        }

        return $query->get();
    }

    /**
     * @param MenuItemEntity $menu_item_entity
     * @param $image_url
     * @return void
     * @throws \Exception
     */
    public function create(MenuItemEntity $menu_item_entity, $image_url): void
    {
        DB::beginTransaction();

        try {
            // メニューアイテムの登録
            $menu_item = MenuItem::query()->create([
                'store_id' => $menu_item_entity->getStoreId(),
                'number' => $menu_item_entity->getNumber(),
                'name' => $menu_item_entity->getName(),
                'price' => $menu_item_entity->getPrice(),
                'description' => $menu_item_entity->getDescription(),
                'image' => $image_url,
                'category_id' => $menu_item_entity->getCategory()->getId(),
                'status' => $menu_item_entity->getStatus()->getValue(),
                'created_at' => $menu_item_entity->getCreatedAt(),
                'updated_at' => $menu_item_entity->getUpdatedAt(),
            ]);

            // メニューアイテムオプションの登録
            $menu_item_entity->getOptionList()->each(function (MenuItemOptionEntity $option) use ($menu_item) {
                $menu_item_option = MenuItemOption::query()->create([
                    'menu_item_id' => $menu_item->getKey(),
                    'name' => $option->getName(),
                ]);

                // メニューアイテムオプション値の登録
                $option->getOptionValueList()->each(function (MenuItemOptionValueEntity $option_value) use ($menu_item_option) {
                    MenuItemOptionValue::query()->create([
                        'menu_item_option_id' => $menu_item_option->getKey(),
                        'order' => $option_value->getOrder(),
                        'value' => $option_value->getName(),
                        'cost' => $option_value->getCost(),
                    ]);
                });
            });
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @param int $store_id
     * @param int $number
     * @return bool
     */
    public function existsByStoreIdAndNumber(int $store_id, int $number): bool
    {
        return MenuItem::query()
            ->where('store_id', $store_id)
            ->where('number', $number)
            ->exists();
    }

    /**
     * 更新対象以外で、同じ店舗IDとメニュー番号が存在するか
     * @param int $store_id
     * @param int $category_id
     * @param int $number
     * @param int $menu_item_id
     * @return bool
     */
    public function existsByStoreIdAndCategoryIdAndOtherMenuId(int $store_id, int $category_id, int $number, int $menu_item_id): bool
    {
        return MenuItem::query()
            ->where('store_id', $store_id)
            ->where('category_id', $category_id)
            ->where('number', $number)
            ->where('id', '!=', $menu_item_id)
            ->exists();
    }

    /**
     * @param MenuItemEntity $menu_item_entity
     * @param string|null $image_url
     * @return void
     * @throws \Exception
     */
    public function update(MenuItemEntity $menu_item_entity, ?string $image_url): void
    {
        DB::beginTransaction();

        try {
            // メニューアイテムの更新
            MenuItem::query()->where('id', $menu_item_entity->getId())->update([
                'store_id' => $menu_item_entity->getStoreId(),
                'number' => $menu_item_entity->getNumber(),
                'name' => $menu_item_entity->getName(),
                'price' => $menu_item_entity->getPrice(),
                'description' => $menu_item_entity->getDescription(),
                'image' => $image_url,
                'category_id' => $menu_item_entity->getCategory()->getId(),
                'status' => $menu_item_entity->getStatus()->getValue(),
                'updated_at' => $menu_item_entity->getUpdatedAt(),
            ]);

            // メニューアイテムオプションの削除
            MenuItemOption::query()->where('menu_item_id', $menu_item_entity->getId())->delete();

            // メニューアイテムオプション値の削除
            MenuItemOptionValue::query()->whereIn('menu_item_option_id', function ($query) use ($menu_item_entity) {
                $query->select('id')
                    ->from('menu_item_options')
                    ->where('menu_item_id', $menu_item_entity->getId());
            })->delete();

            // メニューアイテムオプションの登録
            $menu_item_entity->getOptionList()->each(function (MenuItemOptionEntity $option) use ($menu_item_entity) {
                $menu_item_option = MenuItemOption::query()->create([
                    'menu_item_id' => $menu_item_entity->getId(),
                    'name' => $option->getName(),
                ]);

                // メニューアイテムオプション値の登録
                $option->getOptionValueList()->each(function (MenuItemOptionValueEntity $option_value) use ($menu_item_option) {
                    MenuItemOptionValue::query()->create([
                        'menu_item_option_id' => $menu_item_option->getKey(),
                        'order' => $option_value->getOrder(),
                        'value' => $option_value->getName(),
                        'cost' => $option_value->getCost(),
                    ]);
                });
            });
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @param MenuItemEntity $menu_item_entity
     * @return void
     */
    public function delete(MenuItemEntity $menu_item_entity): void
    {
        MenuItem::query()->where('id', $menu_item_entity->getId())->delete();
    }

    /**
     * @param int $menu_item_id
     * @return MenuItem|null
     */
    public function find(int $menu_item_id): ?MenuItem
    {
        return MenuItem::query()
            ->with(['category', 'menuitemOptions', 'menuitemOptions.menuitemOptionValues'])
            ->where('id', $menu_item_id)
            ->first();
    }

    /**
     * @param int $option_value_id
     * @return MenuItemOptionValue|null
     */
    public function findOptionValue(int $option_value_id): ?MenuItemOptionValue
    {
        return MenuItemOptionValue::query()
            ->where('id', $option_value_id)
            ->first();
    }

    /**
     * @param CustomerEntity $customer_entity
     * @param int $menu_item_id
     * @return MenuItem|null
     */
    public function findForCustomer(
        CustomerEntity $customer_entity,
        int $menu_item_id,
    ): ?MenuItem {
        return MenuItem::query()
            ->with(['category', 'menuitemOptions'])
            ->where('store_id', $customer_entity->getStoreId())
            ->where('id', $menu_item_id)
            ->first();
    }

    /**
     * @param int $category_id
     * @return bool
     */
    public function existsByCategoryId(int $category_id): bool
    {
        return MenuItem::query()
            ->where('category_id', $category_id)
            ->exists();
    }
}
