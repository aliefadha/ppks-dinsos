<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bantuan;
use App\Models\Penerima;

class BantuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bantuan = Bantuan::with('penerimas')->latest()->paginate(10);
        return view('bantuan.index', compact('bantuan'));
    }

    /**
     * Show the form for creating a new resource.
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
        
        // Get all existing penerima records
        $allPenerimas = Penerima::all();
        
        // Attach all penerima to this bantuan with the bantuan's date as tanggal_diberikan
        foreach ($allPenerimas as $penerima) {
            $bantuan->attachPenerima($penerima->id, $bantuan->tanggal);
        }
        
        return redirect()->route('bantuan.index')
                        ->with('success', 'Data bantuan berhasil ditambahkan dan telah ditautkan ke semua penerima.');
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
     * Show the form for editing the specified resource.
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

        $bantuan->attachPenerima(
            $penerima->id,
            $request->tanggal_diberikan
        );

        return redirect()->route('bantuan.show', $bantuan)
                        ->with('success', 'Penerima berhasil ditambahkan ke program bantuan.');
    }

    /**
     * Detach a recipient from a bantuan
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
        $availablePenerimas = Penerima::whereNotIn('id', $attachedPenerimaIds)->get();

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

        foreach ($request->penerima_ids as $penerimaId) {
            $bantuan->attachPenerima(
                $penerimaId,
                $request->tanggal_diberikan
            );
        }

        return redirect()->route('bantuan.show', $bantuan)
                        ->with('success', count($request->penerima_ids) . ' penerima berhasil ditambahkan.');
    }
}
