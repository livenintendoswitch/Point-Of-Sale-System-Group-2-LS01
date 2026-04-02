<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\ProdukModel;

class ControllerLaporan extends Controller
{
    
    public function index()
    {
        $penjualans = PenjualanModel::with('user', 'member')->latest()->paginate(10);
        return view('admin.laporan.index', compact('penjualans'));
    }

    public function stok()
    {
        $produks = ProdukModel::orderBy('stok', 'asc')->get();
        return view('admin.laporan.stok', compact('produks'));
    }
}
