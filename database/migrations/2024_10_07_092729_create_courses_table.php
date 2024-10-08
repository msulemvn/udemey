<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Course title
            $table->string('slug')->unique();
            $table->text('description'); // Course description
            $table->decimal('price', 10, 2); // Course price
            $table->decimal('discounted_price', 10, 2)->nullable(); // Discounted price (nullable)
            $table->string('thumbnail_url')->nullable(); // Thumbnail URL (nullable)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key referencing the instructor (user)
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
        Schema::dropIfExists('courses');
    }
}
