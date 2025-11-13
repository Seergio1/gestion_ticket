<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Projet;

class ProjetList extends Component
{

    public function render()
    {

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

    public function clearModules($projet_id)
    {
        $projet = Projet::with('modules.fonctionnalites')->findOrFail($projet_id);

        if ($projet->modules()->exists()) {
            foreach ($projet->modules as $module) {
                $module->fonctionnalites()->delete();
            }

            $projet->modules()->delete();

            session()->flash('message', 'Modules et fonctionnalités du projet supprimés avec succès.');
        } else {
            session()->flash('error', 'Ce projet n\'a aucun module à supprimer.');
        }
    }
}
