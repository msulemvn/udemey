<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseCategoriesIdToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Add the foreignId column referencing course_categories
            $table->foreignId('course_categories_id')->after('user_id')->constrained('course_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop the foreign key and column if you rollback the migration
            $table->dropForeign(['course_categories_id']);
            $table->dropColumn('course_categories_id');
        });
    }
}
