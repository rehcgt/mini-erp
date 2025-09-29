<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Relación many-to-many con usuarios
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    /**
     * Scope para roles activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
