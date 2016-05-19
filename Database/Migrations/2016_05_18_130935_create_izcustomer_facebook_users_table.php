<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIzcustomerFacebookUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(
            'izcustomer_facebook_users',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('email');
                $table->char('facebook_id');
                $table->string('first_name');
                $table->string('last_name');
                $table->string('access_token')->nullable();
                $table->integer('user_id');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('izcustomer_facebook_users');
    }

}
