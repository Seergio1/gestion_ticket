<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'description', 'projet_id'];

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }

    public function fonctionnalites()
    {
        return $this->hasMany(Fonctionnalite::class, 'module_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'module_id');
    }
}
