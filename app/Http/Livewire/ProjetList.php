<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Projet;

class ProjetList extends Component
{
    public function render()
    {
        $projets = Projet::withCount('tickets')->get();
        return view('livewire.projet-list', ['projets' => $projets]);
    }

    public function deleteProjet($id)
    {
        $projet = Projet::findOrFail($id);
        if (auth()->user()->role !== 'admin') abort(403);
        $projet->delete();
        session()->flash('message', 'Projet supprimé avec succès');
    }
}
