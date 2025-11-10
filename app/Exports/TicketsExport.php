<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;


class TicketsExport implements FromCollection, WithHeadings, WithEvents, WithTitle
{
    protected $tickets;
    protected $sheetName;

    // On passe les tickets filtrés depuis le Livewire
    public function __construct($tickets, $sheetName = 'Tickets')
    {
        $this->tickets = $tickets;
        $this->sheetName = $sheetName;
    }

    /**
     * Retourne la collection à exporter
     */
    public function collection()
    {
        return $this->tickets->map(function ($ticket) {
            return [
                'ID' => $ticket->id,
                'Module' => $ticket->module,
                'État' => $ticket->etat,
                'Status' => $ticket->status,
                'Scénario' => $ticket->scenario,
                'Commentaire' => $ticket->commentaire,
                'Créé par' => $ticket->creator->name,
                'Date création' => $ticket->created_at->format('Y-m-d H:i'),
            ];
        });
    }

    /**
     * Entêtes de colonnes
     */
    public function headings(): array
    {
        return ['ID', 'Module', 'État', 'Status', 'Scénario', 'Commentaire', 'Créé par', 'Date création'];
    }

    public function title(): string
    {
        return $this->sheetName;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Bordures pour tout le tableau
                $sheet->getStyle('A1:H' . ($this->tickets->count() + 1))
                    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Ajuster la largeur des colonnes
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Couleur titre
                $sheet->getStyle('A1:H1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A1:H1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('4B5563');

                // ↑ Augmenter la hauteur de toutes les lignes
                $sheet->getRowDimension(1)->setRowHeight(35); // Titre
                for ($i = 2; $i <= $this->tickets->count() + 1; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(35); // Contenu
                }
            }
        ];
    }
}
