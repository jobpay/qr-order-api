<?php

namespace App\Layers\Presentation\Requests\Shop\MenuItem;

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
            'category_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|integer',
//            'options' => 'nullable|array',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'category_id.required' => 'カテゴリーIDは必須です',
            'category_id.integer' => 'カテゴリーIDは数値で指定してください',
            'name.required' => '商品名は必須です',
            'name.string' => '商品名は文字列で指定してください',
            'name.max' => '商品名は255文字以内で指定してください',
            'price.required' => '価格は必須です',
            'price.integer' => '価格は数値で指定してください',
            'image.image' => '画像は画像ファイルを指定してください',
            'image.mimes' => '画像はjpeg,png,jpg形式のファイルを指定してください',
            'image.max' => '画像は2MB以内のファイルを指定してください',
            'status.required' => 'ステータスは必須です',
            'status.integer' => 'ステータスは数値で指定してください',
        ];
    }
}
