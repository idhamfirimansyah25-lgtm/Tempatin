<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiRequest extends FormRequest
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
            'tipe' => 'required|in:dp,pelunasan',
            'jumlah' => 'required|numeric|min:0',
            'metode_bayar' => 'required|in:transfer,qris,cash',
            'bukti_bayar' => 'required_unless:metode_bayar,cash|image|max:2048',
        ];
    }
}
