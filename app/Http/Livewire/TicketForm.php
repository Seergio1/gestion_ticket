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
    public $module = '';
    public $scenario = '';
    public $etat = 'en cours';
    public $status = 'ok';
    public $commentaire;
    public $fichiers = [];
    public $fichiersExistants = [];

    public $projet;

    protected $rules = [
        'module' => 'required|string|max:255',
        'scenario' => 'required|string',
        'etat' => 'required|in:en cours,terminé',
        'status' => 'required|in:ok,à améliorer,non ok',
        'commentaire' => 'nullable|string',
        'fichiers.*' => 'file|max:10240', // 10MB max
    ];

    public function mount($projet, $ticket = null)
    {
        $this->projet = Projet::findOrFail($projet);

        if ($ticket) {
            $ticket = Ticket::findOrFail($ticket);
            Log::info($ticket);
            $this->ticketId = $ticket->id;
            $this->module = $ticket->module;
            $this->scenario = $ticket->scenario;
            $this->etat = $ticket->etat;
            $this->status = $ticket->status;
            $this->commentaire = $ticket->commentaire;
            $this->fichiersExistants = $ticket->fichiers ?? [];
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
        if ($this->ticketId) {
            $ticket = Ticket::findOrFail($this->ticketId);
        } else {
            $ticket = new Ticket();
            $ticket->projet_id = $this->projet->id;
            $ticket->created_by = Auth::id();
        }

        $ancienFichiers = $ticket->fichiers ?? [];
        foreach ($ancienFichiers as $file) {
            if (!in_array($file, $this->fichiersExistants)) {
                Storage::disk('public')->delete($file);
            }
        }

        $ticket->module = $this->module;
        $ticket->scenario = $this->scenario;
        $ticket->etat = $this->etat;
        $ticket->status = $this->status;
        $ticket->commentaire = $this->commentaire;
        $ticket->fichiers = $this->fichiersExistants;

        $ticket->save();

        session()->flash('message', $this->ticketId ? 'Ticket mis à jour !' : 'Ticket créé !');

        return redirect()->route('tickets.index', $this->projet->id);
    }

    public function removeExistingFile($index)
    {
        unset($this->fichiersExistants[$index]);
        $this->fichiersExistants = array_values($this->fichiersExistants);
    }

    public function render()
    {
        return view('livewire.ticket-form');
    }
}
