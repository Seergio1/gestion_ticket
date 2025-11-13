<?php

namespace App\Http\Livewire;

use App\Models\Fonctionnalite;
use App\Models\Module;
use Livewire\Component;

class FonctionnaliteList extends Component
{
    public $module;

    public function mount(Module $module)
    {
        $this->module = $module;
    }

    public function render()
    {
        $fonctionnalites = Fonctionnalite::where('module_id', $this->module->id)->get();

        return view('livewire.fonctionnalite-list', [
            'module' => $this->module,
            'fonctionnalites' => $fonctionnalites,
        ]);
    }

    public function deleteFonctionnalite($id)
    {
        $fonctionnalite = Fonctionnalite::findOrFail($id);
        $fonctionnalite->delete();
        session()->flash('message', 'Fonctionnalité supprimée avec succès');
    }
}
