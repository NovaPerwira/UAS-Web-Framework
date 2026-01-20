@extends('layouts.app')

@section('content')
<h2>Freelancers</h2>
<a href="{{ route('freelancers.create') }}">+ Add Freelancer</a>

<table>
<tr>
    <th>Name</th>
    <th>Skill</th>
    <th>Action</th>
</tr>

@foreach ($freelancers as $freelancer)
<tr>
    <td>{{ $freelancer->name }}</td>
    <td>{{ $freelancer->skill }}</td>
    <td>
        <a href="{{ route('freelancers.edit', $freelancer) }}">Edit</a>

        <form action="{{ route('freelancers.destroy', $freelancer) }}"
              method="POST"
              style="display:inline"
              onsubmit="return confirm('Yakin ingin menghapus freelancer ini?')">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
@endforeach
</table>
@endsection
