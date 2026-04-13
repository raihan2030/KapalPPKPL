<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUltrasonicTestRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            't_origin' => ['required', 'numeric', 'min:0'],
            'metode_t_min' => ['required', 'in:rule_90,bki'],
            'nilai_ketebalan' => ['required', 'numeric', 'min:0'],
            'batas_standar' => ['required', 'numeric', 'min:0'],
            'frekuensi_ut' => ['required', 'numeric', 'min:0'],
            'level_pengujian' => ['required', 'in:A,B,C,D'],
            'kelas_area' => ['required', 'in:A,B'],
            'jenis_cacat' => ['required', 'string', 'max:255'],
            'kedalaman_cacat' => ['required', 'numeric', 'min:0'],
            'panjang_cacat' => ['required', 'numeric', 'min:0'],
            'amplitudo_gema' => ['required', 'numeric', 'min:0'],
            'dac_referensi' => ['required', 'numeric', 'gt:0'],
            'grafik_ultrasonik' => ['nullable', 'string'],
        ];
    }
}
