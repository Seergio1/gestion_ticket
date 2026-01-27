<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ticket;
use App\Models\Projet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TicketForm extends Component
{
    use WithFileUploads;

    public $ticketId;

    public $etat = 'en cours';
    public $status = 'ok';
    public $commentaire;
    public $fichiers = [];
    public $fichiersExistants = [];

    public $fichiersASupprimer = [];


    public $modules = [];
    public $fonctionnalites = [];
    public $module_id = null;
    public $fonctionnalite_id = null;
    public $fonctionnalite_description = '';

    public $projet;

    protected $rules = [
        'module_id' => 'required|exists:modules,id',
        'fonctionnalite_id' => 'required|exists:fonctionnalites,id',
        // 'scenario' => 'required|string',
        'etat' => 'required|in:en cours,terminé',
        'status' => 'required|in:ok,à améliorer,non ok',
        'commentaire' => 'nullable|string',
        'fichiers.*' => 'file|max:10240', // 10MB max
    ];

    public function mount($projet, $ticket = null)
    {
        $this->projet = Projet::findOrFail($projet);

        $this->modules = $this->projet->modules()->get();

        if ($this->modules->isEmpty()) {
            $this->addError('module_id', 'Aucun module défini pour ce projet. Impossible de créer un ticket.');
        }

        if ($ticket) {
            $ticket = Ticket::findOrFail($ticket);
            Log::info($ticket);
            $this->ticketId = $ticket->id;
            $this->module_id = $ticket->module_id ?? null;
            $this->fonctionnalite_id = $ticket->fonctionnalite_id ?? null;
            // $this->scenario = $ticket->scenario;
            $this->etat = $ticket->etat;
            $this->status = $ticket->status;
            $this->commentaire = $ticket->commentaire;
            $this->fichiersExistants = $ticket->fichiers ?? [];


            // Charger les fonctionnalités si module_id existant
            if ($this->module_id) {
                $this->fonctionnalites = $this->modules->where('id', $this->module_id)->first()->fonctionnalites ?? [];
            }

            if ($this->fonctionnalite_id) {
                $this->fonctionnalite_description = $this->fonctionnalites->where('id', $this->fonctionnalite_id)->first()->description ?? '';
            }
        }
    }

    // Déclenché automatiquement quand un fichier est ajouté
    public function updatedFichiers()
    {
        foreach ($this->fichiers as $file) {
            $path = $file->store('fichiers', 'public');
            $this->fichiersExistants[] = $path;
        }

        // Nettoyage pour éviter les doublons et forcer Livewire à rafraîchir la liste
        $this->fichiers = [];
    }

    public function save()
    {
        //     if (!$this->ticketId && Auth::user()->role !== 'admin') {
        //         session()->flash('message', 'Vous n’êtes pas autorisé à créer un ticket.');
        //         return redirect()->route('tickets.index', $this->projet->id);
        //     }


        // Vérification avant création
        if ($this->modules->isEmpty()) {
            $this->addError('module_id', 'Impossible de créer un ticket : aucun module disponible.');
            return;
        }

        if (!$this->fonctionnalites || $this->fonctionnalites->isEmpty()) {
            $this->addError('fonctionnalite_id', 'Impossible de créer un ticket : le module sélectionné n’a aucune fonctionnalité.');
            return;
        }

        if (!$this->ticketId && Auth::user()->role !== 'admin') {
            $this->addError('general', 'Vous n’êtes pas autorisé à créer un ticket.');
            return;
        }

        $this->validate();

        $ticket = $this->ticketId
            ? Ticket::findOrFail($this->ticketId)
            : new Ticket(['projet_id' => $this->projet->id, 'created_by' => Auth::id()]);

        // Empêche les non-admins de modifier module et scenario
        if (Auth::user()->role === 'admin') {
            $ticket->module_id = $this->module_id;
            $ticket->fonctionnalite_id = $this->fonctionnalite_id;
        }

        // historique modification ticket
        if ($this->ticketId) {
            \App\Models\TicketHistory::create([
                'ticket_id' => $ticket->id,
                'projet_id' => $ticket->projet_id,
                'module_id' => $ticket->module_id,
                'fonctionnalite_id' => $ticket->fonctionnalite_id,
                'etat' => $ticket->etat,
                'status' => $ticket->status,
                'commentaire' => $ticket->commentaire,
                'fichiers' => $ticket->fichiers,
                'updated_by' => Auth::id(),
            ]);
        }


        $ticket->etat = $this->etat;
        $ticket->status = $this->status;
        $ticket->commentaire = $this->commentaire;
        $ticket->fichiers = $this->fichiersExistants;
        $ticket->updated_by = Auth::id();

        $ticket->save();

        // Supprimer les fichiers marqués pour suppression
        if (!empty($this->fichiersASupprimer)) {
            foreach ($this->fichiersASupprimer as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        }

        session()->flash('message', $this->ticketId ? 'Ticket mis à jour !' : 'Ticket créé !');

        return redirect()->route('tickets.index', $this->projet->id);
    }

    public function removeExistingFile($index)
    {
        if (isset($this->fichiersExistants[$index])) {
            $this->fichiersASupprimer[] = $this->fichiersExistants[$index];
            unset($this->fichiersExistants[$index]);
            $this->fichiersExistants = array_values($this->fichiersExistants);
        }
    }

    public function render()
    {
        return view('livewire.ticket-form');
    }

    public function updatedModuleId($module_id)
    {
        $module = $this->modules->where('id', $module_id)->first();
        $this->fonctionnalites = $module ? $module->fonctionnalites : [];
        $this->fonctionnalite_id = null;
        $this->fonctionnalite_description = '';

        if ($this->fonctionnalites->isEmpty()) {
            $this->addError('fonctionnalite_id', 'Le module sélectionné n’a aucune fonctionnalité.');
        } else {
            $this->resetErrorBag('fonctionnalite_id');
        }
    }

    public function updatedFonctionnaliteId($fonctionnalite_id)
    {
        $fonctionnalite = $this->fonctionnalites->where('id', $fonctionnalite_id)->first();
        $this->fonctionnalite_description = $fonctionnalite->description ?? '';
    }
}
