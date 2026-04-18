@extends('layouts.app')

@section('title', 'Edit Data Ultrasonic Test')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h1 class="h5 mb-0">✏️ Edit Data Ultrasonic Test</h1>
                </div>
                <div class="card-body">
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-md-6">
                            <p class="mb-1"><small class="text-muted">ID Inspeksi</small></p>
                            <p class="mb-0"><strong>{{ $inspeksi->id_inspeksi }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><small class="text-muted">Jenis Kapal</small></p>
                            <p class="mb-0"><strong>{{ $inspeksi->jenis_kapal }}</strong></p>
                        </div>
                        <div class="col-md-6 mt-2">
                            <p class="mb-1"><small class="text-muted">Area Kapal</small></p>
                            <p class="mb-0"><strong>{{ $inspeksi->area_kapal }}</strong></p>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="alert alert-info mb-3">
                        <small>
                            <i class="fas fa-info-circle"></i>
                            Ubah data pengukuran yang ingin diperbarui, kemudian klik <strong>Perbarui Data</strong>.
                        </small>
                    </div>

                    <form method="POST" action="{{ route('ultrasonic.update', $inspeksi->id_inspeksi) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="t_origin" class="form-label">Ketebalan Desain Awal (t_origin, mm)</label>
                            <input type="number" step="0.01" min="0"
                                class="form-control @error('t_origin') is-invalid @enderror" id="t_origin" name="t_origin"
                                value="{{ old('t_origin', $inspeksi->t_origin) }}" required>
                            @error('t_origin')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="metode_t_min" class="form-label">Metode Perhitungan t_min</label>
                            <select class="form-select @error('metode_t_min') is-invalid @enderror" id="metode_t_min"
                                name="metode_t_min" required>
                                <option value="rule_90" @selected(old('metode_t_min', $inspeksi->metode_perhitungan) === 'rule_90')>Rule 90% (0.9 x t_origin)</option>
                                <option value="bki" @selected(old('metode_t_min', $inspeksi->metode_perhitungan) === 'bki')>BKI (t_origin - 0.5 x sqrt(t_origin))
                                </option>
                            </select>
                            @error('metode_t_min')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nilai_ketebalan" class="form-label">Nilai Ketebalan</label>
                            <input type="number" step="0.01" min="0"
                                class="form-control @error('nilai_ketebalan') is-invalid @enderror" id="nilai_ketebalan"
                                name="nilai_ketebalan" value="{{ old('nilai_ketebalan', $inspeksi->nilai_ketebalan) }}"
                                required>
                            @error('nilai_ketebalan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="batas_standar" class="form-label">Batas Standar</label>
                            <input type="number" step="0.01" min="0"
                                class="form-control @error('batas_standar') is-invalid @enderror" id="batas_standar"
                                name="batas_standar" value="{{ old('batas_standar', $inspeksi->batas_standar) }}" required>
                            @error('batas_standar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="frekuensi_ut" class="form-label">Frekuensi UT</label>
                            <input type="number" step="0.01" min="0"
                                class="form-control @error('frekuensi_ut') is-invalid @enderror" id="frekuensi_ut"
                                name="frekuensi_ut" value="{{ old('frekuensi_ut', $inspeksi->frekuensi_ut) }}" required>
                            @error('frekuensi_ut')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="level_pengujian" class="form-label">Level Pengujian ISO 17640</label>
                                <select class="form-select @error('level_pengujian') is-invalid @enderror"
                                    id="level_pengujian" name="level_pengujian" required>
                                    <option value="A" @selected(old('level_pengujian', $inspeksi->level_pengujian) === 'A')>A</option>
                                    <option value="B" @selected(old('level_pengujian', $inspeksi->level_pengujian) === 'B')>B</option>
                                    <option value="C" @selected(old('level_pengujian', $inspeksi->level_pengujian) === 'C')>C</option>
                                    <option value="D" @selected(old('level_pengujian', $inspeksi->level_pengujian) === 'D')>D</option>
                                </select>
                                @error('level_pengujian')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="kelas_area" class="form-label">Kelas Area</label>
                                <select class="form-select @error('kelas_area') is-invalid @enderror" id="kelas_area"
                                    name="kelas_area" required>
                                    <option value="A" @selected(old('kelas_area', $inspeksi->kelas_area) === 'A')>A (Kritis/Midship)</option>
                                    <option value="B" @selected(old('kelas_area', $inspeksi->kelas_area) === 'B')>B (Non-Kritis)</option>
                                </select>
                                @error('kelas_area')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_cacat" class="form-label">Jenis Cacat</label>
                            <input type="text" class="form-control @error('jenis_cacat') is-invalid @enderror"
                                id="jenis_cacat" name="jenis_cacat"
                                value="{{ old('jenis_cacat', $inspeksi->jenis_cacat) }}" maxlength="255" required>
                            @error('jenis_cacat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kedalaman_cacat" class="form-label">Kedalaman Cacat</label>
                            <input type="number" step="0.01" min="0"
                                class="form-control @error('kedalaman_cacat') is-invalid @enderror" id="kedalaman_cacat"
                                name="kedalaman_cacat" value="{{ old('kedalaman_cacat', $inspeksi->kedalaman_cacat) }}"
                                required>
                            @error('kedalaman_cacat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="panjang_cacat" class="form-label">Panjang Cacat (mm)</label>
                            <input type="number" step="0.01" min="0"
                                class="form-control @error('panjang_cacat') is-invalid @enderror" id="panjang_cacat"
                                name="panjang_cacat" value="{{ old('panjang_cacat', $inspeksi->panjang_cacat) }}"
                                required>
                            @error('panjang_cacat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="amplitudo_gema" class="form-label">Echo Amplitude</label>
                                <input type="number" step="0.01" min="0"
                                    class="form-control @error('amplitudo_gema') is-invalid @enderror"
                                    id="amplitudo_gema" name="amplitudo_gema"
                                    value="{{ old('amplitudo_gema', $inspeksi->echo_amplitude) }}" required>
                                @error('amplitudo_gema')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dac_referensi" class="form-label">DAC Referensi</label>
                                <input type="number" step="0.01" min="0.01"
                                    class="form-control @error('dac_referensi') is-invalid @enderror" id="dac_referensi"
                                    name="dac_referensi" value="{{ old('dac_referensi', $inspeksi->echo_amplitude) }}"
                                    required>
                                @error('dac_referensi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                💾 Perbarui Data
                            </button>
                            <a href="{{ route('ultrasonic.analysis.result', $inspeksi->id_inspeksi) }}"
                                class="btn btn-secondary">
                                ← Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
