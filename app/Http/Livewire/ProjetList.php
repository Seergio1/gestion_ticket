<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Projet;

class ProjetList extends Component
{
    // public function render()
    // {
    //     $projets = Projet::withCount('tickets')->get();
    //     return view('livewire.projet-list', ['projets' => $projets]);
    // }
    public function render()
    {
        // $projets = Projet::withCount('tickets')->get();
        // return view('livewire.projet-list', ['projets' => $projets]);
        $projets = Projet::join('acces_projets', 'projets.id', '=', 'acces_projets.projet_id')
            ->where('acces_projets.user_id', auth()->user()->id)
            ->withCount('tickets')
            ->get();

        return view('livewire.projet-list', ['projets' => $projets]);
    }

    public function deleteProjet($id)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') abort(403);

        $projet = Projet::findOrFail($id);
        $projet->delete();
        session()->flash('message', 'Projet supprimé avec succès');
    }
}
