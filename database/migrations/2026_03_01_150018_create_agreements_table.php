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
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->string('agreement_number')->unique();
            $table->string('client_name');
            $table->string('client_email');
            $table->string('company_name')->nullable();
            $table->text('service_description');
            $table->longText('scope_of_work');
            $table->decimal('price', 15, 2);
            $table->text('payment_terms');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'sent', 'signed', 'expired', 'cancelled'])->default('draft');
            $table->dateTime('signed_at')->nullable();
            $table->string('signature_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
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
