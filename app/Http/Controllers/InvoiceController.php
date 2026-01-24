<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\InvoiceItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('invoices.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'tax_rate' => 'numeric|min:0|max:100',
            'discount_amount' => 'numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $invoice = Invoice::create([
                'client_id' => $validated['client_id'],
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'tax_rate' => $validated['tax_rate'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'status' => Invoice::STATUS_DRAFT,
            ]);

            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            $invoice->updateTotals();
        });

        return redirect()->route('invoices.index')->with('success', 'Draft invoice created.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'items', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->status !== Invoice::STATUS_DRAFT) {
            return redirect()->route('invoices.show', $invoice)->with('error', 'Cannot edit sent invoice.');
        }
        $clients = Client::orderBy('name')->get();
        return view('invoices.edit', compact('invoice', 'clients'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status !== Invoice::STATUS_DRAFT) {
            return redirect()->route('invoices.show', $invoice)->with('error', 'Cannot edit sent invoice.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'tax_rate' => 'numeric|min:0|max:100',
            'discount_amount' => 'numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            $invoice->update([
                'client_id' => $validated['client_id'],
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'tax_rate' => $validated['tax_rate'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Replace items logic
            $invoice->items()->delete();
            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            $invoice->updateTotals();
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice updated.');
    }

    public function send(Invoice $invoice)
    {
        if ($invoice->status !== Invoice::STATUS_DRAFT) {
            return redirect()->back()->with('error', 'Invoice is not in draft status.');
        }

        DB::transaction(function () use ($invoice) {
            $year = date('Y');
            // Check for existing numbers this year to increment
            $lastInvoice = Invoice::whereYear('created_at', $year)
                ->whereNotNull('invoice_number')
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $sequence = 1;
            if ($lastInvoice && preg_match('/\/(\d+)$/', $lastInvoice->invoice_number, $matches)) {
                $sequence = intval($matches[1]) + 1;
            }

            $number = 'INV/COMPANY/' . $year . '/' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            $invoice->update([
                'status' => Invoice::STATUS_UNPAID,
                'invoice_number' => $number,
            ]);
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice sent successfully.');
    }

    public function addPayment(Request $request, Invoice $invoice)
    {
        if ($invoice->status === Invoice::STATUS_DRAFT) {
            return redirect()->back()->with('error', 'Cannot add payment to draft invoice.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            $invoice->payments()->create($validated);

            $totalPaid = $invoice->payments()->sum('amount');
            if ($totalPaid >= $invoice->grand_total) {
                $invoice->update(['status' => Invoice::STATUS_PAID]);
            } else {
                // Revert to unpaid if it was paid but maybe a payment was deleted? 
                // Here we are adding, so usually it goes towards paid.
                // If partial, it stays unpaid (or partial if we had that status, but we don't).
            }
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Payment recorded.');
    }
}
