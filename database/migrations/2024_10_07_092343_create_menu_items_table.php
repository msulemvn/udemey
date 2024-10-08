<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name of the menu item
            $table->foreignId('page_id')->nullable()->constrained('pages'); // Optional foreign key for a page
            $table->unsignedBigInteger('article_category_id')->nullable(); // Reference to an article category (can be added later if necessary)
            $table->integer('position')->default(0); // Position in the menu
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
        Schema::dropIfExists('menu_items');
    }
};
