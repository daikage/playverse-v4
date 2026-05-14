<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'studio_uuid',
        'verification_status',
        'playverse_key',
        'owner_user_id',
        'logo_path',
        'links',
        'mission_statement',
        'suspended_at',
    ];

    protected $casts = [
        'suspended_at' => 'datetime',
        'links' => 'array',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function isApproved(): bool
    {
        return $this->verification_status === 'approved';
    }
}
