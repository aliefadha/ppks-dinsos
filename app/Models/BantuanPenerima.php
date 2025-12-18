<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BantuanPenerima extends Model
{
    protected $table = 'bantuan_penerima';
    
    protected $fillable = [
        'bantuan_id',
        'penerima_id',
        'tanggal_diberikan',
    ];
    
    protected $casts = [
        'tanggal_diberikan' => 'date',
    ];
    
    /**
     * Get the bantuan that owns this relationship.
     */
    public function bantuan()
    {
        return $this->belongsTo(Bantuan::class);
    }
    
    /**
     * Get the penerima that owns this relationship.
     */
    public function penerima()
    {
        return $this->belongsTo(Penerima::class);
    }
}
