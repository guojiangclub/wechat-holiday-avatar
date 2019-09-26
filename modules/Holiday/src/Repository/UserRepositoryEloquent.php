<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-09-26
 * Time: 10:44
 */
namespace iBrand\HolidayAvatar\Server\Repository;

use iBrand\HolidayAvatar\Server\Model\User;

class UserRepositoryEloquent extends \iBrand\Component\User\Repository\Eloquent\UserRepositoryEloquent
{
    public function model()
    {
        return User::class;
    }
}