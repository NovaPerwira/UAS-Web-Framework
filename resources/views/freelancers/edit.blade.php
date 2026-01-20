@extends('layouts.app')

@section('content')
<h2>Edit Freelancer</h2>

@if ($errors->any())
    <div class="error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('freelancers.update', $freelancer) }}">
    @csrf
    @method('PUT')

    <label>Name</label>
    <input type="text" name="name"
           value="{{ old('name', $freelancer->name) }}">

    <label>Skill</label>
    <input type="text" name="skill"
           value="{{ old('skill', $freelancer->skill) }}">

    <button type="submit">Update</button>
</form>
@endsection
