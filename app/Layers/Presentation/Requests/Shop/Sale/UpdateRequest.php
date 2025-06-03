<?php

namespace App\Layers\Presentation\Requests\Shop\Sale;

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
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|integer',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'amount.required' => '金額は必須です',
            'amount.integer' => '金額は数値で指定してください',
        ];
    }
}
