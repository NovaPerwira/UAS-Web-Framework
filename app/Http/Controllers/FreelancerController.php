<?php

namespace App\Http\Controllers;

use App\Models\Freelancer;
use Illuminate\Http\Request;

class FreelancerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $freelancers = Freelancer::all();
        return view('freelancers.index', compact('freelancers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('freelancers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|min:3',
            'skill' => 'required|min:3'
        ]);

        Freelancer::create($request->all());
        return redirect()->route('freelancers.index');
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
    public function edit(Freelancer $freelancer)
    {
        return view('freelancers.edit', compact('freelancer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Freelancer $freelancer)
    {
        $request->validate([
            'name'  => 'required|min:3',
            'skill' => 'required|min:3'
        ]);

        $freelancer->update($request->all());
        return redirect()->route('freelancers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Freelancer $freelancer)
    {
        $freelancer->delete();
        return redirect()->route('freelancers.index');
    }
}
