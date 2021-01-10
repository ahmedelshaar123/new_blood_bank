<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStaticPagesTable extends Migration {

	public function up()
	{
		Schema::create('static_pages', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name')->unique();
			$table->text('value')->nullable();
			$table->string('type');
			$table->string('key')->unique();
		});
	}

	public function down()
	{
		Schema::drop('static_pages');
	}
}