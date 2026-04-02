<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{
    use HasFactory;
    protected $table = 'penjualans';
    protected $fillable = ['no_invoice', 'user_id', 'member_id', 'total_harga', 'dibayar', 'kembalian'];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function member()
    {
        return $this->belongsTo(MemberModel::class, 'member_id');
    }

    public function details()
    {
        return $this->hasMany(DetailPenjualanModel::class, 'penjualan_id');
    }
}
