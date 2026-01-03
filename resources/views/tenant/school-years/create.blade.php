@extends('tenant.layouts.app')

@section('content')
<h3>➕ Nouvel année scolaire</h3>

<form method="POST" action="/school-years">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nom de l'année scolaire</label>
        <input type="text"
               name="name"
               class="form-control"
               placeholder="2025-2026"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Découpage de l'année</label>
        <select name="period_type_id" class="form-select" required>
            @foreach($periodTypes as $type)
                <option value="{{ $type->id }}">
                    {{ $type->name }} ({{ $type->period_count }} périodes)
                </option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-primary">
        Créer l'année scolaire
    </button>
</form>

@endsection
