<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'inscription</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 48px 40px;
            text-align: center;
            position: relative;
        }
        
        .logo {
            font-size: 48px;
            margin-bottom: 16px;
        }
        
        .header h1 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin: 0;
        }
        
        .content {
            padding: 48px 40px;
        }
        
        .status-badge {
            display: inline-block;
            background: #FEF3C7;
            color: #D97706;
            padding: 8px 16px;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
        }
        
        .greeting {
            font-size: 24px;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 16px;
        }
        
        .school-name {
            color: #667eea;
            font-weight: 700;
        }
        
        .message {
            color: #4B5563;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        
        .info-card {
            background: #F9FAFB;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 12px;
            margin: 32px 0;
        }
        
        .info-card p {
            margin: 8px 0;
            color: #374151;
        }
        
        .info-card strong {
            color: #1F2937;
        }
        
        .timeline {
            margin: 32px 0;
        }
        
        .timeline-step {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .timeline-icon {
            width: 48px;
            height: 48px;
            background: #EEF2FF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            font-size: 24px;
        }
        
        .timeline-content {
            flex: 1;
        }
        
        .timeline-content h4 {
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 4px;
        }
        
        .timeline-content p {
            color: #6B7280;
            font-size: 14px;
            margin: 0;
        }
        
        .warning-box {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 32px 0;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .warning-emoji {
            font-size: 24px;
        }
        
        .warning-text {
            flex: 1;
            color: #92400E;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 40px;
            font-weight: 600;
            margin: 24px 0;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
        }
        
        .footer {
            background: #F9FAFB;
            padding: 32px 40px;
            text-align: center;
            border-top: 1px solid #E5E7EB;
        }
        
        .footer p {
            color: #6B7280;
            font-size: 12px;
            margin: 8px 0;
        }
        
        .social-links {
            margin: 16px 0;
        }
        
        .social-links a {
            color: #9CA3AF;
            text-decoration: none;
            margin: 0 8px;
            font-size: 20px;
        }
        
        @media (max-width: 480px) {
            .content {
                padding: 32px 24px;
            }
            .header {
                padding: 32px 24px;
            }
            .greeting {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏫✨</div>
            <h1>Demande reçue avec succès !</h1>
        </div>
        
        <div class="content">
            <div style="text-align: center;">
                <div class="status-badge">
                    ⏳ EN COURS DE TRAITEMENT
                </div>
            </div>
            
            <div class="greeting">
                Bonjour <span class="school-name">{{ $school->name }}</span> ! 👋
            </div>
            
            <div class="message">
                Nous avons bien reçu votre demande d'inscription sur <strong>Sekoly</strong>. 
                Notre équipe est ravie de vous compter parmi nos futurs partenaires éducatifs !
            </div>
            
            <div class="info-card">
                <p>📧 <strong>Email de contact :</strong> {{ $school->email }}</p>
                <p>🌐 <strong>Domaine demandé :</strong> {{ $domain }}</p>
                <p>📅 <strong>Date de la demande :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
            </div>
            
            <div class="timeline">
                <div class="timeline-step">
                    <div class="timeline-icon">📝</div>
                    <div class="timeline-content">
                        <h4>1. Vérification de votre dossier</h4>
                        <p>Notre équipe analyse votre demande sous 24-48h</p>
                    </div>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon">✅</div>
                    <div class="timeline-content">
                        <h4>2. Activation de votre espace</h4>
                        <p>Création de votre plateforme personnalisée</p>
                    </div>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon">🔐</div>
                    <div class="timeline-content">
                        <h4>3. Réception des accès</h4>
                        <p>Vous recevrez vos identifiants par email</p>
                    </div>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon">🚀</div>
                    <div class="timeline-content">
                        <h4>4. Prêt à démarrer !</h4>
                        <p>Connectez-vous et gérez votre établissement</p>
                    </div>
                </div>
            </div>
            
            <div class="warning-box">
                <div class="warning-emoji">⏰</div>
                <div class="warning-text">
                    <strong>Délai de traitement :</strong> 24 à 48 heures ouvrées.<br>
                    Vous recevrez un email dès que votre espace sera activé avec vos identifiants de connexion.
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="https://Sekoly.com" class="button">Découvrir nos fonctionnalités</a>
            </div>
        </div>
        
        <div class="footer">
            <div class="social-links">
                <a href="#">📘</a>
                <a href="#">🐦</a>
                <a href="#">💼</a>
                <a href="#">📷</a>
            </div>
            <p>© 2025 Sekoly - La solution complète pour la gestion éducative</p>
            <p>Cet email est un accusé de réception automatique. Merci de ne pas y répondre.</p>
            <p>Besoin d'aide ? <a href="mailto:support@Sekoly.com" style="color:#667eea;">support@Sekoly.com</a></p>
        </div>
    </div>
</body>
</html>