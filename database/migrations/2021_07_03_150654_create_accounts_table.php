<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string("name")->nullable();
            $table->string("family")->nullable();
            $table->string("national_code")->nullable();
            $table->string("birthday_city")->nullable();
            $table->string("place_issue")->nullable();
            $table->string("birthday")->nullable();
            $table->integer("grade")->nullable();
            $table->string("major_name")->nullable();
            $table->string("address")->nullable();
            $table->string("home_phone")->nullable();
            $table->string("dad_name")->nullable();
            $table->string("dad_phone")->nullable();
            $table->string("dad_work_address")->nullable();
            $table->string("degree_dad")->nullable();
            $table->tinyInteger("dad_is_dead")->nullable();
            $table->string("mom_name")->nullable();
            $table->string("mom_family")->nullable();
            $table->string("mom_phone")->nullable();
            $table->string("degree_mom")->nullable();
            $table->string("mom_work_address")->nullable();
            $table->tinyInteger("mom_is_dead")->nullable();
            $table->string("relatives_phone")->nullable();
            $table->string("relatives_name")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}