<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFasilitasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kategori_fasilitas_id' => 'required|exists:kategori_fasilitas,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kapasitas' => 'nullable|integer|min:1',
            'harga_per_jam' => 'required|numeric|min:0',
            'durasi_minimum_menit' => 'required|integer|min:15',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'gambar' => 'nullable|image|max:2048',  
            'status' => 'required|in:aktif,nonaktif',
        ];
    }
}
