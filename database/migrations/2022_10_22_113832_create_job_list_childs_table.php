<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobListChildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_list_childs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('status')->unsigned()->nullable();
            $table->unsignedBigInteger('job_list_id')->unsigned();
            $table->foreign('job_list_id')->references('id')
                ->on('job_lists')->onDelete('cascade');
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
        Schema::dropIfExists('job_list_childs');
    }
}
