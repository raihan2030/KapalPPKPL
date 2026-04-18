@extends('layouts.app')

@section('title', 'Validasi Hasil Analisis')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h5 mb-0">✅ Validasi Hasil Analisis</h1>
                </div>
                <div class="card-body">
                    <!-- Informasi Inspeksi -->
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
                        <div class="col-md-6 mt-2">
                            <p class="mb-1"><small class="text-muted">Status Ketebalan</small></p>
                            <p class="mb-0">
                                <span
                                    class="badge {{ $inspeksi->status_ketebalan == 'OK' ? 'bg-success' : ($inspeksi->status_ketebalan == 'REPAIR' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $inspeksi->status_ketebalan }}
                                </span>
                            </p>
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

                    <!-- Ringkasan Hasil -->
                    <div class="card mb-3 border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title fw-bold mb-3">📊 Ringkasan Hasil Analisis</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="50%"><small class="text-muted">Ketebalan Desain Awal</small></td>
                                    <td><strong>{{ $inspeksi->t_origin }} mm</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Nilai Ketebalan Hasil Ukur</small></td>
                                    <td><strong>{{ $inspeksi->nilai_ketebalan }} mm</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Batas Standar Minimum</small></td>
                                    <td><strong>{{ $inspeksi->batas_standar }} mm</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Penipisan / Pengurangan</small></td>
                                    <td><strong class="text-danger">{{ $inspeksi->persentase_penipisan }}%</strong></td>
                                </tr>
                                @if ($inspeksi->jenis_cacat && $inspeksi->jenis_cacat != '')
                                    <tr>
                                        <td><small class="text-muted">Jenis Cacat Terdeteksi</small></td>
                                        <td><strong>{{ $inspeksi->jenis_cacat }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><small class="text-muted">Status Akseptansi</small></td>
                                        <td>
                                            <span
                                                class="badge {{ $inspeksi->status_akseptansi == 'ACCEPTED' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $inspeksi->status_akseptansi }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Instruksi Validasi -->
                    <div class="alert alert-info mb-3">
                        <h6 class="alert-heading">ℹ️ Petunjuk Validasi</h6>
                        <small>
                            Sebagai Inspektur, Anda harus memastikan hasil analisis akurat sebelum dilaporkan.
                            <br><br>
                            <strong>Pilih salah satu:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Validasi:</strong> Jika hasil analisis sudah akurat dan siap dilaporkan</li>
                                <li><strong>Tolak:</strong> Jika ada ketidaksesuaian atau perlu pengukuran ulang</li>
                            </ul>
                        </small>
                    </div>

                    <!-- Form Validasi -->
                    <div class="row g-2 mb-3">
                        <!-- Kolom Validasi -->
                        <div class="col-md-6">
                            <form method="POST"
                                action="{{ route('ultrasonic.validation.validate', $inspeksi->id_inspeksi) }}"
                                class="h-100">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="catatan_validasi_accept" class="form-label">Catatan Validasi
                                        (Opsional)</label>
                                    <textarea class="form-control" id="catatan_validasi_accept" name="catatan_validasi" rows="4"
                                        placeholder="Masukkan catatan atau observasi penting..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100 py-2">
                                    <i class="fas fa-check-circle"></i> Validasi Hasil
                                </button>
                            </form>
                        </div>

                        <!-- Kolom Penolakan -->
                        <div class="col-md-6">
                            <form method="POST"
                                action="{{ route('ultrasonic.validation.reject', $inspeksi->id_inspeksi) }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="catatan_validasi_reject" class="form-label">Alasan Penolakan <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('catatan_validasi') is-invalid @enderror" id="catatan_validasi_reject"
                                        name="catatan_validasi" rows="4" placeholder="Jelaskan alasan penolakan dan tindakan yang diperlukan..."
                                        required></textarea>
                                    @error('catatan_validasi')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-danger w-100 py-2"
                                    onclick="return confirm('Anda yakin ingin menolak hasil analisis ini?')">
                                    <i class="fas fa-times-circle"></i> Tolak & Kembalikan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Tombol Kembali -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('ultrasonic.analysis.result', $inspeksi->id_inspeksi) }}"
                            class="btn btn-secondary">
                            ← Kembali ke Hasil Analisis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
