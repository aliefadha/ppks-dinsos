<?php

namespace App\Exports;

use App\Models\Penerima;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PenerimaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $penerima;

    public function __construct(Penerima $penerima)
    {
        $this->penerima = $penerima;
    }

    /**
     * Return the data for export
     */
    public function collection()
    {
        // Load the recipient with their bantuans
        $this->penerima->load('bantuans');
        
        // Create a collection with recipient info and their bantuans
        $data = collect();
        
        // Add recipient information
        $data->push([
            'type' => 'info',
            'field' => 'Nama Lengkap',
            'value' => $this->penerima->nama
        ]);
        
        $data->push([
            'type' => 'info',
            'field' => 'NIK',
            'value' => $this->penerima->nik
        ]);
        
        $data->push([
            'type' => 'info',
            'field' => 'Jenis Kelamin',
            'value' => $this->penerima->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'
        ]);
        
        $data->push([
            'type' => 'info',
            'field' => 'Alamat',
            'value' => $this->penerima->alamat
        ]);
        
        $data->push([
            'type' => 'info',
            'field' => 'Kelurahan',
            'value' => $this->penerima->kelurahan
        ]);
        
        $data->push([
            'type' => 'info',
            'field' => 'Kecamatan',
            'value' => $this->penerima->kecamatan
        ]);
        
        $data->push([
            'type' => 'info',
            'field' => 'Jenis',
            'value' => $this->penerima->jenis
        ]);
        
        $data->push([
            'type' => 'info',
            'field' => 'Jumlah Program Bantuan',
            'value' => $this->penerima->bantuans->count() . ' program'
        ]);
        
        // Add empty row as separator
        $data->push([
            'type' => 'separator',
            'field' => '',
            'value' => ''
        ]);
        
        // Add bantuan data if exists
        if ($this->penerima->bantuans->count() > 0) {
            $data->push([
                'type' => 'header',
                'field' => 'DAFTAR PROGRAM BANTUAN YANG DITERIMA',
                'value' => ''
            ]);
            
            foreach ($this->penerima->bantuans as $index => $bantuan) {
                $data->push([
                    'type' => 'bantuan',
                    'field' => $index + 1,
                    'value' => $bantuan->nama_bantuan,
                    'deskripsi' => $bantuan->deskripsi,
                    'tanggal' => \Carbon\Carbon::parse($bantuan->tanggal)->format('d F Y'),
                    'tanggal_diberikan' => $bantuan->pivot->tanggal_diberikan ? \Carbon\Carbon::parse($bantuan->pivot->tanggal_diberikan)->format('d F Y') : '-'
                ]);
            }
        } else {
            $data->push([
                'type' => 'no_data',
                'field' => 'Penerima ini belum terdaftar dalam program bantuan mana pun.',
                'value' => ''
            ]);
        }
        
        return $data;
    }

    /**
     * Map the data for export
     */
    public function map($row): array
    {
        if ($row['type'] === 'bantuan') {
            return [
                $row['field'], // No
                $row['value'], // Nama Bantuan
                $row['deskripsi'], // Deskripsi
                $row['tanggal'], // Tanggal Bantuan
                $row['tanggal_diberikan'] // Tanggal Diberikan
            ];
        }
        
        if ($row['type'] === 'header') {
            return [
                $row['field'],
                '',
                '',
                '',
                ''
            ];
        }
        
        if ($row['type'] === 'no_data') {
            return [
                $row['field'],
                '',
                '',
                '',
                ''
            ];
        }
        
        if ($row['type'] === 'separator') {
            return [
                '',
                '',
                '',
                '',
                ''
            ];
        }
        
        // Default for info type
        return [
            $row['field'],
            $row['value'],
            '',
            '',
            ''
        ];
    }

    /**
     * Define the headings for the export
     */
    public function headings(): array
    {
        return [
            'Keterangan',
            'Detail',
            'Deskripsi',
            'Tanggal Bantuan',
            'Tanggal Diberikan'
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FFE0E0E0');
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        
        // Find and style the bantuan header
        $highestRow = $sheet->getHighestRow();
        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();
            if ($cellValue === 'DAFTAR PROGRAM BANTUAN YANG DITERIMA') {
                $sheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
                $sheet->getStyle('A' . $row . ':E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $row . ':E' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('A' . $row . ':E' . $row)->getFill()->getStartColor()->setARGB('FFDDDDDD');
                break;
            }
        }
        
        // Add borders to all cells
        $sheet->getStyle('A1:E' . $highestRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        return [];
    }

    /**
     * Set the title of the worksheet
     */
    public function title(): string
    {
        return 'Data Penerima - ' . $this->penerima->nama;
    }
}