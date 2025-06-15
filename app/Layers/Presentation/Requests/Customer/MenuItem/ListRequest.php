<?php

namespace App\Layers\Presentation\Requests\Customer\MenuItem;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public const DEFAULT_LIMIT = 20;
    public const DEFAULT_OFFSET = 0;

    /**
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'token' => $this->cookie('qr_customer_token'),
            'limit' => $this->input('limit', self::DEFAULT_LIMIT),
            'offset' => $this->input('offset', self::DEFAULT_OFFSET),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'limit' => ['required', 'integer'],
            'offset' => ['required', 'integer'],
            'category' => ['integer'],
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
            'limit.required' => 'limitは必須です',
            'limit.integer' => 'limitは整数で入力してください',
            'offset.required' => 'offsetは必須です',
            'offset.integer' => 'offsetは整数で入力してください',
            'category.integer' => 'categoryは整数で入力してください',
        ];
    }
}
