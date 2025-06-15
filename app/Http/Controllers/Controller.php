<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * @return int|null
     * @throws AuthenticationException
     */
    protected function getStoreId(): ?int
    {
        // ログイン済みの場合はログインユーザーの店舗IDを返す
        if (auth()->check()) {
            return auth()->user()->store_id;
        } else {
            throw new AuthenticationException();
        }
    }
}
