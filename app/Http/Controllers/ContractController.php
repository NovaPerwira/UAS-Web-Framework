<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contracts = Contract::with('client')->latest()->paginate(10);
        return view('contracts.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clients = Client::all();
        $projects = Project::all();

        if ($request->has('client_id')) {
            $projects = Project::where('client_id', $request->client_id)->get();
        }

        return view('contracts.create', compact('clients', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'contract_value' => 'nullable|numeric|min:0',
            'scope_of_work' => 'nullable|string',
            'timeline' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'revisions' => 'nullable|string',
            'ownership_rights' => 'nullable|string',
            'warranty' => 'nullable|string',
            'general_terms' => 'nullable|string',
            'status' => 'required|in:draft,sent,accepted,declined,active,completed,terminated',
            'content' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Contract::create($validated);

        return redirect()->route('contracts.index')->with('success', 'Contract created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        $clients = Client::all();
        return view('contracts.edit', compact('contract', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'contract_value' => 'nullable|numeric|min:0',
            'scope_of_work' => 'nullable|string',
            'timeline' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'revisions' => 'nullable|string',
            'ownership_rights' => 'nullable|string',
            'warranty' => 'nullable|string',
            'general_terms' => 'nullable|string',
            'status' => 'required|in:draft,sent,accepted,declined,active,completed,terminated',
            'content' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $contract->update($validated);

        return redirect()->route('contracts.index')->with('success', 'Contract updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('contracts.index')->with('success', 'Contract deleted successfully.');
    }
}
