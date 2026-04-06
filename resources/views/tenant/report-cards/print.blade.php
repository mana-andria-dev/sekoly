{{-- resources/views/tenant/report-cards/print.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin - {{ $reportCard->student->first_name }} {{ $reportCard->student->last_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            background: white;
            color: #1a1a1a;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 18px;
            color: #555;
            margin-bottom: 10px;
        }
        
        .student-info {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        .info-value {
            color: #1a1a1a;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .text-center {
            text-align: center;
        }
        
        .average-good {
            color: #22c55e;
            font-weight: bold;
        }
        
        .average-bad {
            color: #ef4444;
            font-weight: bold;
        }
        
        .tfoot-average {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: #f5f5f5;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .comments-section {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        
        .comment-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border-left: 3px solid #3b82f6;
        }
        
        .comment-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .comment-text {
            color: #555;
            line-height: 1.5;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #999;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
            
            .student-info {
                background: none;
                border: 1px solid #ddd;
            }
            
            .stat-card, .comment-box {
                background: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <h1>BULLETIN SCOLAIRE</h1>
            <h2>{{ $reportCard->schoolYear->name ?? 'Année scolaire' }}</h2>
            <p>
                @if($reportCard->period == 'trimester1') 1er TRIMESTRE
                @elseif($reportCard->period == 'trimester2') 2ème TRIMESTRE
                @elseif($reportCard->period == 'trimester3') 3ème TRIMESTRE
                @else ANNÉE SCOLAIRE
                @endif
            </p>
        </div>

        <!-- Informations élève -->
        <div class="student-info">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Élève :</span>
                    <span class="info-value">{{ $reportCard->student->first_name }} {{ $reportCard->student->last_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Classe :</span>
                    <span class="info-value">{{ $reportCard->class->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date d'émission :</span>
                    <span class="info-value">{{ $reportCard->issued_date->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Statut :</span>
                    <span class="info-value">{{ ucfirst($reportCard->status) }}</span>
                </div>
            </div>
        </div>

        <!-- Tableau des notes -->
        <h3 style="margin-bottom: 10px;">Résultats par matière</h3>
        <table>
            <thead>
                <tr>
                    <th>Matière</th>
                    <th class="text-center">Moyenne /20</th>
                    <th class="text-center">Moyenne Classe</th>
                    <th class="text-center">Coef.</th>
                    <th>Appréciation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportCard->subject_grades as $subject)
                <tr>
                    <td>{{ $subject['subject_name'] }}</td>
                    <td class="text-center">
                        @if($subject['average'])
                            <span class="{{ $subject['average'] >= 10 ? 'average-good' : 'average-bad' }}">
                                {{ number_format($subject['average'], 2) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ $subject['class_average'] ? number_format($subject['class_average'], 2) : '-' }}</td>
                    <td class="text-center">{{ $subject['coefficient'] }}</td>
                    <td>{{ $subject['appreciation'] }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-average">
                    <td><strong>MOYENNE GÉNÉRALE</strong></td>
                    <td class="text-center">
                        <strong class="{{ $reportCard->overall_average >= 10 ? 'average-good' : 'average-bad' }}">
                            {{ number_format($reportCard->overall_average ?? 0, 2) }}/20
                        </strong>
                    </td>
                    <td class="text-center">{{ $reportCard->class_average ? number_format($reportCard->class_average, 2) : '-' }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $reportCard->class_rank ?? '-' }}</div>
                <div class="stat-label">Rang dans la classe</div>
                <div class="stat-label" style="font-size: 10px;">sur {{ $reportCard->total_students ?? '-' }} élèves</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" style="color: #8b5cf6;">{{ $reportCard->mention }}</div>
                <div class="stat-label">Mention</div>
            </div>
            <div class="stat-card">
                @php
                    $difference = $reportCard->overall_average ? $reportCard->overall_average - ($reportCard->class_average ?? 0) : 0;
                @endphp
                <div class="stat-value" style="color: {{ $difference >= 0 ? '#22c55e' : '#ef4444' }};">
                    {{ $difference >= 0 ? '+' : '' }}{{ number_format($difference, 2) }}
                </div>
                <div class="stat-label">vs Moyenne Classe</div>
            </div>
        </div>

        <!-- Commentaires -->
        <div class="comments-section">
            @if($reportCard->appreciation)
            <div class="comment-box">
                <div class="comment-title">Appréciation générale</div>
                <div class="comment-text">{{ $reportCard->appreciation }}</div>
            </div>
            @endif

            @if($reportCard->teacher_comments)
            <div class="comment-box">
                <div class="comment-title">Commentaires du professeur principal</div>
                <div class="comment-text">{{ $reportCard->teacher_comments }}</div>
            </div>
            @endif

            @if($reportCard->principal_comments)
            <div class="comment-box">
                <div class="comment-title">Commentaires de la direction</div>
                <div class="comment-text">{{ $reportCard->principal_comments }}</div>
            </div>
            @endif

            @if($reportCard->absences && count($reportCard->absences) > 0)
            <div class="comment-box">
                <div class="comment-title">Absences</div>
                <div class="comment-text">
                    <ul style="margin-left: 20px;">
                        @foreach($reportCard->absences as $absence)
                        <li>{{ $absence }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if($reportCard->behaviors && count($reportCard->behaviors) > 0)
            <div class="comment-box">
                <div class="comment-title">Comportement</div>
                <div class="comment-text">
                    <ul style="margin-left: 20px;">
                        @foreach($reportCard->behaviors as $behavior)
                        <li>{{ $behavior }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>Ce bulletin est délivré par l'établissement et atteste des résultats obtenus par l'élève.</p>
            <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>