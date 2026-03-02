<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('invoice_id')->unique()->constrained()->onDelete('cascade');

            // Metas  
            $table->string('agreement_number')->unique();
            $table->date('agreement_date');

            // Provider Info
            $table->string('provider_name');
            $table->text('provider_address');
            $table->string('provider_email');

            // Client Info  
            $table->string('client_name');
            $table->text('client_address');
            $table->string('client_email');

            // Project Details
            $table->string('project_name');
            $table->text('service_description');
            $table->text('scope_of_work');

            // Financials
            $table->decimal('total_price', 15, 2);
            $table->text('payment_terms');

            // Timelines
            $table->date('start_date');
            $table->date('estimated_completion_date');

            // State
            $table->enum('status', ['draft', 'issued', 'signed', 'cancelled'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};
