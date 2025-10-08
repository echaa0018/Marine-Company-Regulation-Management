<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentRelationship extends Model
{
    use HasUuids;

    protected $fillable = [
        'source_document_id',
        'target_document_id',
        'relationship_type',
        'created_by',
    ];

    const TYPE_REVOKES = 'revokes';
    const TYPE_CHANGES = 'changes';

    /**
     * The source document (the one that revokes/changes)
     */
    public function sourceDocument(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'source_document_id');
    }

    /**
     * The target document (the one being revoked/changed)
     */
    public function targetDocument(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'target_document_id');
    }

    /**
     * Scope for revokes relationships
     */
    public function scopeRevokes($query)
    {
        return $query->where('relationship_type', self::TYPE_REVOKES);
    }

    /**
     * Scope for changes relationships
     */
    public function scopeChanges($query)
    {
        return $query->where('relationship_type', self::TYPE_CHANGES);
    }
}
