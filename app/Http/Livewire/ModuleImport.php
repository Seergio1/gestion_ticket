<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Projet;
use App\Models\Module;
use App\Models\Fonctionnalite;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ModuleImport extends Component
{
    use WithFileUploads;

    public $projet_id;
    public $file;

    public function mount($projet_id)
    {
        $this->projet_id = $projet_id;
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $projet = Projet::with('modules.fonctionnalites')->findOrFail($this->projet_id);

        // Vérification : si le projet a déjà des modules/fonctionnalités
        if ($projet->modules()->exists()) {
            session()->flash('error', 'Ce projet possède déjà des modules. Importation non autorisée.');
            return;
        }

        // Lecture du fichier
        try {
            $spreadsheet = IOFactory::load($this->file->getRealPath());
        } catch (\Throwable $e) {
            session()->flash('error', 'Erreur lors de la lecture du fichier Excel : ' . $e->getMessage());
            return;
        }
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
        if (count($rows) <= 1) {
            session()->flash('error', 'Le fichier est vide ou ne contient aucune donnée.');
            return;
        }

        $header = $rows[1];
        if (!isset($header['A'], $header['B'], $header['C'], $header['D'])) {
            session()->flash('error', 'Le fichier ne correspond pas au format attendu (colonnes A à D).');
            return;
        }

        // Ignorer la première ligne (entêtes)
        foreach (array_slice($rows, 1) as $row) {
            $moduleName = trim($row['A']);
            $moduleDesc = trim($row['B']);
            $fonctionName = trim($row['C']);
            $fonctionDesc = trim($row['D']);

            if (!$moduleName) continue;

            // Vérifie si le module existe déjà
            $module = Module::firstOrCreate(
                ['nom' => $moduleName, 'projet_id' => $this->projet_id],
                ['description' => $moduleDesc]
            );

            // Crée la fonctionnalité si elle existe dans le fichier
            if ($fonctionName) {
                Fonctionnalite::firstOrCreate(
                    ['nom' => $fonctionName, 'module_id' => $module->id],
                    ['description' => $fonctionDesc]
                );
            }
        }

        session()->flash('message', 'Importation réussie !');
    }
    public function getFileNameProperty()
    {
        if (!$this->file) return null;

        if (is_array($this->file)) {
            $names = [];
            foreach ($this->file as $f) {
                $names[] = $f->getClientOriginalName();
            }
            return implode(', ', $names);
        }

        return $this->file->getClientOriginalName();
    }

    public function render()
    {
        $projet = Projet::find($this->projet_id);
        return view('livewire.module-import', compact('projet'));
    }
}
