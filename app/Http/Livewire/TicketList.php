<?php

namespace App\Http\Livewire;

use App\Exports\TicketsExport;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Projet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class TicketList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $projet;
    public $modules = [];
    public $etat = '';
    public $status = '';

    public $searchFonctionnalite = '';

    public $dateDebut;
    public $dateFin;

    public $module_id = '';


    public function mount(Projet $projet)
    {
        $this->projet = $projet;
        $this->modules = $projet->modules()->orderBy('nom')->get();
    }

    public function deleteTicket($ticketId)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $ticket = \App\Models\Ticket::findOrFail($ticketId);
        $ticket->delete();
        session()->flash('message', 'Ticket supprimé avec succès');
    }


    /**
     * Renders the ticket list view.
     *
     * This method will build a query based on the component's properties
     * and then render the ticket list view with the results of the query.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $query = $this->projet->tickets()->with('creator', 'module', 'fonctionnalite')->withCount('histories');

        // ajouter une recherche sur la fonctionnalité(barre de recherche)
        if (!empty($this->searchFonctionnalite)) {
            $query->whereHas('fonctionnalite', function ($q) {
                $q->where('nom', 'like', '%' . $this->searchFonctionnalite . '%');
            });
        }

        if ($this->etat !== '') {
            $query->where('etat', $this->etat);
        }

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        if ($this->module_id) {
            $query->where('module_id', $this->module_id);
        }

        if ($this->dateDebut && $this->dateFin) {
            $query->whereBetween('created_at', [$this->dateDebut, $this->dateFin]);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('livewire.ticket-list', [
            'tickets' => $tickets
        ]);
    }

    public function updating($name, $value)
    {
        if (in_array($name, ['searchFonctionnalite', 'etat', 'status', 'module_id', 'dateDebut', 'dateFin'])) {
            $this->resetPage();
        }
    }

    public function exportExcel()
    {
        $query = $this->projet->tickets()->with('creator', 'module', 'fonctionnalite');

        if (!empty($this->searchFonctionnalite)) {
            $query->whereHas('fonctionnalite', function ($q) {
                $q->where('nom', 'like', '%' . $this->searchFonctionnalite . '%');
            });
        }

        if ($this->etat !== '') {
            $query->where('etat', $this->etat);
        }

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        if ($this->module_id) {
            $query->where('module_id', $this->module_id);
        }

        if ($this->dateDebut && $this->dateFin) {
            $query->whereBetween('created_at', [$this->dateDebut, $this->dateFin]);
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        return Excel::download(new TicketsExport($tickets, $this->projet->nom), 'Recettage_' . $this->projet->nom . '.xlsx');
    }

    public function resetFilters()
    {
        $this->etat = '';
        $this->status = '';
        $this->module_id = '';
        $this->searchFonctionnalite = '';
        $this->dateDebut = null;
        $this->dateFin = null;
        $this->render();
    }
}
