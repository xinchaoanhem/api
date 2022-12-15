<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelHasCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('label_has_card', function (Blueprint $table) {
            $table->unsignedBigInteger('label_id')->unsigned();
            $table->foreign('label_id')->references('id')
                ->on('labels')->onDelete('cascade');
            $table->unsignedBigInteger('card_id')->unsigned();
            $table->foreign('card_id')->references('id')
                ->on('cards')->onDelete('cascade');
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
        Schema::dropIfExists('label_has_card');
    }
}
