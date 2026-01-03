@extends('tenant.layouts.app')

@section('content')
<h3>Modifier l’utilisateur</h3>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="/users/{{ $user->id }}">
@csrf
@method('PUT')

<div class="mb-3">
    <label>Nom</label>
    <input name="name" class="form-control" value="{{ $user->name }}" required>
</div>

<div class="mb-3">
    <label>Email</label>
    <input name="email" type="email" class="form-control" value="{{ $user->email }}" required>
</div>

<div class="mb-3">
    <label>Rôle</label>
    <select name="role" class="form-control">
        <option value="admin" @selected($user->role === 'admin')>Admin</option>
        <option value="staff" @selected($user->role === 'staff')>Staff</option>
        <option value="teacher" @selected($user->role === 'teacher')>Professeur</option>
    </select>
</div>

<button class="btn btn-primary">Mettre à jour</button>
<a href="/users" class="btn btn-secondary">Annuler</a>
</form>
@endsection
