@extends('layouts.app')

@section('content')
<h2>Add Freelancer</h2>

@if ($errors->any())
    <div class="error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('freelancers.store') }}">
    @csrf

    <label>Name</label>
    <input type="text" name="name" value="{{ old('name') }}">

    <label>Skill</label>
    <input type="text" name="skill" value="{{ old('skill') }}">

    <button type="submit">Save</button>
</form>
@endsection
