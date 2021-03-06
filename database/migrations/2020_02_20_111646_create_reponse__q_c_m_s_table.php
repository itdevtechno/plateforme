<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReponseQCMSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reponse_qcm', function (Blueprint $table) {
            $table->bigIncrements('reponse_id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('option_id');
            $table->unsignedBigInteger('test_id');
            $table->unsignedBigInteger('question_id');
            $table->integer('note');
            $table->timestamps();

            $table->foreign('session_id')->references('session_id')->on('session')->onDelete('cascade');
            $table->foreign('option_id')->references('option_id')->on('option')->onDelete('cascade');
            $table->foreign('test_id')->references('test_id')->on('test')->onDelete('cascade');
            $table->foreign('question_id')->references('question_id')->on('qcm')->onDelete('cascade');

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reponse_qcm');
    }
}
