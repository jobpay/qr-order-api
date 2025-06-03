<?php

namespace App\Layers\Presentation\Requests\Customer\MenuItem;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
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
