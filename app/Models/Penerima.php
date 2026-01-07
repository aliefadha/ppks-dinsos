<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerima extends Model
{
    use HasFactory;
    protected $table = 'penerima';
    
    protected $fillable = [
        'nama',
        'nik',
        'no_kk',
        'desil',
        'alamat',
        'kelurahan',
        'kecamatan',
        'jenis',
        'jenis_kelamin',
    ];

    
    /**
     * New many-to-many relationship
     */
    public function bantuans()
    {
        return $this->belongsToMany(Bantuan::class, 'bantuan_penerima')
                    ->withPivot('tanggal_diberikan')
                    ->withTimestamps();
    }
    
    /**
     * Helper method to attach a bantuan to this recipient
     */
    public function attachBantuan($bantuanId, $tanggalDiberikan = null)
    {
        return $this->bantuans()->attach($bantuanId, [
            'tanggal_diberikan' => $tanggalDiberikan ?? now()->format('Y-m-d'),
        ]);
    }
    
    /**
     * Helper method to detach a bantuan from this recipient
     */
    public function detachBantuan($bantuanId)
    {
        return $this->bantuans()->detach($bantuanId);
    }
    
    /**
     * Helper method to sync bantuans for this recipient
     */
    public function syncBantuans($bantuanIds)
    {
        return $this->bantuans()->sync($bantuanIds);
    }
}
