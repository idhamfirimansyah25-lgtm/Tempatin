<?php

namespace App\Http\Controllers;

use App\Models\transaksis;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    protected $transaksiService;

    public function __construct(TransaksiService $transaksiService)
    {
        $this->transaksiService = $transaksiService;
    }

    public function store(StoreTransaksiRequest $request)
    {
        try {
            $transaksi = $this->transaksiService->buatTransaksi(
                $request->validated('cart'),
                auth()->user(),
                $request->only(['diskon_total', 'bayar', 'metode_bayar'])
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil',
                'data' => $transaksi->load('detailTransaksi.produk')
            ]);
        } catch (StokTidakCukupException | PembayaranKurangException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }
}
