<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panen extends Model
{
    protected $table = 'panen';
    protected $fillable = ['lahan_id', 'tanaman_id', 'jumlah_panen', 'tanggal_panen', 'user_id', 'kualitas'];

    public function lahan(): BelongsTo {
        return $this->belongsTo(Lahan::class, 'lahan_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tanaman(): BelongsTo {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }

    public function penjualan(): HasMany {
        return $this->hasMany(Penjualan::class, 'panen_id');
    }
}
