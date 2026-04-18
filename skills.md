---
name: laravel-ndt-inspection
description: "Skill untuk generate laporan inspeksi kapal NDT menggunakan Laravel dengan validasi status dan PDF output"
---

# Skill: Generate Laporan Ultrasonic Testing (UT)

## Context Files
- `app/Models/InspeksiUltrasonic.php` - Model utama data inspeksi
- `app/Models/Report.php` - Model untuk menyimpan data laporan PDF
- `app/Http/Controllers/ReportController.php` - Controller untuk handle generate, download, preview laporan
- `app/Services/ReportGeneratorService.php` - Service untuk logic generate PDF
- `resources/views/reports/report_template.blade.php` - Template Blade untuk PDF
- `database/migrations/2026_04_19_000010_create_reports_table.php` - Migration tabel reports
- `routes/web.php` - Routes untuk report API

## Rules & Business Logic

### 1. Validasi Status Sebelum Generate
- Laporan **hanya bisa di-generate** jika status validasi = `"validated"`
- Jika status masih `"pending"` atau `"rejected"`, return error **403 Forbidden**
- Error message: `"Hasil belum divalidasi. Status validasi harus 'validated'"`

### 2. Cek Laporan Duplikat
- Sebelum generate, cek apakah laporan untuk inspeksi tersebut sudah ada
- Jika sudah ada, return response dengan informasi laporan yang sudah ada (tidak generate ulang)
- Ini mencegah duplikasi file dan redundansi data

### 3. Output Format PDF
- Menggunakan library `barryvdh/laravel-dompdf`
- Template: `resources/views/reports/report_template.blade.php`
- Output file: `Laporan_UT_{id_inspeksi}_{timestamp}.pdf`
- Disimpan di: `storage/app/public/reports/`

### 4. Data Yang Sudah Divalidasi
- Data yang sudah divalidasi bersifat **read-only** di database
  - Column `is_locked = true` mencegah perubahan data
  - Column `is_locked = false` memperbolehkan editing
- Laporan hanya menampilkan data yang sudah tervalidasi (immutable)

### 5. Tracking Download
- Sistem mencatat:
  - `tanggal_download` - kapan file pertama kali di-download
  - `jumlah_download` - berapa kali file sudah di-download
  - `status_laporan` - berubah dari `"generated"` menjadi `"downloaded"` setelah pertama kali di-download

### 6. Error Handling
- Validasi ada tidaknya data inspeksi sebelum generate
- Validasi keberadaan file PDF sebelum download/preview
- Try-catch untuk menangani exception dan mengembalikan error message yang informatif

## Tech Stack
- **Framework**: Laravel 11
- **PHP**: 8.x
- **Database**: MySQL
- **PDF Library**: `barryvdh/laravel-dompdf`
- **Templating**: Blade

## Installation Steps

### 1. Install DomPDF Package
```bash
composer require barryvdh/laravel-dompdf
```

### 2. Publish Config (optional)
```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### 3. Run Migrations
```bash
php artisan migrate
```

## API Endpoints

### Generate Laporan
- **POST** `/reports/{idInspeksi}/generate`
- **Body**: None (ambil user dari auth())
- **Response**: JSON dengan report data
- **Status Code**: 201 (created) atau 403 (not validated)

### Download Laporan
- **GET** `/reports/{idLaporan}/download`
- **Response**: PDF file (binary)
- **Status Code**: 200 atau 404

### Preview Laporan (di browser)
- **GET** `/reports/{idLaporan}/preview`
- **Response**: PDF file (inline)
- **Status Code**: 200 atau 404

### List Laporan by Inspeksi
- **GET** `/reports/inspeksi/{idInspeksi}`
- **Response**: JSON array laporan
- **Status Code**: 200

## Key Features

✅ Validasi status terlebih dahulu sebelum generate  
✅ Cek duplikasi laporan  
✅ Generate PDF dengan template Blade  
✅ Tracking download statistics  
✅ Error handling yang comprehensive  
✅ API response yang informatif dalam format JSON  
✅ Data immutable setelah divalidasi

## Database Schema (reports table)

| Column | Type | Description |
|--------|------|-------------|
| id_laporan | BIGINT | Primary key auto-increment |
| id_inspeksi | VARCHAR(20) | FK ke inspeksi_ultrasonic |
| nama_laporan | VARCHAR(100) | Nama file PDF |
| file_path | VARCHAR(255) | Path file di storage |
| status_laporan | ENUM | generated, downloaded, archived |
| tanggal_generate | DATETIME | Waktu laporan di-generate |
| generated_by | BIGINT | User ID yang generate |
| tanggal_download | DATETIME | Waktu pertama kali di-download |
| jumlah_download | INTEGER | Counter download |
| catatan_laporan | TEXT | Catatan tambahan |
| created_at | TIMESTAMP | Created timestamp |
| updated_at | TIMESTAMP | Updated timestamp |

# Engineering Standards - Ship Inspection System (NDT)
## Skill: Laravel Backend Developer (PHP 8.2+)

### **Context**
Anda adalah pengembang backend Laravel senior yang ahli dalam sistem manajemen inspeksi kapal Non-Destructive Testing (NDT). Tugas Anda adalah membangun sistem yang mencakup pengujian **Metalografi**, **Ultrasonic**, **Vacuum**, dan **Penetrant** dengan standar keamanan dan integritas data maritim yang tinggi.

### **Tech Stack**
- **Framework**: Laravel 10.x / 11.x
- **Language**: PHP 8.2+
- **Database**: MariaDB / MySQL (Eloquent ORM)
- **Architecture**: Controller -> Service -> Repository Pattern (MVC)

### **Coding Guidelines**

#### **1. Database & Model Standards**
- **Naming Convention**: Gunakan `snake_case` untuk nama kolom di database.
- **Data Integrity**: 
    - Gunakan tipe data `$table->decimal('column', 12, 2)` untuk angka presisi seperti `bobot_kapal` dan `nilai_ketebalan`.
    - Wajib menggunakan Primary Key `id_[nama_tabel]` (Auto Increment).
    - Gunakan `foreignId()` untuk relasi antar tabel (contoh: `id_kapal` pada tabel `area_inspeksi`).
- **Audit**: Setiap tabel wajib memiliki `$table->timestamps()`.

#### **2. Service Layer & Business Rules (Critical)**
AI harus mengikuti logika bisnis otomatis berikut sesuai dokumen teknis:
- **Validasi Metalografi**: Fungsi `uploadGambar()` wajib memvalidasi resolusi gambar minimal **720p** dengan format **JPG/PNG**.
- **Logika Ultrasonic**: Jika `nilai_ketebalan` < `batas_standar`, maka field `status_hasil` pada model `Inspeksi` otomatis menjadi **'Fail'**.
- **Logika Vacuum**: Parameter `tekanan_vacuum` dan `durasi` wajib angka positif (`numeric|min:0`).
- **Proses Laporan**: Fungsi `generateLaporan()` hanya boleh dijalankan jika `status_validasi` bernilai `true`.
- **Immutability**: Data yang sudah memiliki `status_validasi = true` dilarang untuk di-update atau di-delete.

#### **3. API & Controller Standards**
- Gunakan **FormRequest** untuk validasi input (contoh: `StoreMetalografiRequest`).
- Gunakan **API Resources** untuk standarisasi output JSON.
- Kembalikan response yang konsisten: `{ "status": "success", "data": [...], "message": "..." }`.

#### **4. Error Handling**
- Implementasikan `try-catch` di Service Layer.
- Gunakan custom exception untuk pelanggaran aturan bisnis (misal: `ValidationException` jika resolusi gambar di bawah standar).

### **Prompt Examples**
> "Generate a Laravel Service for Metalografi that validates image resolution and saves microscopic structure data."
> "Create a Migration for the Ultrasonic table with decimal precision for thickness and a foreign key to the inspection_header table."