<?php

namespace App\Layers\Presentation\Requests\Shop\Order;

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
            'status' => 'required|integer',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [];
    }
}
