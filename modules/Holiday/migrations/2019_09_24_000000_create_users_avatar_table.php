<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersAvatarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');
        if (!Schema::hasTable($prefix . 'users_avatar')) {
            Schema::create($prefix . 'users_avatar', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('avatar')->nullable();
                $table->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');
        Schema::dropIfExists($prefix . 'users_avatar');
    }
}
