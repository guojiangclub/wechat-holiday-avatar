<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-09-24
 * Time: 19:49
 */
namespace iBrand\HolidayAvatar\Server\Model;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class User extends \iBrand\Component\User\Models\User
{
    use HasApiTokens;
}