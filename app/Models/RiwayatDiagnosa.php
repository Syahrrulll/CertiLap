<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatDiagnosa extends Model
{
    protected $table = 'riwayat_diagnosa';
    protected $primaryKey = 'id_riwayat';

    protected $fillable = [
        'user_id',
        'id_kerusakan_utama',
        'nilai_cf_akhir',
        'persentase',
        'hasil_lain_json',
        'tanggal_diagnosa',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_diagnosa' => 'datetime',
            'nilai_cf_akhir' => 'float',
            'persentase' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kerusakanUtama()
    {
        return $this->belongsTo(Kerusakan::class, 'id_kerusakan_utama', 'id_kerusakan');
    }

    public function details()
    {
        return $this->hasMany(DetailRiwayat::class, 'id_riwayat', 'id_riwayat');
    }
}

