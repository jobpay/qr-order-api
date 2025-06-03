<?php

namespace App\Layers\Presentation\Requests\Shop\Sale;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    const DEFAULT_LIMIT = 20;
    const DEFAULT_OFFSET = 0;

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
        logger($this->input('from'));

        $this->merge([
            'from' => $this->input('from'),
            'to' => $this->input('to'),
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
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'limit' => ['required', 'integer'],
            'offset' => ['required', 'integer'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date'],
            'category_id' => ['nullable', 'integer'],
            'menu_name' => ['nullable', 'string'],
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
            'start_at.date' => 'start_atは日付で入力してください',
            'end_at.date' => 'end_atは日付で入力してください',
            'category_id.integer' => 'category_idは整数で入力してください',
            'menu_name.string' => 'menu_nameは文字列で入力してください',
        ];
    }
}
