<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UploadBuktiRequest;
use App\Models\Fasilitas;
use App\Models\Booking;
use Illuminate\Http\Request;
use Exception;
use App\Service\BookingService;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function index()
    {
        $fasilitas = Fasilitas::where('status', 'aktif')->with('kategoriFasilitas')->get();
        return view('booking.index', compact('fasilitas'));
    }

    public function store(StoreBookingRequest $request)
    {
        try {
            $booking = $this->bookingService->createBooking($request->validated());
            return redirect()->route('booking.status', ['kode' => $booking->kode_booking])
                ->with('success', 'Reservasi berhasil dibuat! Silakan lakukan pembayaran.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function checkStatus(Request $request)
    {
        $kode = $request->get('kode');
        $booking = null;

        if ($kode) {
            $booking = Booking::where('kode_booking', $kode)->with(['fasilitas', 'pembayarans'])->first();
            if (!$booking) {
                return back()->withErrors(['kode' => 'Kode booking tidak ditemukan.']);
            }
        }

        return view('booking.status', compact('booking'));
    }

    public function uploadBukti(UploadBuktiRequest $request, $id)
    {
        try {
            $this->bookingService->submitPembayaran($id, $request->validated());
            return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengunggah pembayaran: ' . $e->getMessage()]);
        }
    }
}
