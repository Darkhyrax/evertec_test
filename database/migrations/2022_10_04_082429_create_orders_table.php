<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) 
        {
            $table->id();
            $table->foreignIdFor(App\Models\Customer::class);
            $table->foreign('customer_id')->references('id')->on('customer')->onDelete('cascade');
            $table->foreignIdFor(App\Models\Products::class);
            $table->foreign('products_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreignIdFor(App\Models\Status::class);
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->float('total_order',20,2);
            $table->string('order_reference');
            $table->integer('internal_reference')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
