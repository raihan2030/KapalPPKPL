=============================================================================

DESAIN DATABASE - SISTEM INFORMASI INSPEKSI KAPAL BERBASIS NDT

=============================================================================

\*\*Deskripsi:\*\* Database ini mencakup 4 metode pengujian NDT:

\- Penetrant Test (cacat permukaan)

\- Vacuum Test (kebocoran)

\- Ultrasonic Testing (ketebalan & cacat internal)

\- Metalografi (struktur mikro logam)

=============================================================================

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| TABEL 1: kapal \|

\| (Menyimpan data master kapal yang akan diinspeksi) \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| Kolom \| Tipe Data \| PK/FK \| Keterangan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_kapal \| INT \| PK \| Primary key, auto increment \|

\| nama_kapal \| VARCHAR(100) \| - \| Nama kapal \|

\| jenis_kapal \| VARCHAR(50) \| - \| Tanker/Kontainer/Penumpang \|

\| tahun_pembuatan \| INT \| - \| Tahun pembuatan \|

\| bobot_kapal \| DECIMAL(12,2) \| - \| Bobot kapal (ton) \|

\| status_operasional\| ENUM \| - \| Aktif/Perbaikan/Nonaktif \|

\| created_at \| DATETIME \| - \| Waktu input data \|

\| updated_at \| DATETIME \| - \| Waktu update terakhir \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| TABEL 2: area_inspeksi \|

\| (Menyimpan area/lokasi inspeksi pada setiap kapal) \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| Kolom \| Tipe Data \| PK/FK \| Keterangan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_area \| INT \| PK \| Primary key \|

\| id_kapal \| INT \| FK \| Foreign key ke kapal \|

\| nama_area \| VARCHAR(100) \| - \| Nama area (lambung, geladak)\|

\| kode_area \| VARCHAR(20) \| - \| Kode unik area \|

\| titik_koordinat \| VARCHAR(50) \| - \| Koordinat inspeksi \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| TABEL 3: metode_ndt \|

\| (Master data metode pengujian NDT) \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| Kolom \| Tipe Data \| PK/FK \| Keterangan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_metode \| INT \| PK \| Primary key \|

\| kode_metode \| VARCHAR(20) \| - \| PT/VT/UT/MG \|

\| nama_metode \| VARCHAR(50) \| - \| Penetrant/Vacuum/UT/Metalografi\|

\| deskripsi \| TEXT \| - \| Penjelasan metode \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| TABEL 4: inspeksi_header \|

\| (Header/transaksi utama inspeksi - berlaku untuk semua metode) \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| Kolom \| Tipe Data \| PK/FK \| Keterangan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_inspeksi \| INT \| PK \| Primary key \|

\| id_kapal \| INT \| FK \| Foreign key ke kapal \|

\| id_area \| INT \| FK \| Foreign key ke area \|

\| id_metode \| INT \| FK \| Foreign key ke metode_ndt \|

\| tanggal_inspeksi \| DATE \| - \| Tanggal pelaksanaan \|

\| inspektur_nama \| VARCHAR(100) \| - \| Nama inspektur \|

\| status_inspeksi \| ENUM \| - \| Draft/Proses/Selesai \|

\| created_at \| DATETIME \| - \| Waktu input data \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| TABEL 5: detail_penetrant_test \|

\| (Hasil pengujian Penetrant - cacat permukaan) \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| Kolom \| Tipe Data \| PK/FK \| Keterangan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_detail_pt \| INT \| PK \| Primary key \|

\| id_inspeksi \| INT \| FK \| Foreign key ke inspeksi_header\|

\| jenis_penetrant \| VARCHAR(50) \| - \| Jenis cairan penetrant \|

\| waktu_penetrasi \| INT \| - \| Waktu penetrasi (menit) \|

\| indikasi_cacat \| TEXT \| - \| Deskripsi indikasi cacat \|

\| lokasi_cacat \| VARCHAR(100) \| - \| Posisi cacat ditemukan \|

\| ukuran_cacat \| DECIMAL(10,2) \| - \| Ukuran cacat (mm) \|

\| status_hasil \| ENUM \| - \| Pass/Fail \|

\| foto_dokumentasi \| VARCHAR(255) \| - \| Path foto hasil pengujian \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| TABEL 6: detail_vacuum_test \|

\| (Hasil pengujian Vacuum - kebocoran sambungan las) \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| Kolom \| Tipe Data \| PK/FK \| Keterangan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_detail_vt \| INT \| PK \| Primary key \|

\| id_inspeksi \| INT \| FK \| Foreign key ke inspeksi_header\|

\| tekanan_vacuum \| DECIMAL(10,2) \| - \| Nilai tekanan (bar) \|

\| durasi_pengujian \| INT \| - \| Durasi pengujian (detik) \|

\| indikasi_bocor \| BOOLEAN \| - \| True = ada bocor \|

\| lokasi_bocor \| VARCHAR(100) \| - \| Posisi kebocoran \|

\| status_hasil \| ENUM \| - \| Pass/Fail \|

\| catatan_khusus \| TEXT \| - \| Catatan tambahan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| TABEL 7: detail_ultrasonic_testing \|

\| (Hasil pengujian UT - ketebalan & cacat internal) \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| Kolom \| Tipe Data \| PK/FK \| Keterangan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_detail_ut \| INT \| PK \| Primary key \|

\| id_inspeksi \| INT \| FK \| Foreign key ke inspeksi_header\|

\| frekuensi_ut \| DECIMAL(10,2) \| - \| Frekuensi yang digunakan(MHz)\|

\| nilai_ketebalan \| DECIMAL(10,2) \| - \| Hasil ukur ketebalan (mm) \|

\| batas_standar \| DECIMAL(10,2) \| - \| Standar ketebalan minimum \|

\| laju_korosi \| DECIMAL(10,4) \| - \| Laju korosi (mm/tahun) \|

\| jenis_cacat \| VARCHAR(50) \| - \| Porositas/Retak/Inklusi \|

\| kedalaman_cacat \| DECIMAL(10,2) \| - \| Kedalaman cacat (mm) \|

\| status_hasil \| ENUM \| - \| Pass/Fail/Reject \|

\| grafik_ultrasonik \| VARCHAR(255) \| - \| Path file grafik A-scan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| TABEL 8: detail_metalografi \|

\| (Hasil pengujian Metalografi - struktur mikro logam) \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| Kolom \| Tipe Data \| PK/FK \| Keterangan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_detail_mg \| INT \| PK \| Primary key \|

\| id_inspeksi \| INT \| FK \| Foreign key ke inspeksi_header\|

\| gambar_mikroskop \| VARCHAR(255) \| - \| Path file gambar mikroskop
\|

\| resolusi_gambar \| VARCHAR(20) \| - \| Resolusi gambar (1920x1080) \|

\| struktur_mikro \| VARCHAR(100) \| - \| Ferit/Pearlit/Martensit \|

\| butiran_rating \| INT \| - \| Rating ukuran butiran (1-10)\|

\| inklusi_nonlogam \| VARCHAR(100) \| - \| Jenis inklusi jika ada \|

\| status_hasil \| ENUM \| - \| Pass/Fail/Reject \|

\| kesimpulan \| TEXT \| - \| Analisis ahli metalurgi \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| TABEL 9: validasi_inspeksi \|

\| (Menyimpan status validasi dari inspektur) \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| Kolom \| Tipe Data \| PK/FK \| Keterangan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_validasi \| INT \| PK \| Primary key \|

\| id_inspeksi \| INT \| FK \| Foreign key ke inspeksi_header\|

\| status_validasi \| BOOLEAN \| - \| True = sudah divalidasi \|

\| tgl_validasi \| DATETIME \| - \| Waktu validasi \|

\| catatan_validasi \| TEXT \| - \| Catatan inspektur \|

\| validasi_by \| VARCHAR(100) \| - \| Nama yang memvalidasi \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| TABEL 10: laporan_inspeksi \|

\| (Menyimpan metadata laporan yang dihasilkan) \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| Kolom \| Tipe Data \| PK/FK \| Keterangan \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_laporan \| INT \| PK \| Primary key \|

\| id_inspeksi \| INT \| FK \| Foreign key ke inspeksi_header\|

\| file_laporan \| VARCHAR(255) \| - \| Path file laporan PDF \|

\| tgl_generate \| DATETIME \| - \| Waktu generate laporan \|

\| versi_laporan \| INT \| - \| Versi laporan (1,2,3\...) \|

\| status_terkirim \| BOOLEAN \| - \| Apakah sudah dikirim ke atasan\|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

+===========================================================================+

\| DIAGRAM RELASI TABEL (Plain UML / ASCII) \|

+===========================================================================+

+\-\-\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+
+\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| kapal \| \| area_inspeksi \| \| metode_ndt \|

+\-\-\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+
+\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_kapal \|\<\-\-\-\-\-\-\-- \| id_kapal (FK) \| \| id_metode \|

\| nama_kapal \| 1 \* \| id_area (PK) \| \| nama_metode \|

\| \... \| \| nama_area \| \| \... \|

+\-\-\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+
+\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| \| \|

\| 1 \| 1 \| 1

\| \| \|

v v v

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| inspeksi_header \|

\| id_inspeksi (PK) \|

\| id_kapal (FK)
\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_area (FK)
\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| id_metode (FK)
\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\| tanggal_inspeksi \|

\| inspektur_nama \|

\| status_inspeksi \|

+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--+

\|

\| 1

\|

v

+\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\--+\-\-\-\-\-\-\-\-\-\-\--+

\| \| \| \| \| \|

v v v v v v

+\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+
+\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+

\| detail\_ \| \| detail\_ \| \| detail\_ \| \| detail\_ \| \|
validasi\_\| \| laporan\_ \|

\| penetrant\| \| vacuum \| \| ultra- \| \| metallo- \| \| inspeksi \|
\| inspeksi \|

\| \_test \| \| \_test \| \| sonic \| \| grafi \| \| \| \| \|

+\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+
+\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+

\| id_detail\| \| id_detail\| \| id_detail\| \| id_detail\| \| id_val \|
\| id_lapor \|

\| \_pt (PK) \| \| \_vt (PK) \| \| \_ut (PK) \| \| \_mg (PK) \| \|
id_inspek\| \| id_inspek\|

\| id_inspek\| \| id_inspek\| \| id_inspek\| \| id_inspek\| \| status \|
\| file \|

\| indikasi \| \| tekanan \| \| ketebalan\| \| gambar \| \| tgl_val \|
\| tgl_gen \|

\| status \| \| durasi \| \| cacat \| \| struktur \| \| catatan \| \|
versi \|

+\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+
+\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+ +\-\-\-\-\-\-\-\-\--+

+===========================================================================+

\| KETERANGAN RELASI: \|

\| - (1) = Satu / One \|

\| - (\*) = Banyak / Many \|

\| - (FK) = Foreign Key (kunci tamu) \|

\| - (PK) = Primary Key (kunci utama) \|

+===========================================================================+

+===========================================================================+

\| ATURAN BISNIS / VALIDASI (Business Rules) \|

+===========================================================================+

\| No \| Aturan \|

\|\-\-\--+\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--\|

\| 1 \| Setiap inspeksi WAJIB memiliki id_kapal, id_area, dan id_metode
\|

\| 2 \| Status hasil untuk Penetrant & Vacuum: Pass / Fail \|

\| 3 \| Status hasil untuk UT & Metalografi: Pass / Fail / Reject \|

\| 4 \| Validasi WAJIB dilakukan SEBELUM laporan dapat digenerate \|

\| 5 \| Data yang sudah divalidasi TIDAK DAPAT diubah \|

\| 6 \| Nilai ketebalan ULTRASONIC jika \< batas_standar → status = Fail
\|

\| 7 \| File gambar Metalografi WAJIB format JPG/PNG dan resolusi min
720p \|

\| 8 \| Parameter Vacuum Test (tekanan & durasi) WAJIB angka positif \|

\| 9 \| Satu inspeksi_header hanya memiliki SATU metode NDT \|

\| 10 \| Satu inspeksi_header dapat memiliki BANYAK detail (jika perlu
update) \|

+===========================================================================+

\| CONTOH DATA (Preview untuk semua metode) \|

+===========================================================================+

\-\-- Tabel inspeksi_header \-\--

\| id_inspeksi \| id_kapal \| id_area \| id_metode \| tanggal \|
status_hasil \|

\|\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\-\--\|

\| 1 \| 1 \| 1 \| 1 \| 2026-01-10 \| Pass \|

\| 2 \| 1 \| 2 \| 2 \| 2026-01-11 \| Fail \|

\| 3 \| 2 \| 3 \| 3 \| 2026-01-12 \| Reject \|

\| 4 \| 2 \| 4 \| 4 \| 2026-01-13 \| Pass \|

\-\-- Tabel detail_penetrant_test (id_inspeksi=1) \-\--

\| indikasi_cacat \| ukuran_cacat \| status_hasil \|

\|\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\-\--\|

\| Retak rambut di las lambung \| 2.5 mm \| Pass \|

\-\-- Tabel detail_vacuum_test (id_inspeksi=2) \-\--

\| tekanan_vacuum \| durasi \| indikasi_bocor \| lokasi_bocor \|
status_hasil \|

\|\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\-\--\|

\| 0.5 bar \| 30 det \| True \| Sambungan las no 3\| Fail \|

\-\-- Tabel detail_ultrasonic_testing (id_inspeksi=3) \-\--

\| nilai_ketebalan \| batas_standar \| jenis_cacat \| status_hasil \|

\|\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\-\--\|

\| 7.5 mm \| 10.0 mm \| Porositas \| Reject \|

\-\-- Tabel detail_metalografi (id_inspeksi=4) \-\--

\| struktur_mikro \| butiran_rating \| status_hasil \| kesimpulan \|

\|\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\-\--\|\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--\|

\| Ferit-Pearlit \| 5 \| Pass \| Struktur normal \|

+===========================================================================+
