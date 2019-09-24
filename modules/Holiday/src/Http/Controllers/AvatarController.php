<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-09-24
 * Time: 19:14
 */

namespace iBrand\HolidayAvatar\Server\Http\Controllers;


use iBrand\Common\Controllers\Controller;
use iBrand\HolidayAvatar\Server\Model\UserAvatar;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;

class AvatarController extends Controller
{
    public function createAvatar()
    {
        $user = request()->user();
        $ico = request('ico');
        $ico_img_name = $user->id . '_wx_ico.png';

        $ico_img = getImage($ico, storage_path('app/public/userAvatar/'), $ico_img_name, 1);
        if (!$ico_img['error']) { //下载成功
            $ico_path = storage_path('app/public/userAvatar/' . $ico_img_name);
        } else {
            return $this->failed('头像创建失败');
        }

        $saveName = storage_path('app/public/userAvatar/' . $user->id . '_holiday.jpg');
        $imgPath = $this->getUserAvatar($user);
        $img = Image::make($imgPath);
        $img->insert($ico_path, 'bottom-right', 0, 0); //添加头像
        $img->save($saveName);

        $holidayAvatarUrl = env('APP_URL') . '/storage/userAvatar/' . $user->id . '_holiday.jpg';
        UserAvatar::create([
            'user_id' => $user->id,
            'avatar' => $holidayAvatarUrl
        ]);

        return $this->success($holidayAvatarUrl);

    }

    protected function getUserAvatar($user)
    {
        $wx_img_name = $user->id . '_wx.jpg';
        $exists = Storage::disk('public')->exists('userAvatar/' . $wx_img_name);
        if ($exists) {  //如果头像已经存在，直接返回头像
            return storage_path('app/public/userAvatar/' . $wx_img_name);
        }

        if ($user->avatar) { //如果用户有头像
            $wx_img = getImage($user->avatar, storage_path('app/public/userAvatar/'), $wx_img_name, 1); //下载微信头像
            if (!$wx_img['error']) { //下载成功
                return storage_path('app/public/userAvatar/' . $wx_img_name);
            }
        }

        return false;
    }
}