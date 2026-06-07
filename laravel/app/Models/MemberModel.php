<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberModel extends Model
{
    use HasFactory;
    protected $table = 'members';
    protected $fillable = ['kode_member', 'nama', 'telepon', 'poin'];
    
    public function penjualans()
    {
        return $this->hasMany(PenjualanModel::class, 'member_id');
    }
}

