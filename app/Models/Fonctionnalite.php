<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonctionnalite extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'description', 'module_id'];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'fonctionnalite_id');
    }
}
