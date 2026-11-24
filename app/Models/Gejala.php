<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    protected $table = 'gejala';
    protected $primaryKey = 'id_gejala';

    protected $fillable = [
        'kode_gejala',
        'nama_gejala',
    ];

    public function basisPengetahuan()
    {
        return $this->hasMany(BasisPengetahuan::class, 'id_gejala', 'id_gejala');
    }

    public function kerusakan()
    {
        return $this->belongsToMany(Kerusakan::class, 'basis_pengetahuan', 'id_gejala', 'id_kerusakan')
                    ->withPivot('mb', 'md', 'cf_pakar');
    }
}

