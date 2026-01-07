<?php

namespace App\Exports;

use App\Models\Bantuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BantuanIndexExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $year;
    protected $dateFrom;
    protected $dateTo;
    protected $search;
    protected $recipientFilter;

    public function __construct($year = null, $dateFrom = null, $dateTo = null, $search = null, $recipientFilter = null)
    {
        $this->year = $year;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->search = $search;
        $this->recipientFilter = $recipientFilter;
    }

    /**
     * Return data for export
     */
    public function collection()
    {
        $query = Bantuan::with('penerimas');
        
        // Apply year filter
        if ($this->year) {
            $query->whereYear('tanggal', $this->year);
        }
        
        // Apply date range filter
        if ($this->dateFrom) {
            $query->whereDate('tanggal', '>=', $this->dateFrom);
        }
        
        if ($this->dateTo) {
            $query->whereDate('tanggal', '<=', $this->dateTo);
        }
        
        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_bantuan', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            });
        }
        
        // Apply recipient filter
        if ($this->recipientFilter) {
            switch ($this->recipientFilter) {
                case 'with_recipients':
                    $query->whereHas('penerimas');
                    break;
                case 'without_recipients':
                    $query->whereDoesntHave('penerimas');
                    break;
                case 'high_count':
                    $query->withCount('penerimas')->having('penerimas_count', '>', 10);
                    break;
                case 'low_count':
                    $query->withCount('penerimas')->having('penerimas_count', '<=', 10);
                    break;
            }
        }
        
        $bantuans = $query->get();
        
        // Transform data for export
        $data = collect();
        
        foreach ($bantuans as $index => $bantuan) {
            $data->push([
                'no' => $index + 1,
                'nama_bantuan' => $bantuan->nama_bantuan,
                'deskripsi' => $bantuan->deskripsi,
                'tanggal' => \Carbon\Carbon::parse($bantuan->tanggal)->format('d F Y'),
                'jumlah_penerima' => $bantuan->penerimas->count()
            ]);
        }
        
        return $data;
    }

    /**
     * Map data for export
     */
    public function map($bantuan): array
    {
        return [
            $bantuan['no'],
            $bantuan['nama_bantuan'],
            $bantuan['deskripsi'],
            $bantuan['tanggal'],
            $bantuan['jumlah_penerima']
        ];
    }

    /**
     * Define headings for export
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Bantuan',
            'Deskripsi',
            'Tanggal',
            'Jumlah Penerima'
        ];
    }

    /**
     * Apply styles to worksheet
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FFE0E0E0');
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        
        // Add borders to all cells
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:E' . $highestRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        return [];
    }

    /**
     * Set title of worksheet
     */
    public function title(): string
    {
        $title = 'Data Bantuan';
        
        if ($this->year) {
            $title .= ' Tahun ' . $this->year;
        }
        
        return $title;
    }
}