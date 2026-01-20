<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\Client;
use App\Models\Freelancer;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['client', 'freelancer'])->get();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $freelancers = Freelancer::all();

        return view('projects.create', compact('clients', 'freelancers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'client_id' => 'required|exists:clients,id',
        'freelancer_id' => 'required|exists:freelancers,id',
        'project_name' => 'required|string|min:3',
        'budget' => 'required|integer|min:1000',
        'status' => 'required|in:pending,ongoing,completed,cancelled',
    ]);

    Project::create($validated);

    return redirect()
        ->route('projects.index')
        ->with('success', 'Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        if ($project->status === 'completed') {
            return redirect()->route('projects.index')
                ->withErrors('Project yang sudah selesai tidak bisa diedit');
        }

        $clients = Client::all();
        $freelancers = Freelancer::all();

        return view('projects.edit', compact('project', 'clients', 'freelancers'));
    

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if ($project->status === 'completed') {
            return redirect()->route('projects.index')
                ->withErrors('Project completed tidak bisa diubah');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'freelancer_id' => 'required|exists:freelancers,id',
            'project_name' => 'required|string|min:3',
            'budget' => 'required|integer|min:1000',
        ]);

        if ($project->status === 'completed' && $validated['status'] !== 'completed') {
            return back()->withErrors('Status completed tidak bisa diubah');
        }

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete(); // soft delete

        return redirect()->route('projects.index')
            ->with('success', 'Project archived successfully');
    }
}
