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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_log_id')->nullable();
            $table->foreign('request_log_id')->references('id')->on('http_requests');
            $table->string('line_number');
            $table->string('function');
            $table->string('file');
            $table->longText('exception_message')->nullable();
            $table->longText('trace')->nullable();
            $table->string('ip')->index();
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
        Schema::dropIfExists('error_logs');
    }
};
