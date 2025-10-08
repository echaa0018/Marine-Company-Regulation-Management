<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'number',
        'title',
        'type',
        'published_date',
        'effective_until',
        'status',
        'confidentiality',
        'file_path',
        'file_name',
        'revoked_by',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'published_date' => 'datetime',
        'effective_until' => 'datetime',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Documents that this document revokes
     */
    public function revokes(): BelongsToMany
    {
        return $this->belongsToMany(
            Document::class,
            'document_relationships',
            'source_document_id',
            'target_document_id'
        )->wherePivot('relationship_type', 'revokes');
    }

    /**
     * Documents that this document changes
     */
    public function changes(): BelongsToMany
    {
        return $this->belongsToMany(
            Document::class,
            'document_relationships',
            'source_document_id',
            'target_document_id'
        )->wherePivot('relationship_type', 'changes');
    }

    /**
     * Documents that revoke this document
     */
    public function revokedBy(): BelongsToMany
    {
        return $this->belongsToMany(
            Document::class,
            'document_relationships',
            'target_document_id',
            'source_document_id'
        )->wherePivot('relationship_type', 'revokes');
    }

    /**
     * Documents that change this document
     */
    public function changedBy(): BelongsToMany
    {
        return $this->belongsToMany(
            Document::class,
            'document_relationships',
            'target_document_id',
            'source_document_id'
        )->wherePivot('relationship_type', 'changes');
    }

    /**
     * Scope to filter by status
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Berlaku');
    }

    /**
     * Scope to search documents by number or title
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('number', 'ILIKE', "%{$term}%")
              ->orWhere('title', 'ILIKE', "%{$term}%")
              ->orWhere('type', 'ILIKE', "%{$term}%");
        });
    }

    /**
     * Get full file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    /**
     * Check if document is currently active/valid
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'Berlaku' &&
               $this->effective_until >= now()->toDateString();
    }

    /**
     * Get formatted published date
     */
    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_date ? $this->published_date->format('d M Y') : '';
    }

    /**
     * Get formatted effective until date
     */
    public function getFormattedEffectiveUntilAttribute()
    {
        return $this->effective_until ? $this->effective_until->format('d M Y') : '';
    }
}
