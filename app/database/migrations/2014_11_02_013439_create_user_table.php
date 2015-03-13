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
		Schema::create('users', function(Blueprint $table)
		{
            $table->increments("id");
           // $table->string("username");
            $table->string("password")->nullable();
            $table->string("email")->nullable();
            $table->string("remember_token")->nullable();
            //$table->boolean("locked")->default(0);
            //$table->string("confirmation_code")->nullable();
            $table->string("session_token");
            $table->integer("currently_playing")->default(0);
            $table->timestamps();
		});

        Schema::create('songs', function(Blueprint $table)
        {
            $table->increments("id");
            $table->string("session_token");
            $table->string("songid");
            $table->string("songname");
            $table->string("votes")->default(0);
            $table->integer("priority");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists("users");
        Schema::dropIfExists("songs");
	}

}
