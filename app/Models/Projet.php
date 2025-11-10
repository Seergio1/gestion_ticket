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
}
