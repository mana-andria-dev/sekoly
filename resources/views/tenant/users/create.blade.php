@extends('tenant.layouts.app')

@section('content')
<h3>Ajouter un utilisateur</h3>

<form method="POST" action="/users">
@csrf

<div class="mb-3">
    <label>prénom</label>
    <input name="first_name" class="form-control" required>
</div>

<div class="mb-3">
    <label>Nom</label>
    <input name="last_name" class="form-control" required>
</div>

<!-- <div class="mb-3">
    <label>Nom</label>
    <input name="name" class="form-control" required>
</div> -->

<div class="mb-3">
    <label>Email</label>
    <input name="email" type="email" class="form-control" required>
</div>

<div class="mb-3">
    <label>Rôle</label>
    <select name="role" class="form-control">
        <option value="admin">Admin</option>
        <option value="staff">Staff</option>
        <option value="teacher">Professeur</option>
    </select>
</div>

<button class="btn btn-success">Créer</button>
</form>
@endsection
