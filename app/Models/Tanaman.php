<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tanaman extends Model
{
    protected $table = 'tanaman';
    protected $fillable = ['nama_tanaman', 'kategori', ''];

    public function panen(): hasMany {
        return $this->hasMany(Panen::class, 'tanaman_id');
    }

    public function lahan(): HasMany {
        return $this->hasMany(Lahan::class, 'tanaman_id');
    }
}
