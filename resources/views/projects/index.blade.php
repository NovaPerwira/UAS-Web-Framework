@extends('layouts.app')

@section('content')
<h2>Projects</h2>

<a href="{{ route('projects.create') }}">+ Add Project</a>

@if ($errors->any())
    <div style="color:red; margin:10px 0;">
        {{ $errors->first() }}
    </div>
@endif

@if (session('success'))
    <div style="color:green; margin:10px 0;">
        {{ session('success') }}
    </div>
@endif

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Project Name</th>
        <th>Client</th>
        <th>Freelancer</th>
        <th>Budget</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    @foreach ($projects as $project)
    <tr>
        <td>{{ $project->project_name }}</td>
        <td>{{ $project->client->name }}</td>
        <td>{{ $project->freelancer->name }}</td>
        <td>Rp {{ number_format($project->budget, 0, ',', '.') }}</td>

        {{-- STATUS BADGE --}}
        <td>
            @if ($project->status === 'pending')
                <span style="color:gray;">Pending</span>
            @elseif ($project->status === 'ongoing')
                <span style="color:blue;">Ongoing</span>
            @elseif ($project->status === 'completed')
                <span style="color:green;">Completed</span>
            @elseif ($project->status === 'cancelled')
                <span style="color:red;">Cancelled</span>
            @endif
        </td>

        {{-- ACTION --}}
        <td>
            @if ($project->status !== 'completed')
                <a href="{{ route('projects.edit', $project) }}">Edit</a>

                <form action="{{ route('projects.destroy', $project) }}"
                      method="POST"
                      style="display:inline"
                      onsubmit="return confirm('Yakin ingin mengarsipkan project ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Archive</button>
                </form>
            @else
                <em>Locked</em>
            @endif
        </td>
    </tr>
    @endforeach
</table>
@endsection
