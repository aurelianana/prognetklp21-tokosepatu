<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                    ->constrained('products')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->foreignId('category_id')
                    ->constrained('product_categories')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            // $table->foreignId('product_id');
            // $table->foreignId('category_id');

            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            // $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
            // $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_category_details');
    }
}
