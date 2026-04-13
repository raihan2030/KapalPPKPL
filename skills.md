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