<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bantuan extends Model
{
    protected $table = 'bantuan';
    
    protected $fillable = [
        'nama_bantuan',
        'deskripsi',
        'tanggal',
    ];

    
    /**
     * New many-to-many relationship
     */
    public function penerimas()
    {
        return $this->belongsToMany(Penerima::class, 'bantuan_penerima')
                    ->withPivot('tanggal_diberikan')
                    ->withTimestamps();
    }
    
    /**
     * Helper method to attach a recipient to this bantuan
     */
    public function attachPenerima($penerimaId, $tanggalDiberikan = null)
    {
        return $this->penerimas()->attach($penerimaId, [
            'tanggal_diberikan' => $tanggalDiberikan ?? now()->format('Y-m-d'),
        ]);
    }
    
    /**
     * Helper method to detach a recipient from this bantuan
     */
    public function detachPenerima($penerimaId)
    {
        return $this->penerimas()->detach($penerimaId);
    }
    
    /**
     * Helper method to sync recipients for this bantuan
     */
    public function syncPenerimas($penerimaIds)
    {
        return $this->penerimas()->sync($penerimaIds);
    }
}
