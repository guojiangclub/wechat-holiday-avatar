<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-09-24
 * Time: 19:49
 */
namespace iBrand\HolidayAvatar\Server\Model;

use Illuminate\Database\Eloquent\Model;

class UserAvatar extends Model
{

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'users_avatar');

        parent::__construct($attributes);
    }

    protected $guarded = ['id'];
}