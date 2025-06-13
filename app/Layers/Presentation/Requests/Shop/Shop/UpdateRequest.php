<?php

namespace App\Layers\Presentation\Requests\Shop\Shop;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer'],
            'postal_code' => ['nullable', 'integer'],
            'address' => ['nullable','string', 'max:1000'],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'name.required' => '名前は必須です',
            'name.string' => '名前は文字列で入力してください',
            'name.max' => '名前は255文字以内で入力してください',
            'category_id.required' => '店舗カテゴリーは必須です',
            'category_id.integer' => '店舗カテゴリーは整数で入力してください',
            'postal_code.integer' => '郵便番号は整数で入力してください',
            'address.string' => '住所は文字列で入力してください',
        ];
    }
}
