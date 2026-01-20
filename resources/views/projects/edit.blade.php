@extends('layouts.app')

@section('content')
<h2>Edit Project</h2>

{{-- ERROR MESSAGE --}}
@if ($errors->any())
    <div style="color:red; margin-bottom:10px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('projects.update', $project) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- PROJECT NAME --}}
    <div>
        <label>Project Name</label><br>
        <input type="text"
               name="project_name"
               value="{{ old('project_name', $project->project_name) }}"
               required>
    </div>
    <br>

    {{-- CLIENT --}}
    <div>
        <label>Client</label><br>
        <select name="client_id" required>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}"
                    {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>
    </div>
    <br>

    {{-- FREELANCER --}}
    <div>
        <label>Freelancer</label><br>
        <select name="freelancer_id" required>
            @foreach ($freelancers as $freelancer)
                <option value="{{ $freelancer->id }}"
                    {{ old('freelancer_id', $project->freelancer_id) == $freelancer->id ? 'selected' : '' }}>
                    {{ $freelancer->name }}
                </option>
            @endforeach
        </select>
    </div>
    <br>

    {{-- BUDGET --}}
    <div>
        <label>Budget</label><br>
        <input type="number"
               name="budget"
               min="1000"
               value="{{ old('budget', $project->budget) }}"
               required>
    </div>
    <br>

    {{-- STATUS --}}
    <div>
        <label>Status</label><br>
        <select name="status" {{ $project->status === 'completed' ? 'disabled' : '' }}>
            @foreach (['pending','ongoing','completed','cancelled'] as $status)
                <option value="{{ $status }}"
                    {{ old('status', $project->status) === $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>

        {{-- KIRIM STATUS ASLI JIKA DISABLED --}}
        @if ($project->status === 'completed')
            <input type="hidden" name="status" value="completed">
            <small style="color:gray;">
                Project completed tidak dapat diubah
            </small>
        @endif
    </div>
    <br>

    {{-- ACTION --}}
    <button type="submit"
        {{ $project->status === 'completed' ? 'disabled' : '' }}>
        Update Project
    </button>

    <a href="{{ route('projects.index') }}">Back</a>
</form>
@endsection
