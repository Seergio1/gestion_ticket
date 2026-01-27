<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\TicketHistory as TicketHistoryModel;

class TicketHistory extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $ticket;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function render()
    {
        $history = TicketHistoryModel::with(['module', 'fonctionnalite', 'user'])
            ->where('ticket_id', $this->ticket->id)
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('livewire.ticket-history', [
            'history' => $history
        ]);
    }
}
