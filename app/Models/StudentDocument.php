<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDocument extends Model
{
    use SoftDeletes;

    protected $table = 'student_documents';

    protected $fillable = [
        'student_id',
        'document_type',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'generated_at',
        'expires_at',
        'status',
        'metadata',
        'generated_by',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
        'file_size' => 'integer',
    ];

    // Types de documents disponibles
    const TYPES = [
        'certificate_enrollment' => 'Certificat de scolarité',
        'attestation_attendance' => 'Attestation d\'assiduité',
        'attestation_results' => 'Attestation de résultats',
        'certificate_achievement' => 'Certificat de mérite',
        'report_card' => 'Bulletin de notes',
        'attestation_behavior' => 'Attestation de bonne conduite',
        'certificate_transfer' => 'Certificat de radiation',
        'attestation_payment' => 'Attestation de paiement',
        'certificate_level' => 'Certificat de niveau',
        'other' => 'Autre document',
    ];

    // Statuts
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';
    const STATUS_EXPIRED = 'expired';

    const STATUSES = [
        self::STATUS_DRAFT => 'Brouillon',
        self::STATUS_PUBLISHED => 'Publié',
        self::STATUS_ARCHIVED => 'Archivé',
        self::STATUS_EXPIRED => 'Expiré',
    ];

    // Relations
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expires_at', '<=', now()->addDays($days))
                     ->where('expires_at', '>', now());
    }

    // Accessors
    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->document_type] ?? $this->document_type;
    }

    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'warning',
            self::STATUS_PUBLISHED => 'success',
            self::STATUS_ARCHIVED => 'secondary',
            self::STATUS_EXPIRED => 'danger',
            default => 'gray',
        };
    }

    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        return round($bytes / 1024, 2) . ' KB';
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function getIsValidAttribute()
    {
        return $this->status === self::STATUS_PUBLISHED && 
               (!$this->expires_at || $this->expires_at > now());
    }
}