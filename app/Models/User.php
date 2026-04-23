<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Force l'utilisation de la connexion centrale
    // protected $connection = 'tenant';
    
    protected $table = 'users';    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'first_name',
        'last_name',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'emergency_contact',
        'emergency_relation',
        'photo',
        'password',
        'role',
        'is_active',
        
        // Champs parents
        'father_name',
        'father_phone',
        'father_email',
        'father_profession',
        'father_cin',
        'mother_name',
        'mother_phone',
        'mother_email',
        'mother_profession',
        'mother_cin',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_profession',
        'guardian_cin',
        'guardian_relation',
    ];

    protected $casts = [
        'date_of_birth' => 'date:Y-m-d', // Format spécifique pour les inputs HTML
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Accessor pour la relation du tuteur
    public function getGuardianRelationLabelAttribute()
    {
        return match($this->guardian_relation) {
            'grandparent' => 'Grand-parent',
            'uncle' => 'Oncle/Tante',
            'sibling' => 'Frère/Sœur',
            'other_relative' => 'Autre parent',
            'legal_guardian' => 'Tuteur légal',
            'other' => 'Autre',
            default => null,
        };
    }    

    // Méthode pour récupérer le contact principal
    public function getPrimaryContactAttribute()
    {
        if ($this->guardian_phone) {
            return [
                'name' => $this->guardian_name,
                'phone' => $this->guardian_phone,
                'relation' => $this->guardian_relation_label,
            ];
        } elseif ($this->mother_phone) {
            return [
                'name' => $this->mother_name,
                'phone' => $this->mother_phone,
                'relation' => 'Mère',
            ];
        } elseif ($this->father_phone) {
            return [
                'name' => $this->father_name,
                'phone' => $this->father_phone,
                'relation' => 'Père',
            ];
        } elseif ($this->emergency_contact) {
            return [
                'name' => $this->emergency_contact,
                'phone' => $this->phone,
                'relation' => $this->emergency_relation,
            ];
        }
        
        return null;
    }

    // Méthode pour vérifier si des informations parents existent
    public function hasParentInfo()
    {
        return !empty($this->father_name) || !empty($this->mother_name) || !empty($this->guardian_name);
    }    

    // Si vous utilisez des accessors pour formater la date
    public function getFormattedDateOfBirthAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->format('d/m/Y') : null;
    }

    public function getFormattedDateOfBirthForInputAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->format('Y-m-d') : null;
    }

    // Accessor pour l'URL complète de la photo
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return Storage::url($this->photo);
        }
        
        // Photo par défaut basée sur le genre
        return match($this->gender) {
            'female' => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=F472B6&color=fff',
            'male' => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=60A5FA&color=fff',
            default => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6B7280&color=fff',
        };
    }

    // Accessor pour l'initiale
    public function getInitialAttribute()
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }    

    public function school()
    {
        return $this->belongsTo(School::class);
    }       

    public function teacherAssignments()
    {
        return $this->hasMany(ClassAssignment::class, 'teacher_id')
                    ->whereHas('subject')
                    ->whereHas('schoolClass');
    }

    public function assignedSubjects()
    {
        return $this->belongsToMany(Subject::class, 'class_assignments', 'teacher_id', 'subject_id')
                    ->withPivot(['class_id', 'hours_per_week', 'coefficient', 'is_active'])
                    ->withTimestamps();
    }

    public function assignedClasses()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_assignments', 'teacher_id', 'class_id')
                    ->withPivot(['subject_id', 'hours_per_week', 'coefficient', 'is_active'])
                    ->withTimestamps();
    }

    public function studentEnrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'student_id');
    }

    public function currentClass()
    {
        return $this->studentEnrollments()
            ->whereHas('schoolYear', function($query) {
                $query->where('is_current', true);
            })
            ->with('schoolClass')
            ->first()
            ?->schoolClass;
    }

    // Scope pour les élèves
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? app('tenant')->id);
    }    

    public function latestEnrollment()
    {
        return $this->hasOne(StudentEnrollment::class, 'student_id')
            ->latest('enrollment_date') // Prend la dernière inscription
            ->where('status', 'active'); // Optionnel: seulement les actives
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'student_id');
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'student_enrollments', 'student_id', 'class_id')
                    ->withTimestamps();
    }

}
