<?php

namespace App\Layers\Presentation\Requests\Shop\Shop;

use App\Models\User;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required'],
            'store_name' => ['required', 'string', 'max:255'],
            'store_category_id' => ['required', 'integer'],
            'store_postal_code' => ['integer'],
            'store_address' => ['string', 'max:1000'],
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'name.required' => '名前は必須です',
            'name.string' => '名前は文字列で入力してください',
            'name.max' => '名前は255文字以内で入力してください',
            'email.required' => 'メールアドレスは必須です',
            'email.string' => 'メールアドレスは文字列で入力してください',
            'email.lowercase' => 'メールアドレスは小文字で入力してください',
            'email.email' => 'メールアドレスの形式で入力してください',
            'email.max' => 'メールアドレスは255文字以内で入力してください',
            'email.unique' => 'そのメールアドレスは既に登録されています',
            'password.required' => 'パスワードは必須です',
            'password.confirmed' => 'パスワードが一致しません',
            'store_name.required' => '店舗名は必須です',
            'store_name.string' => '店舗名は文字列で入力してください',
            'store_name.max' => '店舗名は255文字以内で入力してください',
            'store_category_id.required' => '店舗カテゴリは必須です',
            'store_category_id.integer' => '店舗カテゴリは整数で入力してください',
            'store_postal_code.integer' => '郵便番号は整数で入力してください',
            'store_address.string' => '住所は文字列で入力してください',
            'store_address.max' => '住所は1000文字以内で入力してください',
            'store_logo.image' => 'ロゴは画像ファイルで入力してください',
            'store_logo.max' => 'ロゴは1024KB以内で入力してください',
        ];
    }
}
