<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduSaaS – La plateforme intelligente de gestion scolaire</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { scroll-behavior: smooth; }
        .hero {
            background: linear-gradient(120deg, #0d6efd, #6610f2);
            color: #fff;
            padding: 140px 0 120px;
        }
        .hero h1 { font-weight: 800; }
        .hero p { max-width: 750px; margin: auto; }
        .badge-saas { background: rgba(255,255,255,.15); }
        .feature-icon {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: linear-gradient(135deg,#0d6efd,#6610f2);
            color: #fff;
            font-size: 26px;
        }
        .section-title { font-weight: 800; }
        .pricing {
            border: 1px solid #eaeaea;
            border-radius: 16px;
            transition: all .3s;
        }
        .pricing:hover { transform: translateY(-6px); box-shadow: 0 15px 35px rgba(0,0,0,.1); }
        .gradient-text {
            background: linear-gradient(90deg,#0d6efd,#6610f2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .error-border {
            border-color: #dc3545 !important;
        }
        .error-text {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold gradient-text" href="#">EduSaaS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#features">Fonctionnalités</a></li>
                <li class="nav-item"><a class="nav-link" href="#schoolyear">Année scolaire</a></li>
                <li class="nav-item"><a class="nav-link" href="#security">Sécurité</a></li>
                <li class="nav-item"><a class="nav-link" href="#pricing">Tarifs</a></li>
                <li class="nav-item"><a class="btn btn-primary ms-3" href="/inscription">Créer mon école</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero text-center">
    <div class="container animate__animated animate__fadeIn">
        <span class="badge badge-saas mb-3">Solution SaaS de gestion scolaire nouvelle génération</span>
        <h1 class="display-4 mt-3">Pilotez votre école avec puissance et sérénité</h1>
        <p class="lead mt-4">
            EduSaaS accompagne les établissements scolaires dans la digitalisation complète de leur gestion :
            élèves, enseignants, classes, notes, années scolaires et bien plus.
        </p>
        <div class="mt-5">
            <a href="/inscription" class="btn btn-light btn-lg fw-bold">Démarrer gratuitement</a>
            <a href="#features" class="btn btn-outline-light btn-lg ms-2">Voir les fonctionnalités</a>
        </div>
    </div>
</section>

<!-- Affichage des messages de succès/erreur -->
@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

@if ($errors->any())
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Erreurs de validation :</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

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

<!-- PRICING -->
<section id="pricing" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Tarification simple et transparente</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="pricing p-4 text-center">
                    <h5>Essentiel</h5>
                    <h2 class="fw-bold">Gratuit</h2>
                    <p>Jusqu'à 50 élèves</p>
                    <a href="/inscription" class="btn btn-outline-primary">Commencer</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pricing p-4 text-center shadow">
                    <h5>Pro</h5>
                    <h2 class="fw-bold">Payant</h2>
                    <p>Élèves illimités + support prioritaire</p>
                    <a href="/inscription" class="btn btn-primary">Choisir</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <p class="mb-0">© 2025 EduSaaS – Solution SaaS de gestion scolaire</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

<!-- MODAL INSCRIPTION ECOLE -->
<div class="modal fade" id="registerModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Créer votre école</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('inscription') }}" enctype="multipart/form-data" id="registerForm">
        @csrf
        <div class="modal-body">
          <!-- Affichage des erreurs dans la modale -->
          @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>❌ Erreurs :</strong>
            <ul class="mb-0 mt-2">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          @endif

          <h6 class="fw-bold mb-3">Informations sur l'établissement</h6>
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label">Nom de l'école *</label>
              <input type="text" name="school_name" class="form-control @error('school_name') is-invalid @enderror" 
                     value="{{ old('school_name') }}" required>
              @error('school_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Sous-domaine *</label>
              <div class="input-group">
                <input type="text" name="subdomain" class="form-control @error('subdomain') is-invalid @enderror" 
                       value="{{ old('subdomain') }}" placeholder="mon-ecole" required>
                <span class="input-group-text">.site.test</span>
              </div>
              @error('subdomain')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
              <small class="text-muted">Utilisez uniquement des lettres, chiffres et tirets</small>
            </div>
            <div class="col-md-6">
              <label class="form-label">Adresse de l'école *</label>
              <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                     value="{{ old('address') }}" required>
              @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Téléphone *</label>
              <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                     value="{{ old('phone') }}" required>
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-12">
              <label class="form-label">Logo de l'école (optionnel)</label>
              <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
              @error('logo')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <h6 class="fw-bold mb-3">Informations administrateur</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Prénom *</label>
              <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                     value="{{ old('first_name') }}" required>
              @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Nom *</label>
              <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                     value="{{ old('last_name') }}" required>
              @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-12">
              <label class="form-label">Email administrateur *</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                     value="{{ old('email') }}" required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- <div class="alert alert-info mt-4">
            🔐 Les accès administrateur seront générés automatiquement et envoyés par email.
          </div> -->
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-lg">Créer mon espace école</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Ouvrir la modale quand on clique sur "Créer mon école"
const modalLinks = document.querySelectorAll('a[href="/inscription"]');
modalLinks.forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();
    const modal = new bootstrap.Modal(document.getElementById('registerModal'));
    modal.show();
  });
});

// Si le formulaire a des erreurs, garder la modale ouverte
@if ($errors->any())
  document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('registerModal'));
    modal.show();
  });
@endif

// Validation côté client pour le sous-domaine
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
  const subdomain = document.querySelector('input[name="subdomain"]').value;
  if (subdomain && !/^[a-z0-9-]+$/.test(subdomain)) {
    e.preventDefault();
    alert('Le sous-domaine ne peut contenir que des lettres minuscules, des chiffres et des tirets.');
  }
});
</script>
</html>