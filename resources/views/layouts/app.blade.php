<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sekoly - Plateforme intelligente de gestion scolaire</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/home.css') }}">
</head>
<body>

<!-- Modern Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/logo.png') }}" alt="Sekoly Logo" height="40" class="d-inline-block align-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link fw-semibold" href="#features">Fonctionnalités</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="#schoolyear">Année scolaire</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="#security">Sécurité</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="#pricing">Tarifs</a></li>
                <li class="nav-item ms-lg-3">
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#registerModal">
                        Créer mon école
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Modern Hero Section -->
<section class="hero">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-7 text-white" data-aos="fade-up">
                <div class="slogan-hero">
                    <span>🎓</span>
                    <span class="slogan-hero-text">Ô i ô! Ndeha hianatra izahay! 🇲🇬</span>
                </div>
                <h1 class="display-4 fw-bold">Une école mieux organisée, des élèves mieux accompagnés</h1>
                <p class="lead mb-4 opacity-90">La plateforme intelligente pour gérer votre école simplement et efficacement.</p>
                <p>Gérez notes, élèves, frais scolaires et bulletins dans une seule plateforme.</p>
                <div class="d-flex align-items-center gap-2 mb-4 text-white-50">
                    <i class="bi bi-phone"></i>
                    <small>Accessible partout depuis un ordinateur ou un téléphone.</small>
                </div>                
                <div class="hero-buttons d-flex gap-3 flex-wrap">
                    <button class="btn btn-primary btn-lg rounded-pill" data-bs-toggle="modal" data-bs-target="#registerModal">
                        Démarrer gratuitement
                    </button>
                    <button class="btn btn-outline-light btn-lg rounded-pill" data-bs-toggle="modal" data-bs-target="#registerModal">
                        Créer mon école
                    </button>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left" data-aos-delay="200">
                <img src="https://placehold.co/500x500/ffffff/667eea?text=SCHOOL+MANAGEMENT&font=montserrat" alt="Hero" class="img-fluid">
            </div>
        </div>
    </div>
</section>

@if(session('success'))
<!-- Modale de succès -->
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-4 text-center">
        <div class="mb-3">
          <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        </div>
        <h3 class="fw-bold text-success mb-3">✅ Inscription enregistrée !</h3>
        <div class="text-start bg-light p-3 rounded-3 mb-3">
          {!! session('success') !!}
        </div>
        <div class="mt-3">
          <button type="button" class="btn btn-primary rounded-pill px-5 py-2" data-bs-dismiss="modal">
            <i class="bi bi-check-lg"></i> J'ai compris
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Attendre un tout petit peu pour être sûr que le DOM est prêt
        setTimeout(function() {
            var modalEl = document.getElementById('successModal');
            if (modalEl) {
                var successModal = new bootstrap.Modal(modalEl, {
                    backdrop: 'static',
                    keyboard: false
                });
                successModal.show();
            }
        }, 100);
    });
</script>
@endif

@if(session('error'))
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if ($errors->any())
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        <strong>Erreurs de validation :</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

<!-- Features Section -->
<section id="features" class="section-padding">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Une plateforme pensée pour les écoles modernes</h2>
            <p class="section-subtitle">Tout ce dont votre établissement a besoin, dans un seul outil</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-card">
                    <div class="feature-icon">🎓</div>
                    <h5 class="fw-bold mb-3">Gestion des élèves</h5>
                    <p class="text-muted">Inscriptions, dossiers scolaires, suivi académique, historique par année scolaire.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon">🏫</div>
                    <h5 class="fw-bold mb-3">Classes & niveaux</h5>
                    <p class="text-muted">Organisation claire par classe, niveau, filière et année scolaire.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon">👨‍🏫</div>
                    <h5 class="fw-bold mb-3">Enseignants</h5>
                    <p class="text-muted">Affectation aux classes, matières, suivi pédagogique et communication interne.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <h5 class="fw-bold mb-3">Notes & évaluations</h5>
                    <p class="text-muted">Saisie des notes, calculs automatiques, bulletins et statistiques.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h5 class="fw-bold mb-3">Statistiques</h5>
                    <p class="text-muted">Tableaux de bord clairs pour une prise de décision rapide et efficace.</p>
                    <small class="text-muted">⭐ Disponible sur l'offre Premium uniquement</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-card">
                    <div class="feature-icon">🔔</div>
                    <h5 class="fw-bold mb-3">Communication</h5>
                    <p class="text-muted">Notifications, SMS, emails aux parents et enseignants.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- School Year Section -->
<section id="schoolyear" class="school-year-section section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <h2 class="section-title">Gestion intelligente des années scolaires</h2>
                <p class="lead text-muted mb-4">Chaque donnée est strictement liée à une année scolaire. Sekoly garantit une continuité parfaite d'une année à l'autre.</p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="year-stats">
                            <i class="bi bi-calendar-check fs-1 text-primary"></i>
                            <h3 class="fw-bold mt-2">100%</h3>
                            <p class="text-muted small">Continuité des données</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="year-stats">
                            <i class="bi bi-clock-history fs-1 text-primary"></i>
                            <h3 class="fw-bold mt-2">Illimité</h3>
                            <p class="text-muted small">Historique conservé</p>
                        </div>
                    </div>
                </div>
                <ul class="mt-4 list-unstyled">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Création automatique de la première année</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Changement d'année en un clic</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Historique complet multi-années</li>
                    <li><i class="bi bi-check-circle-fill text-primary me-2"></i>Données isolées par année scolaire</li>
                </ul>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="https://placehold.co/600x400/ffffff/667eea?text=School+Year+Management&font=montserrat" class="img-fluid rounded-4 shadow-lg" alt="Gestion année scolaire">
            </div>
        </div>
    </div>
</section>

<!-- Security Section -->
<section id="security" class="section-padding">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Sécurité & fiabilité SaaS</h2>
            <p class="section-subtitle">Vos données sont protégées et isolées</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="security-card">
                    <i class="bi bi-shield-lock fs-1 text-primary mb-3 d-block"></i>
                    <h5 class="fw-bold">🔒 Isolation par école</h5>
                    <p class="text-muted">Données totalement séparées par sous-domaine.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="security-card">
                    <i class="bi bi-cloud fs-1 text-primary mb-3 d-block"></i>
                    <h5 class="fw-bold">☁️ Architecture SaaS</h5>
                    <p class="text-muted">Infrastructure scalable et évolutive.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="security-card">
                    <i class="bi bi-database fs-1 text-primary mb-3 d-block"></i>
                    <h5 class="fw-bold">📁 Sauvegardes</h5>
                    <p class="text-muted">Sauvegardes régulières et récupération rapide.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section - CORRIGÉE avec seulement 2 offres -->
<section id="pricing" class="section-padding bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Tarification simple et transparente</h2>
            <p class="section-subtitle">Choisissez la formule qui correspond à vos besoins</p>
        </div>
        <div class="row g-4 justify-content-center">
            <!-- Offre Gratuite -->
            <div class="col-md-6 col-lg-5" data-aos="fade-up" data-aos-delay="0">
                <div class="pricing-card">
                    <h3 class="fw-bold">Essentiel</h3>
                    <div class="price mt-3">Gratuit</div>
                    <p class="text-muted">Pour les petites structures</p>
                    <hr>
                    <ul class="list-unstyled text-start mt-4">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Jusqu'à 50 élèves</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Jusqu'à 5 professeurs</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Gestion des élèves & classes</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Notes et évaluations basiques</li>
                        <li class="mb-2 text-muted"><i class="bi bi-x-circle-fill me-2"></i>Statistiques avancées</li>
                        <li class="text-muted"><i class="bi bi-envelope me-2"></i>Support par email</li>
                    </ul>
                    <button class="btn btn-outline-primary rounded-pill mt-4 w-100" data-bs-toggle="modal" data-bs-target="#registerModal">
                        Commencer gratuitement
                    </button>
                </div>
            </div>

            <!-- Offre Premium 99 000 Ar -->
            <div class="col-md-6 col-lg-5" data-aos="fade-up" data-aos-delay="100">
                <div class="pricing-card featured">
                    <span class="badge bg-warning text-dark position-absolute top-0 start-50 translate-middle rounded-pill px-3">⭐ Populaire</span>
                    <h3 class="fw-bold text-white">Premium</h3>
                    <div class="price text-white mt-3">99 000 Ar <span class="fs-6">/ mois</span></div>
                    <p class="text-white-50">Pour les écoles en pleine croissance</p>
                    <hr class="bg-white-50">
                    <ul class="list-unstyled text-start mt-4 text-white">
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Elèves illimités</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>professeurs illimités</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Tout de l'offre Essentiel</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Statistiques et rapports avancés</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Support prioritaire</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Export de données (PDF, Excel)</li>
                        <!-- <li><i class="bi bi-check-circle-fill me-2"></i>API d'intégration</li> -->
                    </ul>
                    <button class="btn btn-light rounded-pill mt-4 w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#registerModal">
                        Choisir Premium
                    </button>
                </div>
            </div>
        </div>

        <!-- Note explicative -->
        <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="200">
            <p class="text-muted">
                <i class="bi bi-shield-check me-1"></i> 
                Tous les abonnements incluent la sécurité des données et les mises à jour gratuites.
                <br>Pas de frais cachés, résiliation à tout moment.
            </p>
        </div>
    </div>
</section>

<!-- CTA Section -->
<div class="container">
    <div class="cta-section" data-aos="zoom-in">
        <h2 class="text-white mb-3 fw-bold">Prêt à digitaliser votre école ?</h2>
        <p class="text-white-50 mb-4">Rejoignez des centaines d'établissements qui nous font confiance</p>
        <button class="btn btn-light rounded-pill btn-lg px-5" data-bs-toggle="modal" data-bs-target="#registerModal">
            Créer mon école maintenant
        </button>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Sekoly Logo" height="35" class="mb-2">
                <p class="text-white-250">La plateforme intelligente de gestion scolaire</p>
            </div>
            <div class="col-md-2 mb-4">
                <h6 class="fw-bold mb-3">Produit</h6>
                <ul class="list-unstyled">
                    <li><a href="#features">Fonctionnalités</a></li>
                    <li><a href="#pricing">Tarifs</a></li>
                    <li><a href="#security">Sécurité</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h6 class="fw-bold mb-3">Support</h6>
                <ul class="list-unstyled">
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h6 class="fw-bold mb-3">Légal</h6>
                <ul class="list-unstyled">
                    <li><a href="#">CGU</a></li>
                    <li><a href="#">Confidentialité</a></li>
                    <li><a href="#">Mentions légales</a></li>
                </ul>
            </div>
        </div>
        <hr class="bg-white-50">
        <div class="text-center">
            <p class="mb-0 text-white-50">© 2026 Sekoly – Solution SaaS de gestion scolaire</p>
        </div>
    </div>
</footer>

<!-- MODAL INSCRIPTION MODERNE - Vérifiée et corrigée -->
<div class="modal fade" id="registerModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white">
            <i class="bi bi-magic"></i> Créer votre école en 3 étapes
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <form method="POST" action="{{ route('inscription') }}" enctype="multipart/form-data" id="registerForm">
        @csrf
        <div class="modal-body" style="padding: 2rem;">
          <!-- Timeline -->
          <div class="form-timeline">
            <div class="timeline-step" id="step1Indicator">
              <div class="step-icon">1</div>
              <div class="step-label">ÉTABLISSEMENT</div>
            </div>
            <div class="timeline-step" id="step2Indicator">
              <div class="step-icon">2</div>
              <div class="step-label">ABONNEMENT</div>
            </div>
            <div class="timeline-step" id="step3Indicator">
              <div class="step-icon">3</div>
              <div class="step-label">ADMINISTRATEUR</div>
            </div>
          </div>

          <!-- ÉTAPE 1 -->
          <div id="step1" class="step-content">
            <div class="mb-3">
              <label class="form-label fw-bold">Nom de l'école *</label>
              <input type="text" name="school_name" class="modern-input form-control @error('school_name') is-invalid @enderror" 
                     value="{{ old('school_name') }}" required>
              @error('school_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Sous-domaine *</label>
              <div class="input-group">
                <input type="text" name="subdomain" class="modern-input form-control @error('subdomain') is-invalid @enderror" 
                       value="{{ old('subdomain') }}" readonly required>
                <span class="input-group-text">.site.test</span>
              </div>
              @error('subdomain')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold">Adresse *</label>
                  <input type="text" name="address" class="modern-input form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" required>
                  @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold">Téléphone *</label>
                  <input type="tel" name="phone" class="modern-input form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                  @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Logo (optionnel)</label>
              <input type="file" name="logo" class="modern-input form-control" accept="image/*">
              <small class="text-muted">Format JPG, PNG (max 2MB)</small>
            </div>
          </div>

          <!-- ÉTAPE 2 - VÉRIFIÉE : seulement 2 offres -->
          <div id="step2" class="step-content" style="display: none;">
            <div class="row g-4 mb-4 justify-content-center">
              <!-- Offre Gratuite -->
              <div class="col-md-6">
                <div class="plan-card text-center" data-plan="basic">
                  <div class="display-4">📘</div>
                  <h5 class="fw-bold mt-2">Essentiel</h5>
                  <div class="h4 text-primary">Gratuit</div>
                  <hr>
                  <ul class="list-unstyled text-start small">
                    <li>✓ Jusqu'à 50 élèves</li>
                    <li>✓ Jusqu'à 5 professeurs</li>
                    <li>✓ Gestion des notes et classes</li>
                    <li>✓ Support par email</li>
                  </ul>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="plan" value="basic" id="planBasic">
                    <label class="form-check-label" for="planBasic">Sélectionner</label>
                  </div>
                </div>
              </div>

              <!-- Offre Premium 99 000 Ar -->
              <div class="col-md-6">
                <div class="plan-card text-center position-relative" data-plan="premium">
                  <span class="badge bg-primary position-absolute top-0 end-0 m-2 rounded-pill">⭐ Populaire</span>
                  <div class="display-4">🚀</div>
                  <h5 class="fw-bold mt-2 text-primary">Premium</h5>
                  <div class="h4 text-primary">99 000 Ar <small>/mois</small></div>
                  <hr>
                  <ul class="list-unstyled text-start small">
                    <li>✓ Jusqu'à 500 élèves</li>
                    <li>✓ Jusqu'à 50 professeurs</li>
                    <li>✓ Statistiques avancées</li>
                    <li>✓ Support prioritaire</li>
                    <li>✓ Export de données</li>
                  </ul>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="plan" value="premium" id="planPremium">
                    <label class="form-check-label" for="planPremium">Sélectionner</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-6">
                <label class="form-label fw-bold">Période d'abonnement *</label>
                <select name="subscription_period" class="modern-input form-select" required>
                  <option value="monthly">📆 Mensuel</option>
                  <option value="quarterly">📅 Trimestriel (3 mois)</option>
                  <option value="yearly">🎉 Annuel (12 mois - 2 mois offerts)</option>
                </select>
                <small class="text-muted" id="periodDiscount"></small>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold">Montant total</label>
                <div class="modern-input bg-light fw-bold text-primary" id="totalAmount">0 Ar</div>
                <small class="text-muted" id="priceDetail">Offre gratuite</small>
              </div>
            </div>
          </div>

          <!-- ÉTAPE 3 -->
          <div id="step3" class="step-content" style="display: none;">
            <div class="alert alert-info">
              <i class="bi bi-info-circle-fill"></i> 
              Vous allez créer le compte administrateur principal de votre école.
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold">Prénom *</label>
                  <input type="text" name="first_name" class="modern-input form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                  @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold">Nom *</label>
                  <input type="text" name="last_name" class="modern-input form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                  @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Email professionnel *</label>
              <input type="email" name="email" class="modern-input form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Mot de passe *</label>
              <input type="password" name="password" class="modern-input form-control" required>
              <small class="text-muted">Minimum 8 caractères</small>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Confirmer le mot de passe *</label>
              <input type="password" name="password_confirmation" class="modern-input form-control" required>
            </div>
          </div>
        </div>

        <div class="modal-footer" style="border: none; padding: 0 2rem 2rem;">
          <button type="button" class="btn btn-secondary rounded-pill px-4" id="prevBtn" style="display: none;">← Précédent</button>
          <button type="button" class="btn btn-primary rounded-pill px-4" id="nextBtn">Suivant →</button>
          <button type="submit" class="btn btn-success rounded-pill px-4" id="submitBtn" style="display: none;">✅ Créer mon école</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
// Initialize AOS
AOS.init({
    duration: 800,
    once: true,
    offset: 100
});

// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Modal steps
let currentStep = 1;
const step1 = document.getElementById('step1');
const step2 = document.getElementById('step2');
const step3 = document.getElementById('step3');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const submitBtn = document.getElementById('submitBtn');
const step1Indicator = document.getElementById('step1Indicator');
const step2Indicator = document.getElementById('step2Indicator');
const step3Indicator = document.getElementById('step3Indicator');

function updateSteps() {
    step1.style.display = currentStep === 1 ? 'block' : 'none';
    step2.style.display = currentStep === 2 ? 'block' : 'none';
    step3.style.display = currentStep === 3 ? 'block' : 'none';
    
    [step1Indicator, step2Indicator, step3Indicator].forEach((indicator, idx) => {
        const stepNum = idx + 1;
        if (stepNum < currentStep) {
            indicator.classList.add('completed');
            indicator.classList.remove('active');
        } else if (stepNum === currentStep) {
            indicator.classList.add('active');
            indicator.classList.remove('completed');
        } else {
            indicator.classList.remove('active', 'completed');
        }
    });
    
    prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
    nextBtn.style.display = currentStep < 3 ? 'inline-block' : 'none';
    submitBtn.style.display = currentStep === 3 ? 'inline-block' : 'none';
}

function validateStep1() {
    const schoolName = document.querySelector('input[name="school_name"]');
    const subdomain = document.querySelector('input[name="subdomain"]');
    if (!schoolName?.value.trim()) { alert('Veuillez saisir le nom de l\'école'); return false; }
    if (!subdomain?.value.trim()) { alert('Veuillez saisir un sous-domaine'); return false; }
    if (!/^[a-z0-9-]+$/.test(subdomain.value)) { alert('Sous-domaine invalide (minuscules, chiffres, tirets uniquement)'); return false; }
    return true;
}

function validateStep2() {
    const selectedPlan = document.querySelector('input[name="plan"]:checked');
    if (!selectedPlan) { alert('Veuillez choisir une formule d\'abonnement'); return false; }
    return true;
}

function validateStep3() {
    const password = document.querySelector('input[name="password"]');
    const passwordConfirm = document.querySelector('input[name="password_confirmation"]');
    if (!password?.value) { alert('Veuillez saisir un mot de passe'); return false; }
    if (password.value.length < 8) { alert('Le mot de passe doit contenir au moins 8 caractères'); return false; }
    if (password.value !== passwordConfirm?.value) { alert('Les mots de passe ne correspondent pas'); return false; }
    return true;
}

nextBtn?.addEventListener('click', () => {
    if (currentStep === 1 && !validateStep1()) return;
    if (currentStep === 2 && !validateStep2()) return;
    if (currentStep === 3 && !validateStep3()) return;
    currentStep++;
    updateSteps();
});

prevBtn?.addEventListener('click', () => {
    currentStep--;
    updateSteps();
});

updateSteps();

// Plan selection avec mise à jour du prix
const planCards = document.querySelectorAll('.plan-card');
const periodSelect = document.querySelector('[name="subscription_period"]');
const totalAmountSpan = document.getElementById('totalAmount');
const priceDetailSpan = document.getElementById('priceDetail');

const prices = {
    basic: { 
        monthly: 0, 
        quarterly: 0, 
        yearly: 0,
        detail: { monthly: "Gratuit", quarterly: "Gratuit", yearly: "Gratuit" }
    },
    premium: { 
        monthly: 99000, 
        quarterly: 99000 * 3, 
        yearly: 99000 * 10, // 2 mois offerts sur l'annuel
        detail: { 
            monthly: "99 000 Ar/mois", 
            quarterly: "297 000 Ar (équivalent à 99 000 Ar/mois)", 
            yearly: "990 000 Ar (2 mois gratuits - 82 500 Ar/mois)" 
        }
    }
};

function updateTotalAmount() {
    const selectedPlan = document.querySelector('input[name="plan"]:checked');
    if (selectedPlan && periodSelect) {
        const plan = selectedPlan.value;
        const period = periodSelect.value;
        const amount = prices[plan][period];
        totalAmountSpan.textContent = new Intl.NumberFormat('fr-MG').format(amount) + ' Ar';
        if (priceDetailSpan) {
            priceDetailSpan.textContent = prices[plan].detail[period];
        }
        
        // Afficher/masquer le selecteur de période selon le plan
        if (plan === 'basic') {
            periodSelect.disabled = false;
        } else {
            periodSelect.disabled = false;
        }
    }
}

planCards.forEach(card => {
    card.addEventListener('click', function(e) {
        const radio = this.querySelector('input[type="radio"]');
        if (radio && !e.target.closest('.form-check')) radio.click();
    });
    const radio = card.querySelector('input[type="radio"]');
    radio?.addEventListener('change', function() {
        planCards.forEach(c => c.classList.remove('selected'));
        if (this.checked) card.classList.add('selected');
        updateTotalAmount();
    });
});

periodSelect?.addEventListener('change', updateTotalAmount);

// Initialisation
updateTotalAmount();

// Form submission
const registerForm = document.getElementById('registerForm');
registerForm?.addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn && !submitBtn.disabled) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Création en cours...';
    }
});

// Keep modal open on errors
@if ($errors->any())
    const modalElement = document.getElementById('registerModal');
    if (modalElement) new bootstrap.Modal(modalElement).show();
    const submitBtnError = document.getElementById('submitBtn');
    if (submitBtnError) {
        submitBtnError.disabled = false;
        submitBtnError.innerHTML = '✅ Créer mon école';
    }
@endif

// Auto-génération du sous-domaine à partir du nom de l'école
const schoolNameInput = document.querySelector('input[name="school_name"]');
const subdomainInput = document.querySelector('input[name="subdomain"]');

// Fonction pour convertir le nom d'école en sous-domaine valide
function generateSubdomainFromSchoolName(schoolName) {
    if (!schoolName) return '';
    
    return schoolName
        .toLowerCase()                          // minuscules
        .normalize('NFD')                       // décompose les accents
        .replace(/[\u0300-\u036f]/g, '')        // supprime les accents
        .replace(/[^a-z0-9]+/g, '-')            // remplace tout ce qui n'est pas lettre/chiffre par -
        .replace(/^-+|-+$/g, '');               // supprime les tirets au début et à la fin
}

// Écoute l'événement 'input' sur le champ nom de l'école
if (schoolNameInput && subdomainInput) {
    schoolNameInput.addEventListener('input', function() {
        let schoolName = this.value.trim();
        let newSubdomain = generateSubdomainFromSchoolName(schoolName);
        
        // Met à jour le champ sous-domaine
        subdomainInput.value = newSubdomain;
        
        // Optionnel : valider que le sous-domaine n'est pas vide
        if (newSubdomain === '') {
            subdomainInput.classList.add('is-invalid');
        } else {
            subdomainInput.classList.remove('is-invalid');
        }
    });
    
    // Si le nom d'école est déjà pré-rempli (erreur de validation), générer immédiatement
    if (schoolNameInput.value) {
        subdomainInput.value = generateSubdomainFromSchoolName(schoolNameInput.value);
    }
}

</script>

</body>
</html>