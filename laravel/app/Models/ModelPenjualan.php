<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ModelPenjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'user_id',
        'member_id',
        'total',
        'diskon',
        'bayar',
        'kembalian',
        'metode_bayar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(ModelMember::class);
    }

    public function detail()
    {
        return $this->hasMany(ModelDetailPenjualan::class);
    }
}
