<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerima;
use App\Models\Bantuan;
use App\Exports\PenerimaExport;
use Maatwebsite\Excel\Facades\Excel;

class PenerimaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penerima::with('bantuans');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nik', 'like', '%' . $searchTerm . '%')
                  ->orWhere('no_kk', 'like', '%' . $searchTerm . '%')
                  ->orWhere('alamat', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kelurahan', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kecamatan', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Filter by jenis_kelamin
        if ($request->has('jenis_kelamin') && !empty($request->jenis_kelamin)) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        
        // Filter by jenis
        if ($request->has('jenis') && !empty($request->jenis)) {
            $query->where('jenis', 'like', '%' . $request->jenis . '%');
        }
        
        // Filter by kelurahan
        if ($request->has('kelurahan') && !empty($request->kelurahan)) {
            $query->where('kelurahan', 'like', '%' . $request->kelurahan . '%');
        }
        
        // Filter by kecamatan
        if ($request->has('kecamatan') && !empty($request->kecamatan)) {
            $query->where('kecamatan', 'like', '%' . $request->kecamatan . '%');
        }
        
        // Get unique values for filter dropdowns
        $jenisOptions = Penerima::distinct()->pluck('jenis')->filter()->sort()->values();
        $kelurahanOptions = Penerima::distinct()->pluck('kelurahan')->filter()->sort()->values();
        $kecamatanOptions = Penerima::distinct()->pluck('kecamatan')->filter()->sort()->values();
        
        // Order and paginate
        $penerima = $query->latest()->paginate(10)->withQueryString();
        
        return view('penerima.index', compact(
            'penerima',
            'jenisOptions',
            'kelurahanOptions',
            'kecamatanOptions'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $availableBantuans = Bantuan::all();
        return view('penerima.create', compact('availableBantuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.\']+$/',
            'nik' => 'required|string|regex:/^[0-9]{16}$/|unique:penerima,nik',
            'no_kk' => 'nullable|string|regex:/^[0-9]{16}$/',
            'desil' => 'required|integer|between:1,10',
            'alamat' => 'required|string|min:5|regex:/^[a-zA-Z0-9\s\-\.\,\#\:\;\(\)\/]+$/',
            'kelurahan' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.\,\:\;\(\)\/]+$/',
            'kecamatan' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.\,\:\;\(\)\/]+$/',
            'jenis' => 'required|string|max:50|regex:/^[a-zA-Z\s\-\.\,\:\;\(\)\/\&\@\!\?]+$/',
            'jenis_kelamin' => 'required|in:L,P',
            'bantuan_ids' => 'nullable|array',
            'bantuan_ids.*' => 'exists:bantuan,id',
            'tanggal_diberikan' => 'nullable|date',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'nama.regex' => 'Nama hanya boleh berisi huruf, spasi, tanda hubung (-), titik (.), dan apostrof (\')',
            'nama.max' => 'Nama maksimal 255 karakter',
            'nik.required' => 'NIK wajib diisi',
            'nik.regex' => 'NIK harus 16 digit angka',
            'nik.unique' => 'NIK sudah terdaftar',
            'no_kk.regex' => 'No. KK harus 16 digit angka',
            'desil.required' => 'Desil wajib diisi',
            'desil.between' => 'Desil harus antara 1-10',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.min' => 'Alamat minimal 5 karakter',
            'alamat.regex' => 'Alamat mengandung karakter tidak valid',
            'kelurahan.required' => 'Kelurahan wajib diisi',
            'kelurahan.max' => 'Kelurahan maksimal 255 karakter',
            'kelurahan.regex' => 'Kelurahan hanya boleh berisi huruf, spasi, dan tanda baca yang valid',
            'kecamatan.required' => 'Kecamatan wajib diisi',
            'kecamatan.max' => 'Kecamatan maksimal 255 karakter',
            'kecamatan.regex' => 'Kecamatan hanya boleh berisi huruf, spasi, dan tanda baca yang valid',
            'jenis.required' => 'Jenis wajib diisi',
            'jenis.max' => 'Jenis maksimal 50 karakter',
            'jenis.regex' => 'Jenis mengandung karakter tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'bantuan_ids.*.exists' => 'Program bantuan yang dipilih tidak valid',
        ]);

        // Create the penerima
        $penerima = Penerima::create($request->only([
            'nama', 'nik', 'no_kk', 'desil', 'alamat', 'kelurahan', 'kecamatan', 'jenis', 'jenis_kelamin'
        ]));

        // Attach selected bantuans if any
        if ($request->has('bantuan_ids') && !empty($request->bantuan_ids)) {
            $tanggalDiberikan = $request->tanggal_diberikan ?? now()->format('Y-m-d');
            $attachedCount = 0;

            foreach ($request->bantuan_ids as $bantuanId) {
                $penerima->attachBantuan($bantuanId, $tanggalDiberikan);
                $attachedCount++;
            }

            $message = 'Data penerima berhasil ditambahkan.';
            if ($attachedCount > 0) {
                $message .= " {$attachedCount} program bantuan berhasil ditambahkan.";
            }

            return redirect()->route('penerima.show', $penerima)
                            ->with('success', $message);
        }

        return redirect()->route('penerima.show', $penerima)
                        ->with('success', 'Data penerima berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penerima = Penerima::with('bantuans')->findOrFail($id);
        return view('penerima.show', compact('penerima'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penerima = Penerima::findOrFail($id);
        return view('penerima.edit', compact('penerima'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.\']+$/',
            'nik' => 'required|string|regex:/^[0-9]{16}$/|unique:penerima,nik,' . $id,
            'no_kk' => 'nullable|string|regex:/^[0-9]{16}$/',
            'desil' => 'required|integer|between:1,10',
            'alamat' => 'required|string|min:5|regex:/^[a-zA-Z0-9\s\-\.\,\#\:\;\(\)\/]+$/',
            'kelurahan' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.\,\:\;\(\)\/]+$/',
            'kecamatan' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.\,\:\;\(\)\/]+$/',
            'jenis' => 'required|string|max:50|regex:/^[a-zA-Z\s\-\.\,\:\;\(\)\/\&\@\!\?]+$/',
            'jenis_kelamin' => 'required|in:L,P',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'nama.regex' => 'Nama hanya boleh berisi huruf, spasi, tanda hubung (-), titik (.), dan apostrof (\')',
            'nama.max' => 'Nama maksimal 255 karakter',
            'nik.required' => 'NIK wajib diisi',
            'nik.regex' => 'NIK harus 16 digit angka',
            'nik.unique' => 'NIK sudah terdaftar',
            'no_kk.regex' => 'No. KK harus 16 digit angka',
            'desil.required' => 'Desil wajib diisi',
            'desil.between' => 'Desil harus antara 1-10',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.min' => 'Alamat minimal 5 karakter',
            'alamat.regex' => 'Alamat mengandung karakter tidak valid',
            'kelurahan.required' => 'Kelurahan wajib diisi',
            'kelurahan.max' => 'Kelurahan maksimal 255 karakter',
            'kelurahan.regex' => 'Kelurahan hanya boleh berisi huruf, spasi, dan tanda baca yang valid',
            'kecamatan.required' => 'Kecamatan wajib diisi',
            'kecamatan.max' => 'Kecamatan maksimal 255 karakter',
            'kecamatan.regex' => 'Kecamatan hanya boleh berisi huruf, spasi, dan tanda baca yang valid',
            'jenis.required' => 'Jenis wajib diisi',
            'jenis.max' => 'Jenis maksimal 50 karakter',
            'jenis.regex' => 'Jenis mengandung karakter tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
        ]);

        $penerima = Penerima::findOrFail($id);
        $penerima->update($request->only([
            'nama', 'nik', 'no_kk', 'desil', 'alamat', 'kelurahan', 'kecamatan', 'jenis', 'jenis_kelamin'
        ]));
        
        return redirect()->route('penerima.show', $penerima)
                        ->with('success', 'Data penerima berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penerima = Penerima::findOrFail($id);
        $penerima->delete();
        
        return redirect()->route('penerima.index')
                        ->with('success', 'Data penerima berhasil dihapus.');
    }

    /**
     * Attach a bantuan to a recipient
     */
    public function attachBantuan(Request $request, Penerima $penerima, Bantuan $bantuan)
    {
        $request->validate([
            'tanggal_diberikan' => 'nullable|date',
        ]);

        // Check if penerima can receive bantuan based on desil
        $canReceiveBantuan = $penerima->desil === null || $penerima->desil >= 1 && $penerima->desil <= 5;

        if (!$canReceiveBantuan) {
            return redirect()->route('penerima.show', $penerima)
                            ->with('error', 'Penerima dengan desil 6-10 tidak dapat menerima bantuan.');
        }

        $penerima->attachBantuan(
            $bantuan->id,
            $request->tanggal_diberikan
        );

        return redirect()->route('penerima.show', $penerima)
                        ->with('success', 'Program bantuan berhasil ditambahkan untuk penerima ini.');
    }

    /**
     * Detach a bantuan from a recipient
     */
    public function detachBantuan(Penerima $penerima, Bantuan $bantuan)
    {
        $penerima->detachBantuan($bantuan->id);

        return redirect()->route('penerima.show', $penerima)
                        ->with('success', 'Program bantuan berhasil dihapus untuk penerima ini.');
    }

    /**
     * Show form to add multiple bantuans to recipient
     */
    public function addBantuans(Penerima $penerima)
    {
        // Get all bantuans (including those already attached)
        $availableBantuans = Bantuan::all();

        return view('penerima.add-bantuans', compact('penerima', 'availableBantuans'));
    }

    /**
     * Attach multiple bantuans to a recipient
     */
    public function attachMultipleBantuans(Request $request, Penerima $penerima)
    {
        $request->validate([
            'bantuan_ids' => 'required|array|min:1',
            'bantuan_ids.*' => 'exists:bantuan,id',
            'tanggal_diberikan' => 'nullable|date',
        ], [
            'bantuan_ids.required' => 'Pilih minimal satu program bantuan',
            'bantuan_ids.min' => 'Pilih minimal satu program bantuan',
        ]);

        // Check if penerima can receive bantuan based on desil
        $canReceiveBantuan = $penerima->desil === null || $penerima->desil >= 1 && $penerima->desil <= 5;

        if (!$canReceiveBantuan) {
            return redirect()->route('penerima.addBantuans', $penerima)
                            ->with('error', 'Penerima dengan desil 6-10 tidak dapat menerima bantuan.');
        }

        $tanggalDiberikan = $request->tanggal_diberikan ?? now()->format('Y-m-d');
        $attachedCount = 0;
        $skippedCount = 0;

        foreach ($request->bantuan_ids as $bantuanId) {
            // Check if already attached
            if (!$penerima->bantuans()->where('bantuan_id', $bantuanId)->exists()) {
                $penerima->attachBantuan($bantuanId, $tanggalDiberikan);
                $attachedCount++;
            } else {
                $skippedCount++;
            }
        }

        $message = "Berhasil menambahkan {$attachedCount} program bantuan untuk penerima ini.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} program bantuan sudah terdaftar sebelumnya.";
        }

        return redirect()->route('penerima.show', $penerima)
                        ->with('success', $message);
    }

    /**
     * Show form for bulk creating recipients
     */
    public function createBulk()
    {
        return view('penerima.create-bulk');
    }

    /**
     * Store multiple recipients at once
     */
    public function storeBulk(Request $request)
    {
        $penerimas = $request->input('penerimas', []);
        
        if (empty($penerimas)) {
            return redirect()->route('penerima.createBulk')
                            ->with('error', 'Tidak ada data penerima untuk disimpan.');
        }

        $errors = [];
        $successCount = 0;
        
        foreach ($penerimas as $index => $penerimaData) {
            // Skip empty rows
            if (empty($penerimaData['nama']) && empty($penerimaData['nik'])) {
                continue;
            }
            
            $validator = validator()->make($penerimaData, [
                'nama' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.\']+$/',
                'nik' => 'required|string|regex:/^[0-9]{16}$/|unique:penerima,nik',
                'no_kk' => 'nullable|string|regex:/^[0-9]{16}$/',
                'desil' => 'required|integer|between:1,10',
                'alamat' => 'required|string|min:5|regex:/^[a-zA-Z0-9\s\-\.\,\#\:\;\(\)\/]+$/',
                'kelurahan' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.\,\:\;\(\)\/]+$/',
                'kecamatan' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.\,\:\;\(\)\/]+$/',
                'jenis' => 'required|string|max:50|regex:/^[a-zA-Z\s\-\.\,\:\;\(\)\/\&\@\!\?]+$/',
                'jenis_kelamin' => 'required|in:L,P',
            ], [
                'nama.required' => 'Nama wajib diisi',
                'nama.max' => 'Nama maksimal 255 karakter',
                'nik.required' => 'NIK wajib diisi',
                'nik.size' => 'NIK harus 16 digit',
                'nik.unique' => 'NIK sudah terdaftar',
                'no_kk.regex' => 'No. KK harus 16 digit angka',
                'desil.required' => 'Desil wajib diisi',
                'desil.between' => 'Desil harus antara 1-10',
                'alamat.required' => 'Alamat wajib diisi',
                'kelurahan.required' => 'Kelurahan wajib diisi',
                'kelurahan.max' => 'Kelurahan maksimal 255 karakter',
                'kecamatan.required' => 'Kecamatan wajib diisi',
                'kecamatan.max' => 'Kecamatan maksimal 255 karakter',
                'jenis.required' => 'Jenis wajib diisi',
                'jenis.max' => 'Jenis maksimal 50 karakter',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
                'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            ]);

            if ($validator->fails()) {
                $errors["Baris " . ($index + 1)] = $validator->errors()->all();
                continue;
            }

            try {
                Penerima::create($penerimaData);
                $successCount++;
            } catch (\Exception $e) {
                $errors["Baris " . ($index + 1)] = ['Gagal menyimpan data: ' . $e->getMessage()];
            }
        }

        if (!empty($errors)) {
            $errorMessage = "Berhasil menyimpan {$successCount} penerima, tetapi ada kesalahan pada beberapa baris:<br>" . implode('<br>', array_map(function($key, $value) {
                return "{$key}: " . implode(', ', $value);
            }, array_keys($errors), $errors));
            
            return redirect()->route('penerima.createBulk')
                            ->with('error', $errorMessage)
                            ->withInput();
        }

        return redirect()->route('penerima.index')
                        ->with('success', "Berhasil menyimpan {$successCount} data penerima.");
    }

    /**
     * Check if NIK exists in database and return data if found
     */
    public function checkNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|regex:/^[0-9]{16}$/',
        ], [
            'nik.required' => 'NIK wajib diisi',
            'nik.regex' => 'NIK harus 16 digit angka',
        ]);

        $penerima = Penerima::where('nik', $request->nik)->first();

        if ($penerima) {
            return response()->json([
                'found' => true,
                'data' => $penerima
            ]);
        }

        return response()->json(['found' => false]);
    }

    /**
     * Export recipient data to Excel
     */
    public function exportExcel(Penerima $penerima)
    {
        $fileName = 'Data_Penerima_' . str_replace(' ', '_', $penerima->nama) . '_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new PenerimaExport($penerima), $fileName);
    }
}
