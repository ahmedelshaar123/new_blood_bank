<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration {

	public function up()
	{
		Schema::create('articles', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title');
			$table->text('body');
			$table->text('image');
			$table->integer('category_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('articles');
	}
}