<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->cascadeOnDelete();
            $table->string('nama_pelanggan');
            $table->string('no_hp');
            $table->string('email')->nullable();
            $table->date('tanggal_booking');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->decimal('total_harga', 12, 2);
            $table->decimal('dp_dibayar', 12, 2)->default(0);
            $table->decimal('sisa_bayar', 12, 2);
            $table->enum('status', ['menunggu_pembayaran', 'dp_terbayar', 'lunas', 'dibatalkan', 'selesai'])->default('menunggu_pembayaran');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
