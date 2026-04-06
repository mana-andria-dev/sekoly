<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps - {{ $timetable->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10px;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
            color: #1e40af;
        }
        
        .header p {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 5px;
        }
        
        .info-box {
            text-align: center;
        }
        
        .info-box .label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
        }
        
        .info-box .value {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
        }
        
        .timetable-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .timetable-table th,
        .timetable-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            vertical-align: top;
        }
        
        .timetable-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 11px;
        }
        
        .time-column {
            background-color: #f9fafb;
            font-weight: bold;
            width: 60px;
        }
        
        .slot {
            padding: 6px;
            border-radius: 4px;
            margin-bottom: 4px;
            font-size: 9px;
            text-align: left;
        }
        
        .slot:last-child {
            margin-bottom: 0;
        }
        
        .slot-subject {
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .slot-teacher {
            font-size: 8px;
            color: #666;
        }
        
        .slot-classroom {
            font-size: 8px;
            color: #999;
        }
        
        .empty-slot {
            color: #ccc;
            font-size: 10px;
        }
        
        .stats-section {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        
        .stats-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .stats-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .stats-card {
            flex: 1;
            padding: 8px;
            background-color: #f9fafb;
            border-radius: 4px;
        }
        
        .stats-card h4 {
            font-size: 10px;
            margin-bottom: 5px;
            color: #666;
        }
        
        .stats-card p {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
        }
        
        .subject-list, .teacher-list {
            font-size: 9px;
        }
        
        .subject-item, .teacher-item {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px solid #eee;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #999;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        
        @page {
            margin: 1.5cm;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .timetable-table th,
            .timetable-table td {
                border-color: #000;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $timetable->name }}</h1>
        <p>
            Classe : {{ $timetable->class->name }} | 
            Année scolaire : {{ $timetable->academicYear->name }}
        </p>
        <p>
            Date d'impression : {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>
    
    <div class="info-section">
        <div class="info-box">
            <div class="label">Total créneaux</div>
            <div class="value">{{ $totalSlots }}</div>
        </div>
        <div class="info-box">
            <div class="label">Matières enseignées</div>
            <div class="value">{{ $subjectHours->count() }}</div>
        </div>
        <div class="info-box">
            <div class="label">Enseignants</div>
            <div class="value">{{ $teacherHours->count() }}</div>
        </div>
        <div class="info-box">
            <div class="label">Statut</div>
            <div class="value">{{ $timetable->is_active ? 'Actif' : 'Inactif' }}</div>
        </div>
    </div>
    
    <table class="timetable-table">
        <thead>
            <tr>
                <th>Heure</th>
                <th>Lundi</th>
                <th>Mardi</th>
                <th>Mercredi</th>
                <th>Jeudi</th>
                <th>Vendredi</th>
                <th>Samedi</th>
            </tr>
        </thead>
        <tbody>
            @for($hour = $startHour; $hour < $endHour; $hour++)
                @php
                    $startTime = sprintf('%02d:00', $hour);
                    $endTime = sprintf('%02d:00', $hour + 1);
                    $displayStart = sprintf('%dh', $hour);
                    $displayEnd = sprintf('%dh', $hour + 1);
                @endphp
                <tr>
                    <td class="time-column">
                        {{ $displayStart }} - {{ $displayEnd }}
                    </td>
                    
                    @for($day = 1; $day <= 6; $day++)
                        @php
                            $daySlots = $slotsByDay[$day] ?? collect();
                            
                            // Trouver les créneaux qui se superposent avec cette tranche horaire
                            $matchingSlots = $daySlots->filter(function($slot) use ($startTime, $endTime) {
                                $slotStart = $slot->start_time->format('H:i');
                                $slotEnd = $slot->end_time->format('H:i');
                                return ($slotStart < $endTime && $slotEnd > $startTime);
                            });
                        @endphp
                        
                        <td>
                            @if($matchingSlots->isNotEmpty())
                                @foreach($matchingSlots as $slot)
                                    <div class="slot" style="border-left: 3px solid {{ $slot->color ?? '#3B82F6' }}; background-color: {{ $slot->color ?? '#3B82F6' }}10;">
                                        <div class="slot-subject">
                                            <strong>{{ $slot->subject->code ?? 'N/A' }}</strong>
                                            @if($slot->duration != 1)
                                                <span style="font-size: 8px;">({{ $slot->duration }}h)</span>
                                            @endif
                                        </div>
                                        <div class="slot-subject" style="font-size: 8px;">
                                            {{ $slot->subject->name ?? 'Inconnu' }}
                                        </div>
                                        @if($slot->teacherProfile)
                                            <div class="slot-teacher">
                                                👨‍🏫 {{ $slot->teacherProfile->first_name }} {{ $slot->teacherProfile->last_name ?? '' }}
                                            </div>
                                        @elseif($slot->teacher)
                                            <div class="slot-teacher">
                                                👨‍🏫 {{ $slot->teacher->name }}
                                            </div>
                                        @endif
                                        @if($slot->classroom)
                                            <div class="slot-classroom">
                                                🏫 {{ $slot->classroom->code }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-slot">—</div>
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
    
    <div class="stats-section">
        <div class="stats-title">Statistiques détaillées</div>
        
        <div class="stats-grid">
            <div class="stats-card">
                <h4>Répartition par matière</h4>
                <div class="subject-list">
                    @foreach($subjectHours as $subject)
                        <div class="subject-item">
                            <span><strong>{{ $subject->code }}</strong> {{ $subject->name }}</span>
                            <span>{{ number_format($subject->total_hours, 1) }}h ({{ $subject->slot_count }} cours)</span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="stats-card">
                <h4>Répartition par enseignant</h4>
                <div class="teacher-list">
                    @foreach($teacherHours as $teacher)
                        <div class="teacher-item">
                            <span>{{ $teacher->teacher_name }}</span>
                            <span>{{ number_format($teacher->total_hours, 1) }}h ({{ $teacher->slot_count }} cours)</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>Document généré automatiquement par SekolyApp - Emploi du temps officiel</p>
        @if($timetable->creator)
            <p>Créé par : {{ $timetable->creator->name }} le {{ $timetable->created_at->format('d/m/Y') }}</p>
        @endif
    </div>
</body>
</html>