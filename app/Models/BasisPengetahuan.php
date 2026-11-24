<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasisPengetahuan extends Model
{
    protected $table = 'basis_pengetahuan';
    protected $primaryKey = 'id_rule';

    protected $fillable = [
        'id_kerusakan',
        'id_gejala',
        'mb',
        'md',
        'cf_pakar',
    ];

    public function kerusakan()
    {
        return $this->belongsTo(Kerusakan::class, 'id_kerusakan', 'id_kerusakan');
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'id_gejala', 'id_gejala');
    }
}

