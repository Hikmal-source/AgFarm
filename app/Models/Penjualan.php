<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penjualan extends Model
{
    protected $table = 'penjualan';
   protected $fillable = [
        'user_id',
        'panen_id',
        'jumlah_terjual',
        'total_pendapatan',
        'total_profit',
        'tanggal_penjualan'
    ];

    public function panen(): BelongsTo {
        return $this->belongsTo(Panen::class, 'panen_id');
    }
    
}
