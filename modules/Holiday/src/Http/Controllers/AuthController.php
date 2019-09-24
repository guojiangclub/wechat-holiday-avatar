<?php

/*
 * This file is part of ibrand/edu-server.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\HolidayAvatar\Server\Http\Controllers;

use EasyWeChat\Factory;
use iBrand\Component\User\Models\User;
use iBrand\Component\User\Repository\UserBindRepository;
use iBrand\Component\User\Repository\UserRepository;
use iBrand\Component\User\UserService;
use iBrand\Common\Controllers\Controller;

class AuthController extends Controller
{
    protected $userRepository;
    protected $userBindRepository;
    protected $userService;

    public function __construct(UserRepository $userRepository, UserBindRepository $userBindRepository, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userBindRepository = $userBindRepository;
        $this->userService = $userService;
    }


    public function wxlogin()
    {
        $app = request('app') ?? 'default';

        $options = [
            'app_id' => config('ibrand.wechat.official_account.' . $app . '.app_id'),
            'secret' => config('ibrand.wechat.official_account.' . $app . '.secret'),
        ];

        $app = Factory::officialAccount($options);

        $redirect_url = request('redirect_url');

        \Log::info('$redirect_url:' . $redirect_url);

        $response = $app->oauth->scopes(['snsapi_userinfo'])
            ->redirect(route('api.oauth.getUerInfo', ['target_url' => $redirect_url]));

        return $response;
    }

    public function getUerInfo()
    {
        $app = request('app') ?? 'default';

        $options = [
            'app_id' => config('ibrand.wechat.official_account.' . $app . '.app_id'),
            'secret' => config('ibrand.wechat.official_account.' . $app . '.secret'),
        ];

        $app = Factory::officialAccount($options);

        $user = $app->oauth->user();
        $openid = $user->getId();

        $url = request('target_url') . '&openid=' . $openid;

        $openid = $user->getId();  // 对应微信的 OPENID
        $nick_name = $user->getNickname(); // 对应微信的 nickname
        $avatar = $user->getAvatar(); // 头像网址

        if (!$this->userBindRepository->getByOpenId($openid)) {
            $user = $this->userRepository->create([
                'nick_name' => $nick_name,
                'avatar' => $avatar
            ]);

            $this->userService->bindPlatform($user->id, $openid, $options['app_id']);
        }

        return redirect($url);
    }


    public function quickLogin()
    {
        $openid = request('open_id');
        $app = request('app') ?? 'default';
        $appid = get_wechat_config($app)['app_id'];

        if (empty($openid)) {
            return $this->failed('Missing openid parameters.');
        }

        //1. openid 不存在相关用户和记录，直接返回 openid
        if (!$userBind = $this->userBindRepository->getByOpenId($openid)) {
            return $this->success(['open_id' => $openid]);
        }

        //2. openid 不存在相关用户，直接返回 openid
        if (!$userBind->user_id) {
            return $this->success(['open_id' => $openid]);
        }

        //3. 绑定了用户,直接返回 token
        $user = $this->userRepository->find($userBind->user_id);

        $token = $user->createToken($user->id)->accessToken;

        return $this->success(['token_type' => 'Bearer', 'access_token' => $token, 'user' => $user]);
    }

}
