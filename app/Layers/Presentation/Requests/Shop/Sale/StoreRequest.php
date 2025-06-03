<?php

namespace App\Layers\Presentation\Requests\Shop\Sale;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'customer_id' => ['required', 'integer'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'customer_id.required' => '顧客IDは必須です',
            'customer_id.integer' => '顧客IDは数値で入力してください',
        ];
    }
}
