<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps - {{ $timetable->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.5;
            color: #333;
            background: #fff;
            padding: 20px;
        }
        
        .print-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .header .subtitle {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        
        .header .dates {
            font-size: 14px;
            color: #9ca3af;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .timetable {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 12px;
        }
        
        .timetable th {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
        }
        
        .timetable td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            vertical-align: top;
            min-height: 80px;
        }
        
        .time-cell {
            background: #f9fafb;
            font-weight: 500;
            color: #374151;
            text-align: center;
            width: 100px;
        }
        
        .time-range {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }
        
        .slot {
            padding: 6px;
            border-radius: 4px;
            margin-bottom: 4px;
            font-size: 11px;
            border-left: 3px solid;
        }
        
        .slot-subject {
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .slot-teacher {
            font-size: 10px;
            color: #4b5563;
            margin-bottom: 1px;
        }
        
        .slot-room {
            font-size: 10px;
            color: #6b7280;
        }
        
        .details-section {
            margin-top: 40px;
            page-break-before: always;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .day-section {
            margin-bottom: 30px;
        }
        
        .day-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .course-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: 4px solid;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
            font-size: 13px;
        }
        
        .course-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }
        
        .course-subject {
            font-weight: 600;
            color: #1f2937;
        }
        
        .course-time {
            font-size: 12px;
            color: #6b7280;
        }
        
        .course-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            font-size: 12px;
        }
        
        .detail-item {
            display: flex;
            gap: 6px;
        }
        
        .detail-label {
            color: #6b7280;
            min-width: 70px;
        }
        
        .detail-value {
            color: #374151;
            font-weight: 500;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
        }
        
        .no-print {
            display: none;
        }
        
        @media print {
            body {
                padding: 0;
                font-size: 10pt;
            }
            
            .print-container {
                max-width: 100%;
            }
            
            .stats-grid {
                gap: 10px;
                margin-bottom: 20px;
            }
            
            .stat-card {
                padding: 10px;
            }
            
            .stat-value {
                font-size: 18px;
            }
            
            .timetable {
                font-size: 9pt;
            }
            
            .timetable th,
            .timetable td {
                padding: 6px 4px;
            }
            
            .slot {
                padding: 4px;
                font-size: 9pt;
            }
            
            .details-section {
                margin-top: 30px;
            }
            
            .page-break {
                page-break-before: always;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px; padding: 10px; background: #f3f4f6; border-radius: 8px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; margin-right: 10px;">
            🖨️ Imprimer
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
            ✕ Fermer
        </button>
    </div>
    
    <div class="print-container">
        <!-- En-tête -->
        <div class="header">
            <h1>{{ $timetable->name }}</h1>
            <div class="subtitle">{{ $timetable->class->name }} • {{ $timetable->academicYear->name ?? 'Année scolaire' }}</div>
            <div class="dates">
                Valide du {{ $timetable->start_date->format('d/m/Y') }} au {{ $timetable->end_date->format('d/m/Y') }}
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="stats-grid">
            @php
                $totalSlots = $slotsByDay->flatten()->count();
                $totalHours = $slotsByDay->flatten()->sum(function($slot) {
                    $start = strtotime($slot->start_time);
                    $end = strtotime($slot->end_time);
                    return ($end - $start) / 3600;
                });
                $teachersCount = $slotsByDay->flatten()->pluck('teacher_id')->unique()->count();
                $subjectsCount = $slotsByDay->flatten()->pluck('subject_id')->unique()->count();
            @endphp
            
            <div class="stat-card">
                <div class="stat-value">{{ $totalSlots }}</div>
                <div class="stat-label">Cours/semaine</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-value">{{ number_format($totalHours, 1) }}h</div>
                <div class="stat-label">Heures/semaine</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-value">{{ $teachersCount }}</div>
                <div class="stat-label">Professeurs</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-value">{{ $subjectsCount }}</div>
                <div class="stat-label">Matières</div>
            </div>
        </div>
        
        <!-- Emploi du temps principal -->
        <table class="timetable">
            <thead>
                <tr>
                    <th class="time-cell">Heure</th>
                    @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'] as $dayIndex => $dayName)
                    @php
                        $dayNumber = $dayIndex + 1;
                        $daySlots = $slotsByDay[$dayNumber] ?? collect();
                        $dayHours = $daySlots->sum(function($slot) {
                            $start = strtotime($slot->start_time);
                            $end = strtotime($slot->end_time);
                            return ($end - $start) / 3600;
                        });
                    @endphp
                    <th>
                        {{ $dayName }}
                        <div style="font-size: 10px; font-weight: normal; color: #6b7280; margin-top: 2px;">
                            {{ number_format($dayHours, 1) }}h
                        </div>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $timeSlots = [
                        ['08:00', '09:30'],
                        ['09:45', '11:15'],
                        ['11:30', '13:00'],
                        ['14:00', '15:30'],
                        ['15:45', '17:15'],
                        ['17:30', '19:00']
                    ];
                @endphp
                
                @foreach($timeSlots as $timeSlot)
                <tr>
                    <td class="time-cell">
                        {{ $timeSlot[0] }}
                        <div class="time-range">{{ $timeSlot[1] }}</div>
                    </td>
                    
                    @for($day = 1; $day <= 5; $day++)
                    @php
                        $slot = $slotsByDay[$day] ?? collect();
                        $currentSlot = $slot->first(function($s) use ($timeSlot) {
                            return $s->start_time->format('H:i') == $timeSlot[0];
                        });
                    @endphp
                    
                    <td>
                        @if($currentSlot)
                        <div class="slot" style="border-left-color: {{ $currentSlot->color }}; background: {{ $currentSlot->color }}10;">
                            <div class="slot-subject">{{ $currentSlot->subject->code }}</div>
                            <div class="slot-teacher">
                                @if($currentSlot->teacherProfile)
                                {{ $currentSlot->teacherProfile->first_name }}
                                @elseif($currentSlot->teacher)
                                {{ $currentSlot->teacher->name }}
                                @endif
                            </div>
                            <div class="slot-room">
                                {{ $currentSlot->classroom->name ?? 'Salle non attribuée' }}
                            </div>
                        </div>
                        @endif
                    </td>
                    @endfor
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Détails par jour -->
        <div class="details-section">
            <h2 class="section-title">Détails des cours par jour</h2>
            
            @foreach($slotsByDay as $dayNumber => $daySlots)
            @if($daySlots->isNotEmpty())
            @php
                $dayNames = ['', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                $dayName = $dayNames[$dayNumber] ?? "Jour $dayNumber";
                $dayHours = $daySlots->sum(function($slot) {
                    $start = strtotime($slot->start_time);
                    $end = strtotime($slot->end_time);
                    return ($end - $start) / 3600;
                });
            @endphp
            
            <div class="day-section">
                <h3 class="day-title">{{ $dayName }} ({{ number_format($dayHours, 1) }}h de cours)</h3>
                
                @foreach($daySlots->sortBy('start_time') as $slot)
                <div class="course-card" style="border-left-color: {{ $slot->color }}">
                    <div class="course-header">
                        <div class="course-subject">{{ $slot->subject->name }} ({{ $slot->subject->code }})</div>
                        <div class="course-time">{{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}</div>
                    </div>
                    
                    <div class="course-details">
                        <div class="detail-item">
                            <span class="detail-label">Professeur:</span>
                            <span class="detail-value">
                                {{ $slot->teacherProfile->full_name ?? $slot->teacher->name ?? 'Non attribué' }}
                            </span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Salle:</span>
                            <span class="detail-value">{{ $slot->classroom->name ?? 'Non attribuée' }}</span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Durée:</span>
                            <span class="detail-value">{{ $slot->duration }}h</span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Type:</span>
                            <span class="detail-value">{{ $slot->recurring ? 'Récurrent' : 'Ponctuel' }}</span>
                        </div>
                    </div>
                    
                    @if($slot->notes)
                    <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                        <div style="font-size: 11px; color: #6b7280; font-style: italic;">{{ $slot->notes }}</div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
            @endforeach
        </div>
        
        <!-- Pied de page -->
        <div class="footer">
            <div>Document généré le {{ now()->format('d/m/Y à H:i') }}</div>
            <div>{{ config('app.name') }} - Système de gestion scolaire</div>
        </div>
    </div>
    
    <script>
        // Auto-impression optionnelle
        window.onload = function() {
            // Décommenter pour impression automatique
            // setTimeout(function() {
            //     window.print();
            // }, 1000);
        }
        
        // Gestion du retour après impression
        window.onafterprint = function() {
            // window.close(); // Décommenter pour fermer automatiquement après impression
        };
    </script>
</body>
</html>