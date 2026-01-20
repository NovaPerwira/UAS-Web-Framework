@extends('layouts.app')

@section('content')
<h2>Add Project</h2>

{{-- ERROR VALIDATION --}}
@if ($errors->any())
    <div style="color:red; margin-bottom:10px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('projects.store') }}" method="POST">
    @csrf

    {{-- PROJECT NAME --}}
    <div>
        <label>Project Name</label><br>
        <input type="text"
               name="project_name"
               value="{{ old('project_name') }}"
               required>
    </div>
    <br>

    {{-- CLIENT --}}
    <div>
        <label>Client</label><br>
        <select name="client_id" required>
            <option value="">-- Select Client --</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}"
                    {{ old('client_id') == $client->id ? 'selected' : '' }}>
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
            <option value="">-- Select Freelancer --</option>
            @foreach ($freelancers as $freelancer)
                <option value="{{ $freelancer->id }}"
                    {{ old('freelancer_id') == $freelancer->id ? 'selected' : '' }}>
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
               value="{{ old('budget') }}"
               min="1000"
               required>
    </div>
    <br>

    {{-- STATUS --}}
    <div>
        <label>Status</label><br>
        <select name="status" required>
            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                Pending
            </option>
            <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>
                Ongoing
            </option>
            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                Completed
            </option>
            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                Cancelled
            </option>
        </select>
    </div>
    <br>

    <button type="submit">Save Project</button>
    <a href="{{ route('projects.index') }}">Cancel</a>
</form>
@endsection
