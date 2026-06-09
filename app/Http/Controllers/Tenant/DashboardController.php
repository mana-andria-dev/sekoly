<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolYear;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\StudentEnrollment;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = tenant();
        
        if (!$tenant) {
            abort(403, 'Tenant non trouvé');
        }

        // Année active
        $activeYear = SchoolYear::where('is_active', true)->first();
        $currentYearId = $activeYear?->id;

        // Statistiques dynamiques
        $usersCount = User::count();
        
        // Taux d'utilisateurs actifs (dernier mois)
        $lastMonth = now()->subMonth();
        $usersActiveLastMonth = User::where('created_at', '>=', $lastMonth)->count();
        $usersGrowthPercent = $usersCount > 0 
            ? round(($usersActiveLastMonth / $usersCount) * 100) 
            : 0;

        // Nombre total de classes
        $classesCount = SchoolClass::count();
        
        // Classes créées ce mois
        $classesThisMonth = SchoolClass::where('created_at', '>=', $lastMonth)->count();
        
        // Taux de remplissage moyen des classes
        $totalCapacity = 0;
        $totalStudents = 0;
        
        // Vous pouvez définir une capacité par classe (ex: 30)
        $defaultClassCapacity = 30;
        
        $classes = SchoolClass::withCount(['students' => function($query) use ($currentYearId) {
            if ($currentYearId) {
                $query->whereHas('studentEnrollments', function($q) use ($currentYearId) {
                    $q->where('school_year_id', $currentYearId);
                });
            }
        }])->get();
        
        foreach ($classes as $class) {
            $totalCapacity += $defaultClassCapacity;
            $totalStudents += $class->students_count;
        }
        
        $fillRate = $totalCapacity > 0 ? round(($totalStudents / $totalCapacity) * 100) : 0;

        // Nombre total d'élèves
        $studentsTotal = User::where('role', 'student')->count();
        
        // Élèves inscrits ce mois
        $studentsThisMonth = User::where('role', 'student')
            ->where('created_at', '>=', $lastMonth)
            ->count();
        
        // Moyenne d'élèves par classe
        $avgStudentsPerClass = $classesCount > 0 
            ? round($studentsTotal / $classesCount, 1) 
            : 0;

        // Évolution des utilisateurs (pourcentage)
        $usersPreviousPeriod = User::where('created_at', '<', $lastMonth)->count();
        $usersEvolution = $usersPreviousPeriod > 0 
            ? round(($usersActiveLastMonth / $usersPreviousPeriod) * 100) 
            : 0;

        // Activités récentes (exemple avec différents modèles)
        $recentActivities = $this->getRecentActivities();

        return view('tenant.dashboard', [
            'tenant' => $tenant,
            'usersCount' => $usersCount,
            'usersGrowthPercent' => $usersGrowthPercent,
            'usersActiveLastMonth' => $usersActiveLastMonth,
            'usersEvolution' => $usersEvolution,
            'activeYear' => $activeYear,
            'classesCount' => $classesCount,
            'classesThisMonth' => $classesThisMonth,
            'fillRate' => $fillRate,
            'studentsTotal' => $studentsTotal,
            'studentsThisMonth' => $studentsThisMonth,
            'avgStudentsPerClass' => $avgStudentsPerClass,
            'recentActivities' => $recentActivities,
        ]);
    }

    private function getRecentActivities()
    {
        $activities = [];
        
        // Derniers utilisateurs inscrits
        $recentUsers = User::latest()->take(3)->get();
        foreach ($recentUsers as $user) {
            $activities[] = (object)[
                'type' => 'user',
                'icon' => '👤',
                'icon_class' => 'primary-600',
                'title' => 'Nouvel utilisateur',
                'description' => "{$user->name} s'est inscrit comme " . ($user->role ?? 'utilisateur'),
                'time' => $user->created_at->diffForHumans(),
                'time_raw' => $user->created_at
            ];
        }
        
        // Dernières classes créées
        $recentClasses = SchoolClass::latest()->take(2)->get();
        foreach ($recentClasses as $class) {
            $activities[] = (object)[
                'type' => 'class',
                'icon' => '📚',
                'icon_class' => 'success',
                'title' => 'Classe créée',
                'description' => "La classe \"{$class->name}\" a été ajoutée",
                'time' => $class->created_at->diffForHumans(),
                'time_raw' => $class->created_at
            ];
        }
        
        // Dernières inscriptions
        $recentEnrollments = StudentEnrollment::with(['student', 'schoolClass'])
            ->latest()
            ->take(2)
            ->get();
            
        foreach ($recentEnrollments as $enrollment) {
            if ($enrollment->student && $enrollment->schoolClass) {
                $activities[] = (object)[
                    'type' => 'enrollment',
                    'icon' => '🎓',
                    'icon_class' => 'warning',
                    'title' => 'Nouvelle inscription',
                    'description' => "{$enrollment->student->name} a rejoint {$enrollment->schoolClass->name}",
                    'time' => $enrollment->created_at->diffForHumans(),
                    'time_raw' => $enrollment->created_at
                ];
            }
        }
        
        // Trier par date et prendre les 5 plus récentes
        usort($activities, function($a, $b) {
            return $b->time_raw <=> $a->time_raw;
        });
        
        return array_slice($activities, 0, 5);
    }
}