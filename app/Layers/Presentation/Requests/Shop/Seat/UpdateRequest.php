<?php

namespace App\Layers\Presentation\Requests\Shop\Seat;

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
            'number' => ['required', 'string', 'max:10'],
            'order' => ['required', 'integer'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'number.required' => '席番号は必須です',
            'number.string' => '席番号は文字列で入力してください',
            'number.max' => '席番号は10文字以内で入力してください',
            'order.required' => '並び順は必須です',
            'order.integer' => '並び順は整数で入力してください',
        ];
    }
}
