<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->longText("product_name")->nullable();
            $table->string("manufacturer")->nullable();
            $table->string("price")->nullable();
            $table->string("number_available_in_stock")->nullable();
            $table->string("number_of_reviews")->nullable();
            $table->string("average_review_rating")->nullable();
            $table->integer("sold")->nullable();
            $table->string("entry_date")->nullable();
            $table->unsignedBigInteger('file_id')->index()->nullable(); //->references('id')->on('file');
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
        Schema::dropIfExists('analytics');
    }
}
