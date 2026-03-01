<?php
try {
    echo "Rendering index...\n";
    $agreements = App\Models\Agreement::with(['invoice', 'creator'])->latest()->paginate(10);
    $view = view('agreements.index', compact('agreements'))->render();
    echo "Agreements index OK length: " . strlen($view) . "\n";

    echo "Rendering create...\n";
    $invoices = App\Models\Invoice::doesntHave('agreement')->get();
    $templates = App\Models\AgreementTemplate::all();
    $selectedInvoice = null;
    $view2 = view('agreements.create', compact('invoices', 'templates', 'selectedInvoice'))->render();
    echo "Agreements create OK length: " . strlen($view2) . "\n";

} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
}
