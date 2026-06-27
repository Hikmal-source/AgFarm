<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lahan extends Model
{
    protected $table = 'lahan';
    protected $fillable = ['user_id', 'nama_lahan', 'lokasi_blok', 'tanaman_id'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tanaman(): BelongsTo {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }

    public function panen(): HasMany {
        return $this->hasMany(Panen::class, 'lahan_id');
    }
}
