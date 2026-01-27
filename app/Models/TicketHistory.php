<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'projet_id',
        'module_id',
        'fonctionnalite_id',
        'etat',
        'status',
        'commentaire',
        'fichiers',
        'updated_by',
    ];
    protected $casts = [
        'fichiers' => 'array'
    ];
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function fonctionnalite()
    {
        return $this->belongsTo(Fonctionnalite::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function mount(Projet $projet)
    {
        $this->projet = $projet;
    }
}
