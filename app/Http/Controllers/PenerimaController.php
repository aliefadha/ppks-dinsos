<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerima;
use App\Models\Bantuan;

class PenerimaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penerima = Penerima::with('bantuans')->latest()->paginate(10);
        return view('penerima.index', compact('penerima'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penerima.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.\']+$/',
            'nik' => 'required|string|regex:/^[0-9]{16}$/|unique:penerima,nik',
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

        $penerima = Penerima::create($request->only([
            'nama', 'nik', 'alamat', 'kelurahan', 'kecamatan', 'jenis', 'jenis_kelamin'
        ]));
        
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
            'nama', 'nik', 'alamat', 'kelurahan', 'kecamatan', 'jenis', 'jenis_kelamin'
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
        // Get bantuans not already attached to this recipient
        $attachedBantuanIds = $penerima->bantuans->pluck('id');
        $availableBantuans = Bantuan::whereNotIn('id', $attachedBantuanIds)->get();

        return view('penerima.add-bantuans', compact('penerima', 'availableBantuans'));
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
}
