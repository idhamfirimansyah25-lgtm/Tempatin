<?php

// app/Http/Controllers/Admin/AdminBookingController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Service\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBookingController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function index(Request $request)
    {
        $query = Booking::with(['fasilitas', 'pembayarans'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('kode_booking', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $request->search . '%');
        }

        $bookings = $query->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['fasilitas', 'pembayarans.verifikator'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function konfirmasiPembayaran($pembayaranId)
    {
        $adminId = Auth::id();
        $pembayaran = Pembayaran::findOrFail($pembayaranId);

        $proses = $this->bookingService->verifikasiPembayaran($pembayaranId, $adminId);

        if ($proses) {
            return back()->with('success', 'Pembayaran berhasil diverifikasi, status booking otomatis diperbarui.');
        }

        return back()->withErrors(['error' => 'Pembayaran gagal diverifikasi atau status sudah berubah.']);
    }

    public function batalkanBooking($id)
    {
        $booking = Booking::findOrFail($id);
        
        if (in_array($booking->status, ['lunas', 'selesai', 'dibatalkan'])) {
            return back()->withErrors(['error' => 'Booking dengan status ini tidak dapat dibatalkan.']);
        }

        $booking->update(['status' => 'dibatalkan']);
        return back()->with('success', 'Booking berhasil dibatalkan oleh admin.');
    }
}
