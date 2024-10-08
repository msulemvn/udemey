<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade'); // Foreign key referencing articles
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key referencing users
            $table->decimal('amount', 10, 2); // Amount paid for the article
            $table->timestamp('purchase_date')->default(now()); // Date of purchase
            $table->enum('status', ['completed', 'refunded', 'disputed'])->default('completed'); // Status of the purchase
            $table->softDeletes();
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
