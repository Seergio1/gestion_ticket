<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['projet_id', 'module_id', 'fonctionnalite_id', 'etat', 'status', 'commentaire', 'fichiers', 'created_by', 'updated_by'];

    protected $casts = [
        'fichiers' => 'array'
    ];

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function fonctionnalite()
    {
        return $this->belongsTo(Fonctionnalite::class, 'fonctionnalite_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
