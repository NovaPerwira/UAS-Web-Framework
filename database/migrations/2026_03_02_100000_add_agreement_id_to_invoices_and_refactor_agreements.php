<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * STEP 1: Add agreement_id to invoices (nullable to allow safe backfill)
     * STEP 2: Backfill agreement_id on invoices using the old invoice_id on agreements
     * STEP 3: Drop old FK + invoice_id from agreements
     * STEP 4: Add new agreement fields (total_value, rendered_content) + rename total_price
     */
    public function up(): void
    {
        // ─────────────────────────────────────────────
        // STEP 1: Add agreement_id FK to invoices (nullable first for safe backfill)
        // ─────────────────────────────────────────────
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('agreement_id')->nullable()->after('id');
            $table->decimal('amount_due', 15, 2)->nullable()->after('grand_total');
            $table->string('payment_reference')->nullable()->after('amount_due');
            $table->index('agreement_id');
        });

        // ─────────────────────────────────────────────
        // STEP 2: Backfill — map invoices to agreements via old invoice_id on agreements
        // Uses JOIN to find which invoice belongs to which agreement
        // ─────────────────────────────────────────────
        DB::statement("
            UPDATE invoices
            INNER JOIN agreements ON agreements.invoice_id = invoices.id
            SET invoices.agreement_id = agreements.id
        ");

        // ─────────────────────────────────────────────
        // STEP 3: Now that we have backfilled, add the real FK constraint
        // ─────────────────────────────────────────────
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('agreement_id')
                ->references('id')
                ->on('agreements')
                ->restrictOnDelete();
        });

        // ─────────────────────────────────────────────
        // STEP 4: Alter agreements table
        // - Drop invoice_id FK and column
        // - Rename total_price → total_value
        // - Add rendered_content
        // - Status column should already exist (from create migration), 
        //   but we add it if not present
        // ─────────────────────────────────────────────
        Schema::table('agreements', function (Blueprint $table) {
            // Drop existing FK on invoice_id if it exists
            // Using try/catch since constraint name may vary
            try {
                $table->dropForeign(['invoice_id']);
            } catch (\Exception $e) {
                // FK may not exist or may have a different name — ignore
            }

            $table->dropColumn('invoice_id');
            $table->renameColumn('total_price', 'total_value');
            $table->longText('rendered_content')->nullable()->after('scope_of_work');
        });

        // Ensure status column has correct enum values (re-create it if needed)
        // The existing migration already has status enum, so this is a safety guard
        if (!Schema::hasColumn('agreements', 'status')) {
            Schema::table('agreements', function (Blueprint $table) {
                $table->enum('status', ['draft', 'issued', 'signed', 'cancelled'])
                    ->default('draft')
                    ->after('rendered_content');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore invoice_id on agreements
        Schema::table('agreements', function (Blueprint $table) {
            $table->renameColumn('total_value', 'total_price');
            $table->dropColumn('rendered_content');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('cascade');
        });

        // Remove agreement_id from invoices
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['agreement_id']);
            $table->dropIndex(['agreement_id']);
            $table->dropColumn(['agreement_id', 'amount_due', 'payment_reference']);
        });
    }
};
