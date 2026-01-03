<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>La plateforme intelligente de gestion scolaire à Madagascar</title>

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
                <li class="nav-item"><a class="btn btn-primary ms-3" href="/register">Créer mon école</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero text-center">
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
                    <p>Jusqu’à 50 élèves</p>
                    <a href="/register" class="btn btn-outline-primary">Commencer</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pricing p-4 text-center shadow">
                    <h5>Pro</h5>
                    <h2 class="fw-bold">Payant</h2>
                    <p>Élèves illimités + support prioritaire</p>
                    <a href="/register" class="btn btn-primary">Choisir</a>
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
</html>
