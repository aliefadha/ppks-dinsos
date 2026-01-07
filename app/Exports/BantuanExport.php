<?php

namespace App\Exports;

use App\Models\Bantuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BantuanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $bantuan;

    public function __construct(Bantuan $bantuan)
    {
        $this->bantuan = $bantuan;
    }

    /**
     * Return the data for export
     */
    public function collection()
    {
        // Load the bantuan with their penerimas
        $this->bantuan->load('penerimas');
        
        // Create a collection with bantuan info and their penerimas
        $data = collect();
        
        // Add bantuan information
        $data->push([
            'type' => 'info',
            'field' => 'Nama Bantuan',
            'value' => $this->bantuan->nama_bantuan
        ]);
        
        $data->push([
            'type' => 'info',
            'field' => 'Tanggal',
            'value' => \Carbon\Carbon::parse($this->bantuan->tanggal)->format('d F Y')
        ]);
        
        $data->push([
            'type' => 'info',
            'field' => 'Deskripsi',
            'value' => $this->bantuan->deskripsi
        ]);
        
        $data->push([
            'type' => 'info',
            'field' => 'Jumlah Penerima',
            'value' => $this->bantuan->penerimas->count() . ' orang'
        ]);
        
        // Add empty row as separator
        $data->push([
            'type' => 'separator',
            'field' => '',
            'value' => ''
        ]);
        
        // Add penerima data if exists
        if ($this->bantuan->penerimas->count() > 0) {
            $data->push([
                'type' => 'header',
                'field' => 'DAFTAR PENERIMA PROGRAM BANTUAN',
                'value' => ''
            ]);
            
            foreach ($this->bantuan->penerimas as $index => $penerima) {
                $data->push([
                    'type' => 'penerima',
                    'field' => $index + 1,
                    'value' => $penerima->nama,
                    'nik' => $penerima->nik,
                    'alamat' => $penerima->alamat,
                    'kelurahan' => $penerima->kelurahan,
                    'kecamatan' => $penerima->kecamatan,
                    'jenis' => $penerima->jenis,
                    'jenis_kelamin' => $penerima->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                    'tanggal_diberikan' => $penerima->pivot->tanggal_diberikan ? \Carbon\Carbon::parse($penerima->pivot->tanggal_diberikan)->format('d F Y') : '-'
                ]);
            }
            
            // Add summary row
            $data->push([
                'type' => 'summary',
                'field' => 'Total Penerima',
                'value' => $this->bantuan->penerimas->count() . ' orang'
            ]);
        } else {
            $data->push([
                'type' => 'no_data',
                'field' => 'Belum ada penerima untuk program bantuan ini.',
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
        if ($row['type'] === 'penerima') {
            return [
                $row['field'], // No
                $row['value'], // Nama
                $row['nik'], // NIK
                $row['alamat'], // Alamat
                $row['kelurahan'], // Kelurahan
                $row['kecamatan'], // Kecamatan
                $row['jenis'], // Jenis
                $row['jenis_kelamin'], // Jenis Kelamin
                $row['tanggal_diberikan'] // Tanggal Diberikan
            ];
        }
        
        if ($row['type'] === 'header') {
            return [
                $row['field'],
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ];
        }
        
        if ($row['type'] === 'summary') {
            return [
                $row['field'],
                $row['value'],
                '',
                '',
                '',
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
                '',
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
            '',
            '',
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
            'NIK',
            'Alamat',
            'Kelurahan',
            'Kecamatan',
            'Jenis',
            'Jenis Kelamin',
            'Tanggal Diberikan'
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('FFE0E0E0');
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(20);
        
        // Find and style the penerima header
        $highestRow = $sheet->getHighestRow();
        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();
            if ($cellValue === 'DAFTAR PENERIMA PROGRAM BANTUAN') {
                $sheet->getStyle('A' . $row . ':I' . $row)->getFont()->setBold(true);
                $sheet->getStyle('A' . $row . ':I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $row . ':I' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('A' . $row . ':I' . $row)->getFill()->getStartColor()->setARGB('FFDDDDDD');
                break;
            }
        }
        
        // Find and style the summary row
        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();
            if ($cellValue === 'Total Penerima') {
                $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
                $sheet->getStyle('A' . $row . ':B' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('A' . $row . ':B' . $row)->getFill()->getStartColor()->setARGB('FFF0F0F0');
                break;
            }
        }
        
        // Add borders to all cells
        $sheet->getStyle('A1:I' . $highestRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        return [];
    }

    /**
     * Set the title of the worksheet
     */
    public function title(): string
    {
        return 'Data Bantuan - ' . $this->bantuan->nama_bantuan;
    }
}