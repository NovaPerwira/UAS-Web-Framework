@extends('layouts.app')

@section('content')
<h2>Clients</h2>
<a href="{{ route('clients.create') }}">+ Add Client</a>

<table>
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Action</th>
</tr>

@foreach ($clients as $client)
<tr>
    <td>{{ $client->name }}</td>
    <td>{{ $client->email }}</td>
    <td>
        <a href="{{ route('clients.edit', $client) }}">Edit</a>

        <form action="{{ route('clients.destroy', $client) }}"
              method="POST"
              style="display:inline"
              onsubmit="return confirm('Yakin ingin menghapus client ini?')">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
@endforeach
</table>
@endsection
