<?php

namespace App\Http\Livewire;

use App\Models\Module;
use App\Models\Projet;
use Livewire\Component;

class ModuleList extends Component
{
    public $projet;

    public function mount(Projet $projet)
    {
        $this->projet = $projet;
    }

    public function render()
    {
        $modules = Module::where('projet_id', $this->projet->id)
            ->with('projet')
            ->withCount('fonctionnalites')
            ->get();

        return view('livewire.module-list', [
            'modules' => $modules,
            'projet' => $this->projet
        ]);
    }

    public function deleteModule($id)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') abort(403);

        $module = Module::findOrFail($id);
        $module->delete();
        session()->flash('message', 'Module supprimé avec succès');
    }
}
