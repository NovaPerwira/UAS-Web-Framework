<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RelationshipController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = \App\Models\Client::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhereHas('projects', function ($q) use ($search) {
                    $q->where('project_name', 'like', "%{$search}%");
                });
        }

        $clients = $query->with([
            'projects' => function ($q) {
                // Ensure recursive relationships are also loaded if any
                $q->with(['contracts', 'invoices', 'freelancer']);
            },
            'contracts',
            'invoices'
        ])
            ->orderByDesc('created_at')
            ->paginate(5)
            ->withQueryString();

        return view('relations.index', compact('clients'));
    }
}
