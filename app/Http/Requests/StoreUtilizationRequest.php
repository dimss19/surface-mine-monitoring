<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUtilizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'tipe' => 'required|in:breakdown,servis',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string|max:500',
        ];
    }
}
