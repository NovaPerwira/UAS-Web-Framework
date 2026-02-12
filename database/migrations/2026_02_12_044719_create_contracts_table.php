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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('contract_number')->nullable()->unique();
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('contract_value', 15, 2)->default(0);
            $table->enum('status', ['draft', 'sent', 'accepted', 'declined', 'active', 'completed', 'terminated'])->default('draft');
            $table->longText('content')->nullable(); // For custom contract text
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
