<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('number');
            $table->string('reference');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
        Schema::create('visits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role');
            $table->unsignedInteger('address_id');
            $table->unsignedInteger('payment_card_id');
            $table->unsignedInteger('doctor_id')->nullable();
            $table->timestamp('visit_at');
            $table->timestamps();

            $table->foreign('address_id')
                ->references('id')->on('addresses')
                ->onDelete('cascade');
            $table->foreign('payment_card_id')
                ->references('id')->on('payment_cards')
                ->onDelete('cascade');
            $table->foreign('doctor_id')
                ->references('id')->on('doctors')
                ->onDelete('set null');
        });
        Schema::create('visit_card', function (Blueprint $table) {
            $table->unsignedInteger('visit_id');
            $table->unsignedInteger('card_id');
            $table->unique(['visit_id', 'card_id']);

            $table->foreign('visit_id')
                ->references('id')->on('visits')
                ->onDelete('cascade');
            $table->foreign('card_id')
                ->references('id')->on('cards')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit_card');
        Schema::dropIfExists('visits');
        Schema::dropIfExists('payment_cards');
    }
}
