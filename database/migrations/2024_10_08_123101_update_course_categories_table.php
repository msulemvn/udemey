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
        Schema::table('course_categories', function (Blueprint $table) {
            $table->dropForeign(['course_id']); // Remove foreign key constraint if it exists
            $table->dropColumn('course_id'); // Drop the course_id column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_categories', function (Blueprint $table) {
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade'); // Re-add the course_id column
        });
    }
};
