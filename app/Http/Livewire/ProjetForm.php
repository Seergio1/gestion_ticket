<?php

namespace App\Http\Livewire;

use App\Models\Projet;
use Livewire\Component;


class ProjetForm extends Component
{
    public $nom;
    public $description;

    public function save()
    {

        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Projet::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Projet créé avec succès');
        $this->reset(['nom', 'description']);
        return redirect()->route('projets.index');
    }

    public function render()
    {
        return view('livewire.projet-form');
    }
}
