<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDonationRequestsTable extends Migration {

	public function up()
	{
		Schema::create('donation_requests', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('age');
			$table->string('bags_num');
			$table->string('hos_name');
			$table->string('hos_address');
			$table->decimal('lat', 4,2)->nullable();
			$table->decimal('lng', 4,2)->nullable();
			$table->string('phone');
			$table->text('notes')->nullable();
			$table->integer('blood_type_id')->unsigned();
			$table->integer('city_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('donation_requests');
	}
}