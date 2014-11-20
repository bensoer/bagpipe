<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user', function(Blueprint $table)
		{
            $table->increments("id");
           // $table->string("username");
            $table->string("password")->nullable();
            $table->string("email")->nullable();
            $table->string("remember_token")->nullable();
            //$table->boolean("locked")->default(0);
            //$table->string("confirmation_code")->nullable();
            $table->string("session_token");
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
        Schema::dropIfExists("user");
	}

}
