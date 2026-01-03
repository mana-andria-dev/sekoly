@extends('tenant.layouts.app')

@section('content')
<h3>Utilisateurs</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="/users/create" class="btn btn-primary mb-3">➕ Ajouter</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td class="text-end">
                <a href="/users/{{ $user->id }}/edit" class="btn btn-sm btn-warning">✏️</a>
                <form action="/users/{{ $user->id }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">🗑️</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
