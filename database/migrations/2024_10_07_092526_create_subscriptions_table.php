<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key referencing users
            $table->string('plan'); // Subscription plan name
            $table->timestamp('start_date')->default(now()); // Start date of the subscription
            $table->timestamp('end_date')->nullable(); // End date of the subscription
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active'); // Subscription status
            $table->boolean('is_trial')->default(true); // Indicates if the subscription is in trial period
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
        Schema::dropIfExists('subscriptions');
    }
}
