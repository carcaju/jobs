<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecreationPlace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recreation_place', function (Blueprint $table) {
            $table->increments('id');
            $table->char('country_code',3);
            $table->unsignedInteger('city_id')->nullable();
            $table->string('name');
            $table->string('address');
            $table->string('district');
            $table->string('description');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('phone',30)->nullable();
            $table->string('email')->nullable();
            $table->text('policies');
            $table->text('comments');
            $table->boolean('parking_lot');
            $table->timestamps();

            $table->index('country_code');
            $table->index('state_code');
            $table->index('city_id');

            $table->foreign('city_id')
                ->references('id')
                ->on('cities');

            $table->foreign('city_id')
                ->references('code')
                ->on('countries');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recreation_place');
    }
}
