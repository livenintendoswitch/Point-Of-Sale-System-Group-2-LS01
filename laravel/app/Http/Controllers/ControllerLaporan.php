<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\MemberModel;
use App\Models\DetailPenjualanModel;
use Illuminate\Support\Facades\DB;

class ControllerLaporan extends Controller
{
    public function index()
    {
        return redirect()->route('laporan.bulanan');
    }
    
    public function bulanan(Request $request)
    {
        // 1. Ambil input bulan dan tahun
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // 2. Ringkasan Total Bulanan (Hapus perkalian konversi_saat_itu)
        $ringkasan = DetailPenjualanModel::whereHas('penjualan', function ($query) use ($bulan, $tahun) {
                $query->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
            })
            ->select(
                DB::raw('SUM(subtotal) as total_omset'),
                DB::raw('SUM(harga_beli * jumlah) as total_modal'), // 🟢 DIPERBAIKI
                DB::raw('SUM(subtotal - (harga_beli * jumlah)) as total_laba') // 🟢 DIPERBAIKI
            )->first();

        // 3. Laporan Khusus Per Member
        $laporanMember = MemberModel::whereHas('penjualans', function ($query) use ($bulan, $tahun) {
                $query->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
            })
            ->with(['penjualans' => function ($query) use ($bulan, $tahun) {
                $query->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)
                      ->with('details.produk', 'details.kemasan');
            }])
            ->get()
            ->map(function ($member) {
                $totalBelanjaMember = 0;
                $totalLabaDariMember = 0;
                $detailBarang = [];

                foreach ($member->penjualans as $penjualan) {
                    $totalBelanjaMember += $penjualan->total_harga;

                    foreach ($penjualan->details as $detail) {
                        // 🟢 DIPERBAIKI: Hitung modal murni tanpa pengali konversi fisik stok
                        $modalItem = $detail->harga_beli * $detail->jumlah;
                        $labaItem = $detail->subtotal - $modalItem;
                        $totalLabaDariMember += $labaItem;

                        $detailBarang[] = [
                            'nama_produk' => $detail->produk->nama . ' (' . ($detail->kemasan->satuan ?? '-') . ')',
                            'jumlah' => $detail->jumlah,
                            'subtotal' => $detail->subtotal,
                            'laba' => $labaItem
                        ];
                    }
                }

                return [
                    'nama_member' => $member->nama,
                    'kode_member' => $member->kode_member,
                    'total_belanja' => $totalBelanjaMember,
                    'total_laba' => $totalLabaDariMember,
                    'barang_dibeli' => $detailBarang
                ];
            });

        return view('admin.laporan_bulanan', compact('ringkasan', 'laporanMember', 'bulan', 'tahun'));
    }
}