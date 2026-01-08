<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bantuan;
use App\Models\Penerima;
use App\Exports\BantuanExport;
use App\Exports\BantuanIndexExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class BantuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bantuan::with('penerimas');

        // Filter by year
        if ($request->has('export_year') && !empty($request->export_year)) {
            $query->whereYear('tanggal', $request->export_year);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_bantuan', 'like', '%' . $searchTerm . '%')
                  ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        // Filter by recipient count
        if ($request->has('recipient_filter') && !empty($request->recipient_filter)) {
            switch ($request->recipient_filter) {
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

        // Order and paginate
        $bantuan = $query->latest()->paginate(10)->withQueryString();

        return view('bantuan.index', compact('bantuan'));
    }

    /**
     * Show form for creating a new resource.
     */
    public function create()
    {
        return view('bantuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bantuan' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:10|regex:/^[a-zA-Z0-9\s\-\.\,\:\;\(\)\/\&\@\!\?]+$/',
            'tanggal' => 'required|date|before_or_equal:today',
        ], [
            'nama_bantuan.required' => 'Nama bantuan wajib diisi',
            'nama_bantuan.max' => 'Nama bantuan maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'deskripsi.regex' => 'Deskripsi mengandung karakter tidak valid',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini',
        ]);

        $bantuan = Bantuan::create($request->all());

        return redirect()->route('bantuan.index')
                        ->with('success', 'Data bantuan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bantuan = Bantuan::with('penerimas')->findOrFail($id);
        return view('bantuan.show', compact('bantuan'));
    }

    /**
     * Show form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bantuan = Bantuan::findOrFail($id);
        return view('bantuan.edit', compact('bantuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_bantuan' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:10|regex:/^[a-zA-Z0-9\s\-\.\,\:\;\(\)\/\&\@\!\?]+$/',
            'tanggal' => 'required|date|before_or_equal:today',
        ], [
            'nama_bantuan.required' => 'Nama bantuan wajib diisi',
            'nama_bantuan.max' => 'Nama bantuan maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'deskripsi.regex' => 'Deskripsi mengandung karakter tidak valid',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini',
        ]);

        $bantuan = Bantuan::findOrFail($id);
        $bantuan->update($request->all());

        return redirect()->route('bantuan.index')
                        ->with('success', 'Data bantuan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bantuan = Bantuan::findOrFail($id);
        $bantuan->delete();

        return redirect()->route('bantuan.index')
                        ->with('success', 'Data bantuan berhasil dihapus.');
    }

    /**
     * Attach a recipient to a bantuan
     */
    public function attachPenerima(Request $request, Bantuan $bantuan, Penerima $penerima)
    {
        $request->validate([
            'tanggal_diberikan' => 'nullable|date',
        ]);

        // Check if penerima can receive bantuan based on desil (only 1-5 eligible)
        $canReceiveBantuan = $penerima->desil >= 1 && $penerima->desil <= 5;

        if (!$canReceiveBantuan) {
            return redirect()->route('bantuan.show', $bantuan)
                            ->with('error', 'Penerima dengan desil kosong atau 6-10 tidak dapat menerima bantuan. Hanya penerima dengan desil 1-5 yang dapat menerima bantuan.');
        }

        $bantuan->attachPenerima(
            $penerima->id,
            $request->tanggal_diberikan
        );

        return redirect()->route('bantuan.show', $bantuan)
                        ->with('success', 'Penerima berhasil ditambahkan ke program bantuan.');
    }

    /**
     * Detach a recipient from bantuan
     */
    public function detachPenerima(Bantuan $bantuan, Penerima $penerima)
    {
        $bantuan->detachPenerima($penerima->id);

        return redirect()->route('bantuan.show', $bantuan)
                        ->with('success', 'Penerima berhasil dihapus dari program bantuan.');
    }

    /**
     * Show form to add multiple recipients to bantuan
     */
    public function addPenerimas(Bantuan $bantuan)
    {
        // Get recipients not already attached to this bantuan
        $attachedPenerimaIds = $bantuan->penerimas->pluck('id');
        $availablePenerimas = Penerima::whereNotIn('id', $attachedPenerimaIds)
            ->whereBetween('desil', [1, 5])
            ->get();

        return view('bantuan.add-penerimas', compact('bantuan', 'availablePenerimas'));
    }

    /**
     * Store multiple recipients for bantuan
     */
    public function storePenerimas(Request $request, Bantuan $bantuan)
    {
        $request->validate([
            'penerima_ids' => 'required|array',
            'penerima_ids.*' => 'exists:penerima,id',
            'tanggal_diberikan' => 'nullable|date',
        ]);

        // Filter out penerimas with desil 6-10 or null (cannot receive bantuan)
        $validPenerimaIds = [];
        $invalidPenerimaIds = [];

        foreach ($request->penerima_ids as $penerimaId) {
            $penerima = Penerima::find($penerimaId);

            // Check if penerima can receive bantuan (desil 1-5 only)
            if ($penerima->desil >= 1 && $penerima->desil <= 5) {
                $validPenerimaIds[] = $penerimaId;
            } else {
                $invalidPenerimaIds[] = $penerima->nama;
            }
        }

        // Attach only valid penerimas
        foreach ($validPenerimaIds as $penerimaId) {
            $bantuan->attachPenerima(
                $penerimaId,
                $request->tanggal_diberikan
            );
        }

        // Prepare message based on results
        $message = count($validPenerimaIds) . ' penerima berhasil ditambahkan.';
        if (!empty($invalidPenerimaIds)) {
            $message .= ' ' . count($invalidPenerimaIds) . ' penerima dengan desil kosong atau 6-10 tidak dapat menerima bantuan.';
        }

        return redirect()->route('bantuan.show', $bantuan)
                        ->with('success', $message);
    }

    /**
     * Export filtered bantuan data to Excel
     */
    public function exportIndex(Request $request)
    {
        $year = $request->get('export_year');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $search = $request->get('search');
        $recipientFilter = $request->get('recipient_filter');

        $fileName = 'Data_Bantuan';
        if ($year) {
            $fileName .= '_Tahun_' . $year;
        }
        $fileName .= '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new BantuanIndexExport($year, $dateFrom, $dateTo, $search, $recipientFilter), $fileName);
    }

    /**
     * Export single bantuan data to PDF
     */
    public function exportPdf(Bantuan $bantuan)
    {
        $bantuan->load('penerimas');

        // Convert logo to base64
        $logoPath = public_path('dinsos.jpeg');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);

        $html = view('bantuan.pdf', compact('bantuan', 'logoBase64'))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $fileName = 'Bantuan_' . preg_replace('/[^A-Za-z0-9-_]/', '_', $bantuan->nama_bantuan) . '_' . date('Y-m-d') . '.pdf';

        return $dompdf->stream($fileName);
    }
}
