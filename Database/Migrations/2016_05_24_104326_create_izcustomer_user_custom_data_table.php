<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIzcustomerUserCustomDataTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(
            'izcustomer_user_custom_data',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->json('value');
                $table->unsignedInteger('user_id');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('izcustomer_user_custom_data');
    }

}
