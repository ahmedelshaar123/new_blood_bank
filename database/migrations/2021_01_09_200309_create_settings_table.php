<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name')->unique();
			$table->string('value')->nullable();
			$table->string('type');
			$table->string('key')->unique();
		});
	}

	public function down()
	{
		Schema::drop('settings');
	}
}