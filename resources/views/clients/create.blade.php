@extends('layouts.app')

@section('content')
<h2>Add Client</h2>

<form method="POST" action="{{ route('clients.store') }}">
@csrf
<input name="name" placeholder="Name">
<input name="email" placeholder="Email">
<button>Save</button>
</form>
@endsection
