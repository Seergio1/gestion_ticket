<?php

namespace App\Http\Livewire;

use App\Models\Module;
use App\Models\Projet;
use Livewire\Component;

class ModuleForm extends Component
{
    public $projet_id;
    public $nom;
    public $description;

    public function mount($projet_id)
    {
        $this->projet_id = $projet_id;
    }

    public function save()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Module::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'projet_id' => $this->projet_id
        ]);

        session()->flash('message', 'Module créé avec succès.');
        $this->reset(['nom', 'description']);
        // return redirect()->route('modules.index', $this->projet_id);
    }

    public function render()
    {
        $projet = Projet::findOrFail($this->projet_id);
        return view('livewire.module-form', compact('projet'));
    }
}
