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

    public $module_id;

    public function mount($projet_id, $module_id = null)
    {
        $this->projet_id = $projet_id;
        $this->module_id = $module_id;
        if ($this->module_id) {
            $module = Module::findOrFail($this->module_id);
            $this->nom = $module->nom;
            $this->description = $module->description;
        }
    }

    public function save()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($this->module_id) {
            // Modification
            $module = Module::findOrFail($this->module_id);
            $module->update([
                'nom' => $this->nom,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Module mis à jour avec succès.');
        } else {
            // Création
            Module::create([
                'nom' => $this->nom,
                'description' => $this->description,
                'projet_id' => $this->projet_id
            ]);
            session()->flash('message', 'Module créé avec succès.');
            $this->reset(['nom', 'description']);
        }
    }

    public function render()
    {
        $projet = Projet::findOrFail($this->projet_id);
        return view('livewire.module-form', compact('projet'));
    }
}
