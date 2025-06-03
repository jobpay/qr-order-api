<?php

namespace App\Layers\Presentation\Requests\Customer\Order;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'token' => $this->cookie('qr_customer_token'),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'orders' => ['required', 'array'],
            'orders.*.menu_item_id' => ['required', 'integer'],
            'orders.*.quantity' => ['required', 'integer'],
            'orders.*.option_value_ids' => ['nullable', 'array'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'token.required' => '入店情報が確認できませんでした。座席QRコードの読み取りをお願いします。',
            'token.string' => 'tokenは文字列で入力してください',
            'orders.required' => '注文情報がありません',
            'orders.array' => '注文情報は配列で入力してください',
            'orders.*.menu_item_id.required' => 'メニューIDが指定されていません',
            'orders.*.menu_item_id.integer' => 'メニューIDは整数で入力してください',
            'orders.*.quantity.required' => '数量が指定されていません',
            'orders.*.quantity.integer' => '数量は整数で入力してください',
            'orders.*.option_value_ids.array' => 'オプション情報は配列で入力してください',
        ];
    }
}
