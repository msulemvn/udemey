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
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade'); // Foreign key reference to articles
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key reference to users
            $table->unsignedBigInteger('parent_comment_id')->nullable(); // Parent comment ID for nested comments
            $table->foreign('parent_comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->text('content'); // Comment content
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Comment status for moderation
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
        Schema::dropIfExists('comments');
    }
};
