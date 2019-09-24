<?php


namespace App\Http\Controllers;


use iBrand\Common\Wechat\Factory;

class IndexController extends Controller
{
    public function oauth()
    {
        $app = Factory::officialAccount();
        return $app->oauth->scopes(['snsapi_userinfo'])
            ->redirect();
    }

    public function profile()
    {
        $app = Factory::officialAccount();
        $oauth = $app->oauth;
        $user = $oauth->user();
    }
}