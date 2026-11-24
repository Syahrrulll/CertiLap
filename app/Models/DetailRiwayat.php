<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRiwayat extends Model
{
    protected $table = 'detail_riwayat';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_riwayat',
        'id_gejala',
        'cf_user',
        'label_keyakinan',
    ];

    protected function casts(): array
    {
        return [
            'cf_user' => 'float',
        ];
    }

    public function riwayat()
    {
        return $this->belongsTo(RiwayatDiagnosa::class, 'id_riwayat', 'id_riwayat');
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'id_gejala', 'id_gejala');
    }
}

