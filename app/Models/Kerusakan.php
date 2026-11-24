<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kerusakan extends Model
{
    protected $table = 'kerusakan';
    protected $primaryKey = 'id_kerusakan';

    protected $fillable = [
        'kode_kerusakan',
        'nama_kerusakan',
        'deskripsi',
        'solusi',
    ];

    public function basisPengetahuan()
    {
        return $this->hasMany(BasisPengetahuan::class, 'id_kerusakan', 'id_kerusakan');
    }

    public function gejala()
    {
        return $this->belongsToMany(Gejala::class, 'basis_pengetahuan', 'id_kerusakan', 'id_gejala')
                    ->withPivot('mb', 'md', 'cf_pakar');
    }
}

