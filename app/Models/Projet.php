<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'description', 'created_by'];
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'projet_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation avec les utilisateurs ayant accès
    public function accesProjets()
    {
        return $this->hasMany(AccesProjet::class);
    }

    // Relation pratique many-to-many pour récupérer directement les users
    public function users()
    {
        return $this->belongsToMany(User::class, 'acces_projets', 'projet_id', 'user_id');
    }
    public function modules()
    {
        return $this->hasMany(Module::class, 'projet_id');
    }
}
