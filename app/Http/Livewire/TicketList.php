<?php

namespace App\Http\Livewire;

use App\Exports\TicketsExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Projet;
use Livewire\Component;


class TicketList extends Component
{
    public $projet;

    public $etat = '';
    public $status = '';
    public $dateDebut;
    public $dateFin;


    public function mount(Projet $projet)
    {
        $this->projet = $projet;
    }

    public function deleteTicket($ticketId)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $ticket = \App\Models\Ticket::findOrFail($ticketId);
        $ticket->delete();
        session()->flash('message', 'Ticket supprimé avec succès');
    }


    public function render()
    {
        $query = $this->projet->tickets()->with('creator');

        if ($this->etat !== '') {
            $query->where('etat', $this->etat);
        }

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        if ($this->dateDebut && $this->dateFin) {
            $query->whereBetween('created_at', [$this->dateDebut, $this->dateFin]);
        }

        $tickets = $query->orderBy('created_at', 'asc')->get();

        return view('livewire.ticket-list', [
            'tickets' => $tickets
        ]);
    }

    public function exportExcel()
    {
        $query = $this->projet->tickets()->with('creator');

        if ($this->etat !== '') {
            $query->where('etat', $this->etat);
        }

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        if ($this->dateDebut && $this->dateFin) {
            $query->whereBetween('created_at', [$this->dateDebut, $this->dateFin]);
        }

        $tickets = $query->orderBy('created_at', 'asc')->get();

        return Excel::download(new TicketsExport($tickets, $this->projet->nom), 'Recettage_' . $this->projet->nom . '.xlsx');
    }

    public function resetFilters()
    {
        $this->etat = '';
        $this->status = '';
        $this->dateDebut = null;
        $this->dateFin = null;
        $this->render();
    }
}
