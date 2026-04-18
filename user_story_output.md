# User Story Output - Input Data Ultrasonic Test

## User Story

Sebagai inspector, saya ingin menginput data Ultrasonic Test untuk area inspeksi kapal agar data ketebalan dan cacat dapat direkam oleh sistem.

## Implementasi Selesai

- Migration tabel ultrasonic_test
- Model UltrasonicTest
- FormRequest StoreUltrasonicTestRequest
- Repository Pattern (interface + implementasi)
- Service Layer UltrasonicTestService
- Controller API UltrasonicTestController
- Controller Web UltrasonicTestWebController
- Blade layout Bootstrap 5 CDN
- Blade form input ultrasonic
- Routing API dan Web
- Binding interface repository di service provider

## Validasi Input

- nilai_ketebalan: required|numeric|min:0
- batas_standar: required|numeric|min:0
- frekuensi_ut: required|numeric|min:0
- jenis_cacat: required|string|max:255
- kedalaman_cacat: required|numeric|min:0
- grafik_ultrasonik: required|string

## Alur Bisnis

- Input valid disimpan ke database melalui Service dan Repository.
- Input tidak valid ditolak oleh FormRequest.
- Tidak ada kalkulasi status atau korosi tambahan.

## Endpoint

- Web form: GET /ultrasonic/{idInspeksi}/create
- Web submit: POST /ultrasonic/{idInspeksi}
- API submit JSON: POST /api/ultrasonic/{idInspeksi}

## Format Respons API

{
"status": "success",
"data": { ... },
"message": "Data ultrasonic test berhasil disimpan."
}

## Commit Message (suggested)

feat: implementasi user story 3.2 input data ultrasonic test

- Add ultrasonic_test migration
- Add UltrasonicTest model
- Add FormRequest validation for input data
- Implement Repository and Service layer
- Add controller for API and Web
- Add Blade form for input data (create view)
- Implement validation handling (success save & error response)
- Add routes for storing ultrasonic test data

---

# User Story Output - US 3.5: Generate Laporan Hasil Ultrasonic Testing

**Kelompok/Login**: [Masukkan nama anggota kelompok di sini]

## User Story

**As a** Inspektur  
**I want to** menghasilkan laporan hasil UT (Ultrasonic Testing)  
**So that** hasil pengujian terdokumentasi secara resmi

---

## Acceptance Criteria Status

| AC | Kriteria | Status | Implementation |
|:---:|----------|:------:|---|
| AC1 | Generate laporan setelah validasi | ✅ | ReportGeneratorService::generate() + ReportController |
| AC2 | Tampilkan isi laporan dalam PDF | ✅ | Blade template + download/preview endpoints |
| AC3 | Tolak jika belum divalidasi | ✅ | Status validasi check + Error 403 |

---

## Implementasi Completed

✅ Migration: `create_reports_table`  
✅ Model: `Report.php` dengan relationships dan scopes  
✅ Service: `ReportGeneratorService.php` untuk generate PDF  
✅ Controller: `ReportController.php` dengan 4 methods  
✅ View/Template: `report_template.blade.php` (Blade for PDF)  
✅ Routes: 4 endpoints di `routes/web.php`  
✅ Package: `barryvdh/laravel-dompdf` dependency  

---

## Database Schema: reports Table

```sql
CREATE TABLE reports (
    id_laporan BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_inspeksi VARCHAR(20) NOT NULL UNIQUE,
    nama_laporan VARCHAR(100) NOT NULL COMMENT 'Nama file laporan PDF',
    file_path VARCHAR(255) NOT NULL COMMENT 'Path file di storage/app/public/',
    status_laporan ENUM('generated', 'downloaded', 'archived') DEFAULT 'generated',
    tanggal_generate DATETIME NOT NULL COMMENT 'Waktu laporan di-generate',
    generated_by BIGINT UNSIGNED NOT NULL COMMENT 'User ID pengguna generator',
    tanggal_download DATETIME NULL COMMENT 'Waktu download pertama kali',
    jumlah_download INT DEFAULT 0 COMMENT 'Counter jumlah download',
    catatan_laporan TEXT NULL COMMENT 'Catatan tambahan',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (id_inspeksi) REFERENCES inspeksi_ultrasonic(id_inspeksi) ON DELETE CASCADE,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_id_inspeksi (id_inspeksi),
    INDEX idx_status (status_laporan)
);
```

---

## Validasi Business Rules

### ✅ Validasi 1: Status Harus "Tervalidasi"
```
IF status_validasi != "validated"
  THEN return 403 Forbidden
  MESSAGE: "Hasil belum divalidasi. Status validasi harus 'validated'"
```

### ✅ Validasi 2: Data Immutability
```
IF is_locked = true
  THEN data read-only di laporan
  Perubahan data setelah validasi tidak mempengaruhi laporan
```

### ✅ Validasi 3: Cek Duplikasi Laporan
```
IF laporan untuk id_inspeksi sudah ada
  THEN return 200 OK (dengan info laporan existing)
  ELSE generate laporan baru
```

### ✅ Validasi 4: File Existence Check
```
IF file laporan tidak ada di storage
  THEN return 404 Not Found
  MESSAGE: "File laporan tidak ditemukan di server"
```

---

## PDF Template Features

📄 **Isi Laporan Output PDF**:
- Header: Judul dan informasi doc  
- Section I: Informasi Inspeksi (ID, jenis kapal, area, status validasi)
- Section II: Data Pengujian UT (t_origin, nilai_ketebalan, batas_standar, frekuensi, level, dll)
- Section III: Hasil Analisis (jenis cacat, kedalaman, amplitudo, status akseptansi, dll)
- Section IV: Catatan Validasi (jika ada)
- Footer: Metadata generate timestamp

---

## API Endpoints

| Method | Endpoint | Action | Response |
|--------|----------|--------|----------|
| **POST** | `/reports/{idInspeksi}/generate` | Generate laporan PDF | JSON (201/403/404/500) |
| **GET** | `/reports/{idLaporan}/download` | Download PDF file | Binary (200/404/500) |
| **GET** | `/reports/{idLaporan}/preview` | Preview PDF di browser | PDF inline (200/404/500) |
| **GET** | `/reports/inspeksi/{idInspeksi}` | List laporan by inspeksi | JSON array (200/500) |

---

## Response Examples

### Success: Generate Laporan (201 Created)
```json
{
  "success": true,
  "message": "Laporan berhasil di-generate",
  "report_id": 1,
  "laporan": {
    "id_laporan": 1,
    "id_inspeksi": "INS001",
    "nama_laporan": "Laporan_UT_INS001_20260419120530.pdf",
    "file_path": "reports/Laporan_UT_INS001_20260419120530.pdf",
    "status_laporan": "generated",
    "tanggal_generate": "2026-04-19T12:05:30Z",
    "generated_by": 1,
    "created_at": "2026-04-19T12:05:30Z"
  }
}
```

### Error: Belum Tervalidasi (403 Forbidden)
```json
{
  "success": false,
  "message": "Hasil belum divalidasi. Status validasi harus 'validated'",
  "current_status": "pending"
}
```

### Error: Inspeksi Tidak Ada (404 Not Found)
```json
{
  "success": false,
  "message": "Data inspeksi tidak ditemukan"
}
```

---

## Key Implementation Details

### ReportGeneratorService::generate()
```
1. Load inspeksi data dari database
2. Validasi status_validasi == "validated"
3. Prepare data array untuk Blade template
4. Generate PDF via DomPDF dari view
5. Save file ke storage/app/public/reports/
6. Create record di tabel reports
7. Return Report model
```

### ReportController::generate()
```
1. Validate: data inspeksi exists?
2. Validate: status_validasi == "validated"?
3. Check: laporan duplikat?
4. Call ReportGeneratorService::generate()
5. Return JSON response
```

### ReportController::download()
```
1. Find laporan dari ID
2. Check: file exists di storage?
3. Call Report->incrementDownloadCount()
4. Return file download response
```

---

## Download Tracking

| Column | Updated | Behavior |
|--------|---------|----------|
| `tanggal_download` | Saat download pertama | Set once, tidak berubah setelah itu |
| `jumlah_download` | Setiap kali download | Increment counter |
| `status_laporan` | Saat download pertama | "generated" → "downloaded" |

---

## Installation & Setup

### 1. Install DomPDF Package
```bash
composer require barryvdh/laravel-dompdf
```

### 2. Config Publication (optional)
```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### 3. Run Migration
```bash
php artisan migrate
```

### 4. Create Storage Link (untuk akses public)
```bash
php artisan storage:link
```

---

## File Structure

```
app/
├── Http/Controllers/
│   └── ReportController.php          (4 methods)
├── Models/
│   └── Report.php                    (relationships, scopes)
└── Services/
    └── ReportGeneratorService.php    (PDF generation logic)

resources/views/
└── reports/
    └── report_template.blade.php     (PDF template)

database/migrations/
└── 2026_04_19_000010_create_reports_table.php

routes/
└── web.php                           (4 new routes)
```

---

## Testing Scenarios

- ✅ Generate laporan → status "validated" → berhasil generate + file tercipta
- ✅ Generate laporan → status "pending" → error 403 Forbidden
- ✅ Generate laporan → inspeksi tidak ada → error 404 Not Found
- ✅ Download laporan → file ada → download berhasil + counter increment
- ✅ Download laporan → file tidak ada → error 404 Not Found
- ✅ Multiple downloads → jumlah_download increment setiap kali
- ✅ Preview laporan → PDF tampil inline di browser

---

## Commit Message

```
feat: add PDF report generation for Ultrasonic Testing results with validation

- Add reports table migration with FK to inspeksi_ultrasonic and users
- Add Report model with relationships (InspeksiUltrasonic, User)
- Add scopes: byInspection(), downloaded()
- Add ReportGeneratorService for PDF generation logic via DomPDF
- Add ReportController with 4 endpoints:
  * generate() - validate & generate report
  * download() - download PDF with tracking
  * preview() - inline preview PDF in browser
  * listByInspection() - list reports
- Add Blade template report_template.blade.php for PDF layout
- Add routes: POST /reports/{idInspeksi}/generate, GET /reports/{idLaporan}/download, etc
- Add validation: status_validasi must be "validated" before generate
- Add download tracking: tanggal_download, jumlah_download, status update
- Ensure data immutability: is_locked = true prevents changes after validation

Acceptance Criteria:
- AC1: Generate laporan setelah validasi ✅
- AC2: Tampilkan isi laporan dalam PDF ✅
- AC3: Tolak generate jika belum divalidasi ✅

Closes: Tugas 3.5 - Generate Laporan UT
```

---

## Dependencies

- `laravel/framework` ^11.0
- `barryvdh/laravel-dompdf` ^2.0
- `php` ^8.2

---

## Notes

- Library DomPDF compatible dengan Laravel 11
- Storage disk: `public` untuk public access
- Auth requirement: `auth()->id()` untuk generated_by tracking
- Recommendation: Untuk production, gunakan Queue job untuk generate PDF async
