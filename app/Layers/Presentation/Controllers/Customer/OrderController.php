<?php

namespace App\Layers\Presentation\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Layers\Application\UseCase\Customer\Order\ConfirmUseCase;
use App\Layers\Application\UseCase\Customer\Order\ListUseCase;
use App\Layers\Application\UseCase\Customer\Order\StoreUseCase;
use App\Layers\Domain\Entity\Customer\Order\OrderEntity;
use App\Layers\Domain\Entity\Customer\Order\OrderOptionEntity;
use App\Layers\Domain\ValueObject\Invoice;
use App\Layers\Presentation\Requests\Customer\Order\ConfirmRequest;
use App\Layers\Presentation\Requests\Customer\Order\ListRequest;
use App\Layers\Presentation\Requests\Customer\Order\StoreRequest;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * @param ListRequest $request
     * @param ListUseCase $use_case
     * @return JsonResponse
     * @throws \App\Exceptions\DomainException
     */
    public function index(
        ListRequest $request,
        ListUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($request);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        /** @var Invoice $invoice */
        $invoice = $output->getData()[1];

        return response()->json([
            'orders' => $output->getData()[0]->map(function (OrderEntity $item) {
                return [
                    'id' => $item->getId(),
                    'menu_name' => $item->getMenuName(),
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getPrice(),
                    'options' => $item->getOptions()->map(function (OrderOptionEntity $option) {
                        return [
                            'option_value_id' => $option->getOptionValueId(),
                            'option_name' => $option->getOptionName(),
                            'option_value_name' => $option->getOptionValueName(),
                            'cost' => $option->getCost(),
                        ];
                    }),
                    'total_price' => $item->getTotalPrice(),
                ];
            }),
            /** @var Invoice $invoice */
            'total' => $invoice->getTotal(),
            'tax' => $invoice->getConsumptionTax(),
            'total_include_tax' => $invoice->getTotalIncludingTax(),
        ]);
    }

    public function confirm(
        ConfirmRequest $request,
        ConfirmUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($request);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json([
            'orders' => $output->getData()[0]->map(function ($item) {
                /** @var CustomerOrderEntity $item */
                return [
                    'menu_item_id' => $item->getMenuItemId(),
                    'menu_name' => $item->getMenuName(),
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getPrice(),
                    'options' => $item->getOptions()->map(function ($option) {
                        /** @var CustomerOrderOptionEntity $option */
                        return [
                            'id' => $option->getId(),
                            'option_value_id' => $option->getOptionValueId(),
                            'option_name' => $option->getOptionName(),
                            'option_value_name' => $option->getOptionValueName(),
                            'cost' => $option->getCost(),
                        ];
                    }),
                    'total_price' => $item->getTotalPrice(),
                ];
            }),
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param StoreUseCase $use_case
     * @return JsonResponse
     */
    public function store(
        StoreRequest $request,
        StoreUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($request);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }
}
