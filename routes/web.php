<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\FasilitasController;


// --------------------------------------------------------------------------
// 1. Jalur Publik (Pelanggan / Guest Flow)
// --------------------------------------------------------------------------
Route::get('/', [BookingController::class, 'index'])->name('home');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/status', [BookingController::class, 'checkStatus'])->name('booking.status');
Route::post('/booking/{id}/pembayaran', [BookingController::class, 'uploadBukti'])->name('booking.pembayaran.upload');

// --------------------------------------------------------------------------
// 2. Jalur Otentikasi Admin (Tanpa Registrasi Publik)
// --------------------------------------------------------------------------
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --------------------------------------------------------------------------
// 3. Jalur Proteksi Admin Panel (Menggunakan Middleware CheckRole)
// --------------------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Utama Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Manajemen Operasional Reservasi
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/pembayaran/{pembayaranId}/konfirmasi', [AdminBookingController::class, 'konfirmasiPembayaran'])->name('pembayaran.konfirmasi');
    Route::post('/bookings/{id}/batalkan', [AdminBookingController::class, 'batalkanBooking'])->name('bookings.batalkan');



Route::resource('kategori', FasilitasController::class)->except(['show']);
Route::resource('fasilitas', FasilitasController::class)->except(['show']);
});