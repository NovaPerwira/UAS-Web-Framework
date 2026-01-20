@extends('layouts.app')

@section('content')
<h2>Edit Client</h2>

@if ($errors->any())
    <div class="error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('clients.update', $client) }}">
    @csrf
    @method('PUT')

    <label>Name</label>
    <input type="text" name="name"
           value="{{ old('name', $client->name) }}">

    <label>Email</label>
    <input type="email" name="email"
           value="{{ old('email', $client->email) }}">

    <button type="submit">Update</button>
</form>
@endsection
