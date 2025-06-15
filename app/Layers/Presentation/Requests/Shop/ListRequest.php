<?php

namespace App\Layers\Presentation\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public const DEFAULT_LIMIT = 20;
    public const DEFAULT_OFFSET = 0;

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
            'limit' => ['required', 'integer'],
            'offset' => ['required', 'integer'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'limit.required' => 'limitは必須です',
            'limit.integer' => 'limitは整数で入力してください',
            'offset.required' => 'offsetは必須です',
            'offset.integer' => 'offsetは整数で入力してください',
        ];
    }
}
