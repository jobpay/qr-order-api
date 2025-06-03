<?php

namespace App\Layers\Presentation\Requests\Customer\Seat;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'token' => $this->cookie('qr_customer_token'),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
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
        ];
    }
}
