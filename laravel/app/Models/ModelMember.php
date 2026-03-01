<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelMember extends Model
{
    protected $table = 'members';

    protected $fillable = [
        'kode_member',
        'nama_member',
        'no_hp',
        'poin'
    ];

    public function penjualan()
    {
        return $this->hasMany(ModelPenjualan::class);
    }
}
