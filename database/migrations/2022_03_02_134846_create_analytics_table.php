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
            $table->date("entry_date");
            $table->unsignedBigInteger('file_id')->index()->nullable(); //->references('id')->on('file');
            $table->string("sellingPrice")->nullable();
            $table->string("old_cus")->nullable();
            $table->string("new_cus")->nullable();
            $table->string("pending_orders")->nullable();
            $table->string("soldAmountProducts")->nullable();
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
