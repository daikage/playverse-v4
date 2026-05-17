<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'type',
        'platforms',
        'asset_path',
        'pages',
        'published',
        // +++ NEW +++
        'screenshots',
        'videos',
        // +++ END +++
        'thumbnail_path',
    ];

    protected $casts = [
        'platforms' => 'array',
        'pages' => 'array',
        'published' => 'boolean',
        // +++ NEW +++
        'screenshots' => 'array',
        'videos' => 'array',
        // +++ END +++
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function canPublish(): bool
    {
        return $this->author?->isApproved() === true;
    }
}
