<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	 public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();  // auto-increment id
			$table->string('name');
			$table->unsignedBigInteger('telegram_id')->unique();  // telegram_id, уникальный
			$table->boolean('subscribed')->default(false);  // подписка (по умолчанию false)
			$table->timestamps();  // created_at и updated_at
		});
	}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
