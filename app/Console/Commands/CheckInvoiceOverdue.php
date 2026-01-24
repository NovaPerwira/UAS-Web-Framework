<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckInvoiceOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and mark overdue invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = \App\Models\Invoice::whereIn('status', [\App\Models\Invoice::STATUS_SENT, \App\Models\Invoice::STATUS_UNPAID])
            ->where('due_date', '<', now()->startOfDay())
            ->update(['status' => \App\Models\Invoice::STATUS_OVERDUE]);

        $this->info("Marked {$count} invoices as overdue.");
    }
}
