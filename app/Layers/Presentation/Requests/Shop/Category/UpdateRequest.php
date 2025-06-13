<?php

namespace App\Layers\Presentation\Requests\Shop\Category;

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
            'name' => ['required', 'string', 'max:100'],
            'order' => ['required', 'integer'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'name.required' => 'カテゴリー名は必須です',
            'name.string' => 'カテゴリー名は文字列で入力してください',
            'name.max' => 'カテゴリー名は100文字以内で入力してください',
            'order.required' => '表示順は必須です',
            'order.integer' => '表示順は整数で入力してください',
        ];
    }
}
