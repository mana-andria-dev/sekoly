@extends('layouts.app')

@section('title', 'Gestion scolairemmmmm')

@section('content')

<!-- HERO -->
<section class="hero text-center">

    @if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="container animate__animated animate__fadeIn">
        <h1 class="display-4 mt-3">Pilotez votre école avec puissance et sérénité</h1>
        <p class="lead mt-4">La gestion d’école devient enfin simple, fiable et intelligente</p>
        <p class="lead mt-4">
            EduSaaS accompagne les établissements scolaires dans la digitalisation complète de leur gestion :
            élèves, enseignants, classes, notes, années scolaires et bien plus.
        </p>
        <div class="mt-5">
            <a href="/register" class="btn btn-light btn-lg fw-bold">Démarrer gratuitement</a>
            <a href="#features" class="btn btn-outline-light btn-lg ms-2">Voir les fonctionnalités</a>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Une plateforme pensée pour les écoles modernes</h2>
            <p class="text-muted">Tout ce dont votre établissement a besoin, dans un seul outil</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-icon mb-3">🎓</div>
                <h5>Gestion des élèves</h5>
                <p>Inscriptions, dossiers scolaires, suivi académique, historique par année scolaire.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon mb-3">🏫</div>
                <h5>Classes & niveaux</h5>
                <p>Organisation claire par classe, niveau, filière et année scolaire.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon mb-3">👨‍🏫</div>
                <h5>Enseignants</h5>
                <p>Affectation aux classes, matières, suivi pédagogique et communication interne.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon mb-3">📝</div>
                <h5>Notes & évaluations</h5>
                <p>Saisie des notes, calculs automatiques, bulletins et statistiques.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon mb-3">📊</div>
                <h5>Statistiques</h5>
                <p>Tableaux de bord clairs pour une prise de décision rapide et efficace.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon mb-3">🔔</div>
                <h5>Communication</h5>
                <p>Notifications, SMS, emails aux parents et enseignants.</p>
            </div>
        </div>
    </div>
</section>

<!-- SCHOOL YEAR -->
<section id="schoolyear" class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="section-title">Gestion intelligente des années scolaires</h2>
                <p class="mt-3">
                    Chaque donnée est strictement liée à une année scolaire. EduSaaS garantit une continuité parfaite
                    d’une année à l’autre, sans jamais perdre l’historique.
                </p>
                <ul class="mt-3">
                    <li>Création automatique de la première année</li>
                    <li>Changement d’année en un clic</li>
                    <li>Historique complet multi-années</li>
                    <li>Données isolées par année scolaire</li>
                </ul>
            </div>
            <div class="col-md-6 text-center">
                <img src="https://dummyimage.com/500x350/e9ecef/000&text=Gestion+Ann%C3%A9e+Scolaire" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- SECURITY -->
<section id="security" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Sécurité & fiabilité SaaS</h2>
            <p class="text-muted">Vos données sont protégées et isolées</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4"><strong>🔒 Isolation par école</strong><p>Données totalement séparées par sous-domaine.</p></div>
            <div class="col-md-4"><strong>☁️ Architecture SaaS</strong><p>Infrastructure scalable et évolutive.</p></div>
            <div class="col-md-4"><strong>📁 Sauvegardes</strong><p>Sauvegardes régulières et récupération rapide.</p></div>
        </div>
    </div>
</section>

@endsection