<?php

namespace App\Http\Livewire;

use App\Models\AccesProjet;
use App\Models\Projet;
use App\Models\User;
use Livewire\Component;

class ProjetForm extends Component
{
    public $projet;
    public $nom;
    public $description;
    public $utilisateurs = [];
    public $users = [];

    public function mount($projet = null)
    {

        // Tous les utilisateurs sauf l'utilisateur connecté
        $this->users = User::where('id', '!=', auth()->id())->orderBy('name')->get();
        if ($projet) {
            $this->projet = Projet::findOrFail($projet);
            $this->nom = $this->projet->nom;
            $this->description = $this->projet->description;
        }
    }

    public function save()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'utilisateurs' => 'array',
        ]);

        if (!$this->projet) {
            // Création
            $this->projet = Projet::create([
                'nom' => $this->nom,
                'description' => $this->description,
                'created_by' => auth()->id(),
            ]);
            $message = "Projet créé avec succès";
        } else {
            // Mise à jour
            $this->projet->update([
                'nom' => $this->nom,
                'description' => $this->description,
            ]);
            $message = "Projet mis à jour avec succès";
        }

        // Ajouter les utilisateurs sélectionnés
        foreach ($this->utilisateurs as $userId) {
            AccesProjet::firstOrCreate([
                'projet_id' => $this->projet->id,
                'user_id' => $userId,
            ]);
        }

        // Ajouter automatiquement le créateur
        AccesProjet::firstOrCreate([
            'projet_id' => $this->projet->id,
            'user_id' => $this->projet->created_by,
        ]);

        // Recharge la relation users pour mise à jour immédiate
        $this->projet->load('users');

        session()->flash('message', $message);
        return redirect()->route('projets.index');
    }

    public function removeUserFromDB($userId)
    {
        // Supprime uniquement la ligne correspondant à ce user
        AccesProjet::where('projet_id', $this->projet->id)
            ->where('user_id', $userId)
            ->delete();

        // Recharge la relation users pour mise à jour de la vue
        $this->projet->load('users');
    }

    public function render()
    {
        $usersWithAccess = collect([]);

        if ($this->projet instanceof Projet) {
            // Recharge la relation users si nécessaire
            $this->projet->loadMissing('users');
            $usersWithAccess = $this->projet->users;
        }

        return view('livewire.projet-form', [
            'usersWithAccess' => $usersWithAccess,
            'projet' => $this->projet,
        ]);
    }
}
