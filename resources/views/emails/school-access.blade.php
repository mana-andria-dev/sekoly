<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre école est activée - Sekoly</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #F3F4F6;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        
        .email-wrapper {
            width: 100%;
            background: #F3F4F6;
        }
        
        .header {
            background: linear-gradient(135deg, #1E3A5F 0%, #2E5A8A 100%);
            padding: 60px 20px;
            text-align: center;
        }
        
        .header-content {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .logo {
            font-size: 56px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            color: white;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 16px;
        }
        
        .success-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            color: white;
            padding: 8px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .content {
            max-width: 900px;
            margin: 0 auto;
            padding: 48px 24px;
            background: white;
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: 48px;
        }
        
        .greeting {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }
        
        .school-name {
            color: #2E5A8A;
            border-bottom: 3px solid #2E5A8A;
            padding-bottom: 4px;
        }
        
        .message {
            color: #4B5563;
            font-size: 16px;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .credentials-section {
            background: #F9FAFB;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            border: 1px solid #E5E7EB;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 28px;
        }
        
        .section-title h3 {
            font-size: 22px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 6px;
        }
        
        .section-title p {
            font-size: 14px;
            color: #6B7280;
        }
        
        .credentials-row {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .credential-col {
            display: table-cell;
            width: 33.333%;
            padding: 20px;
            text-align: center;
            border-right: 1px solid #E5E7EB;
            vertical-align: top;
        }
        
        .credential-col:last-child {
            border-right: none;
        }
        
        .credential-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6B7280;
            margin-bottom: 12px;
        }
        
        .credential-value {
            font-family: 'SF Mono', Monaco, monospace;
            font-size: 14px;
            font-weight: 500;
            color: #1E3A5F;
            word-break: break-all;
            background: white;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
        }
        
        .alert-security {
            background: #FEF2E8;
            border-left: 4px solid #F97316;
            border-radius: 12px;
            padding: 18px 24px;
            margin-bottom: 32px;
        }
        
        .alert-title {
            font-weight: 700;
            color: #9A3412;
            margin-bottom: 4px;
            font-size: 15px;
        }
        
        .alert-text {
            font-size: 14px;
            color: #7C2D12;
        }
        
        .cta-button {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .btn {
            display: inline-block;
            background: #2E5A8A;
            color: white;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: #1E3A5F;
            transform: translateY(-2px);
        }
        
        /* Section abonnement améliorée */
        .subscription-info {
            background: #F0F9FF;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid #BAE6FD;
        }
        
        .sub-title {
            font-weight: 600;
            color: #0369A1;
            margin-bottom: 20px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .sub-details {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            align-items: center;
        }
        
        .sub-item {
            display: flex;
            align-items: baseline;
            gap: 8px;
            font-size: 14px;
            color: #0C4A6E;
            background: white;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #BAE6FD;
        }
        
        .sub-item strong {
            font-weight: 600;
            color: #0369A1;
        }
        
        .footer {
            background: #F9FAFB;
            padding: 40px 24px;
            text-align: center;
            border-top: 1px solid #E5E7EB;
        }
        
        .footer-content {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .footer p {
            color: #6B7280;
            font-size: 13px;
            margin: 8px 0;
        }
        
        .footer a {
            color: #2E5A8A;
            text-decoration: none;
        }
        
        .footer-links {
            margin-top: 16px;
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
        }
        
        hr {
            border: none;
            border-top: 1px solid #E5E7EB;
            margin: 24px 0;
        }
        
        @media (max-width: 640px) {
            .credentials-row {
                display: block;
            }
            
            .credential-col {
                display: block;
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #E5E7EB;
                padding: 20px;
            }
            
            .credential-col:last-child {
                border-bottom: none;
            }
            
            .sub-details {
                flex-direction: column;
                gap: 12px;
            }
            
            .sub-item {
                width: 100%;
            }
            
            .header {
                padding: 40px 20px;
            }
            
            .header h1 {
                font-size: 32px;
            }
            
            .content {
                padding: 32px 20px;
            }
            
            .greeting {
                font-size: 26px;
            }
            
            .credentials-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <div class="header-content">
                <div class="logo">🏫</div>
                <h1>Félicitations !</h1>
                <div class="success-badge">✓ Votre école est activée</div>
            </div>
        </div>
        
        <div class="content">
            <div class="welcome-section">
                <div class="greeting">
                    Bonjour <span class="school-name">{{ $school->name }}</span>
                </div>
                <div class="message">
                    Votre établissement est désormais actif sur Sekoly. 
                    Voici vos identifiants de connexion.
                </div>
            </div>
            
            <div class="credentials-section">
                <div class="section-title">
                    <h3>Vos identifiants de connexion</h3>
                    <p>Conservez ces informations précieusement</p>
                </div>
                
                <div class="credentials-row">
                    <div class="credential-col">
                        <div class="credential-label">URL D'ACCÈS</div>
                        <div class="credential-value">
                            {{ $domain ?? $school->slug . '.site.test' }}
                        </div>
                    </div>
                    
                    <div class="credential-col">
                        <div class="credential-label">EMAIL</div>
                        <div class="credential-value">
                            {{ $user->email }}
                        </div>
                    </div>
                    
                    <div class="credential-col">
                        <div class="credential-label">MOT DE PASSE</div>
                        <div class="credential-value">
                            {{ $password }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert-security">
                <div class="alert-title">⚠️ Important : Sécurité</div>
                <div class="alert-text">
                    Pour protéger votre compte, veuillez changer votre mot de passe lors de votre première connexion.
                </div>
            </div>
            
            <div class="cta-button">
                <a href="https://{{ $domain ?? $school->slug . '.site.test' }}" class="btn">
                    Accéder à mon espace →
                </a>
            </div>
            
            @if($subscription)
            <div class="subscription-info">
                <div class="sub-title">
                    <span>📋</span> Votre abonnement
                </div>
                <div class="sub-details">
                    <div class="sub-item">
                        <strong>Formule :</strong> {{ ucfirst($subscription->plan) }}
                    </div>
                    <div class="sub-item">
                        <strong>Période :</strong> {{ ucfirst($subscription->period ?? 'Mensuel') }}
                    </div>
                    <div class="sub-item">
                        <strong>Montant :</strong> {{ number_format($subscription->amount, 0, ',', ' ') }} €
                    </div>
                    <div class="sub-item">
                        <strong>Renouvellement :</strong> {{ $subscription->ends_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="footer">
            <div class="footer-content">
                <p>© {{ date('Y') }} Sekoly — La solution complète pour la gestion éducative</p>
                <p>Cet email a été envoyé automatiquement suite à l'activation de votre établissement.</p>
                <hr>
                <p style="font-size: 11px; color: #9CA3AF;">
                    Sekoly - 123 Avenue de l'Innovation, 75000 Paris
                </p>
            </div>
        </div>
    </div>
</body>
</html>