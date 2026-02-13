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
        Schema::table('contracts', function (Blueprint $table) {
            $table->longText('scope_of_work')->nullable()->after('contract_value');
            $table->longText('timeline')->nullable()->after('scope_of_work');
            $table->longText('payment_terms')->nullable()->after('timeline');
            $table->longText('revisions')->nullable()->after('payment_terms');
            $table->longText('ownership_rights')->nullable()->after('revisions');
            $table->longText('warranty')->nullable()->after('ownership_rights');
            $table->longText('general_terms')->nullable()->after('warranty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'scope_of_work',
                'timeline',
                'payment_terms',
                'revisions',
                'ownership_rights',
                'warranty',
                'general_terms'
            ]);
        });
    }
};
