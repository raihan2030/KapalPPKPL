# Database Design - TUGAS 3.5: Generate Laporan Ultrasonic Testing

## NEW TABLE: reports

```sql
CREATE TABLE reports (
    id_laporan BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Primary Key Laporan',
    
    -- Foreign Keys
    id_inspeksi VARCHAR(20) NOT NULL UNIQUE COMMENT 'ID Inspeksi (FK to inspeksi_ultrasonic)',
    generated_by BIGINT UNSIGNED NOT NULL COMMENT 'User ID yang generate laporan (FK to users)',
    
    -- Laporan File Info
    nama_laporan VARCHAR(100) NOT NULL COMMENT 'Nama file PDF laporan',
    file_path VARCHAR(255) NOT NULL COMMENT 'Path file di storage/app/public/',
    
    -- Laporan Status
    status_laporan ENUM('generated', 'downloaded', 'archived') 
        DEFAULT 'generated' 
        COMMENT 'Status: generated=baru, downloaded=sudah diakses, archived=arsip',
    
    -- Timestamps
    tanggal_generate DATETIME NOT NULL COMMENT 'Waktu laporan di-generate',
    tanggal_download DATETIME NULL COMMENT 'Waktu download pertama kali (NULL = belum)',
    jumlah_download INT DEFAULT 0 COMMENT 'Counter jumlah download',
    
    -- Catatan
    catatan_laporan TEXT NULL COMMENT 'Catatan tambahan tentang laporan',
    
    -- System Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Constraints
    FOREIGN KEY (id_inspeksi) 
        REFERENCES inspeksi_ultrasonic(id_inspeksi) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    
    FOREIGN KEY (generated_by) 
        REFERENCES users(id) 
        ON DELETE RESTRICT ON UPDATE CASCADE,
    
    -- Indexes
    INDEX idx_id_inspeksi (id_inspeksi),
    INDEX idx_status_laporan (status_laporan),
    INDEX idx_generated_by (generated_by),
    INDEX idx_tanggal_generate (tanggal_generate)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```

---

## Relational Schema

```
┌─────────────┐
│    users    │
├─────────────┤
│ id (PK)     │ ◄────────────────┐ generated_by (FK)
│ name        │                  │
│ email       │                  │
│ password    │                  │
│ ...         │                  │
└─────────────┘                  │
                                 │
                    ┌────────────┴──────────────┐
                    │                           │
        ┌──────────────────────┐    ┌──────────────────────┐
        │ inspeksi_ultrasonic  │    │     reports          │
        ├──────────────────────┤    ├──────────────────────┤
        │ id (PK)              │    │ id_laporan (PK)      │
        │ id_inspeksi (UK,FK)  │◄───│ id_inspeksi (FK,UK)  │
        │ jenis_kapal          │    │ nama_laporan         │
        │ area_kapal           │    │ file_path            │
        │ nilai_ketebalan      │    │ status_laporan       │
        │ batas_standar        │    │ tanggal_generate     │
        │ echo_amplitude       │    │ tanggal_download     │
        │ status_ketebalan     │    │ jumlah_download      │
        │ status_akseptansi    │    │ generated_by (FK)◄───┘
        │ status_validasi ◄────├────┤ catatan_laporan
        │ validated_at         │    │ created_at
        │ validated_by         │    │ updated_at
        │ is_locked            │    └──────────────────────┘
        │ catatan_validasi     │
        │ ...                  │
        └──────────────────────┘
```

---

## Column Details

| Column | Type | Size | Null | Default | Key | Comment |
|--------|------|------|------|---------|-----|---------|
| id_laporan | BIGINT UNSIGNED | - | NO | AUTO_INC | PK | Primary key |
| id_inspeksi | VARCHAR | 20 | NO | - | FK,UK | Foreign key to inspeksi_ultrasonic |
| nama_laporan | VARCHAR | 100 | NO | - | - | PDF filename (e.g., Laporan_UT_INS001_20260419.pdf) |
| file_path | VARCHAR | 255 | NO | - | - | Storage path (e.g., reports/Laporan_UT_INS001_20260419.pdf) |
| status_laporan | ENUM | - | NO | 'generated' | IX | Status tracking |
| tanggal_generate | DATETIME | - | NO | - | IX | When PDF was generated |
| tanggal_download | DATETIME | - | YES | NULL | - | First access timestamp |
| jumlah_download | INT | - | NO | 0 | - | Download counter |
| generated_by | BIGINT UNSIGNED | - | NO | - | FK,IX | User ID who generated |
| catatan_laporan | TEXT | - | YES | NULL | - | Optional notes |
| created_at | TIMESTAMP | - | YES | NOW() | - | Created timestamp |
| updated_at | TIMESTAMP | - | YES | NOW() | - | Updated timestamp |

---

## Access Patterns & Queries

### 1. Generate Laporan (INSERT)
```sql
INSERT INTO reports 
(id_inspeksi, nama_laporan, file_path, status_laporan, 
 tanggal_generate, generated_by, catatan_laporan) 
VALUES 
('INS001', 'Laporan_UT_INS001_20260419120530.pdf', 
 'reports/Laporan_UT_INS001_20260419120530.pdf', 
 'generated', '2026-04-19 12:05:30', 1, 
 'Laporan otomatis di-generate');
```

### 2. Get Laporan by ID (SELECT)
```sql
SELECT * FROM reports WHERE id_laporan = 1;
```

### 3. Find Existing Report for Inspection
```sql
SELECT * FROM reports 
WHERE id_inspeksi = 'INS001';
```

### 4. Update Download Counter & Status
```sql
UPDATE reports 
SET jumlah_download = jumlah_download + 1,
    status_laporan = 'downloaded',
    tanggal_download = NOW()
WHERE id_laporan = 1 
  AND tanggal_download IS NULL;
```

### 5. List All Reports for User
```sql
SELECT r.*, u.name 
FROM reports r
JOIN users u ON r.generated_by = u.id
WHERE r.generated_by = 1
ORDER BY r.tanggal_generate DESC;
```

### 6. Reports Downloaded Statistics
```sql
SELECT 
    id_laporan,
    nama_laporan,
    jumlah_download,
    tanggal_download,
    DATEDIFF(NOW(), tanggal_generate) AS days_since_generate
FROM reports 
WHERE status_laporan = 'downloaded'
ORDER BY jumlah_download DESC;
```

---

## Data Validation Rules

### inspeksi_ultrasonic (Source Data)

**MUST HAVE for Report Generation:**
- ✅ `status_validasi = 'validated'` (CRITICAL)
- ✅ `validated_at IS NOT NULL`
- ✅ `validated_by IS NOT NULL`
- ✅ `is_locked = true` (data immutable)
- ✅ All key fields populated: nilai_ketebalan, batas_standar, etc.

**Return if NOT VALID:**
- ❌ `status_validasi IN ('pending', 'rejected')` → 403 Forbidden
- ❌ `id_inspeksi NOT EXISTS` → 404 Not Found
- ❌ `is_locked = false` → Error (not finalized)

### reports (Destination Table)

**Unique Constraints:**
- Only 1 report per `id_inspeksi` (UNIQUE KEY)
- Prevents duplicate PDF generation

**Status Transitions:**
- Generated → (first access) → Downloaded
- Downloaded → (archival) → Archived
- Never reverts backward

---

## Performance Considerations

### Indexes for Query Optimization

1. **id_inspeksi (FK, UNIQUE)**
   - Purpose: Lookup reports by inspection
   - Cardinality: Very High
   - Used in: WHERE id_inspeksi = ?

2. **status_laporan**
   - Purpose: Filter by status
   - Cardinality: Low (3 values)
   - Used in: WHERE status_laporan = 'downloaded'

3. **generated_by**
   - Purpose: Reports per user
   - Cardinality: Medium
   - Used in: WHERE generated_by = ?

4. **tanggal_generate**
   - Purpose: Sort by date
   - Cardinality: Very High
   - Used in: ORDER BY tanggal_generate DESC

### Query Optimization Tips

```php
// ✅ GOOD: Specify columns
SELECT id_laporan, nama_laporan, tanggal_generate 
FROM reports WHERE id_inspeksi = 'INS001';

// ❌ BAD: SELECT * is expensive
SELECT * FROM reports WHERE id_inspeksi = 'INS001';

// ✅ GOOD: Use eager loading in Eloquent
$report->load('inspeksiUltrasonic', 'userGenerator');

// ✅ GOOD: Limit result set
SELECT * FROM reports LIMIT 100;
```

---

## Migration Script

```php
// Filename: 2026_04_19_000010_create_reports_table.php

Schema::create('reports', function (Blueprint $table) {
    $table->id('id_laporan');
    
    $table->string('id_inspeksi', 20);
    $table->foreign('id_inspeksi')
        ->references('id_inspeksi')
        ->on('inspeksi_ultrasonic')
        ->onDelete('cascade');
    
    $table->string('nama_laporan', 100);
    $table->string('file_path', 255);
    
    $table->enum('status_laporan', ['generated', 'downloaded', 'archived'])
        ->default('generated');
    
    $table->dateTime('tanggal_generate');
    $table->unsignedBigInteger('generated_by');
    $table->foreign('generated_by')
        ->references('id')
        ->on('users')
        ->onDelete('restrict');
    
    $table->dateTime('tanggal_download')->nullable();
    $table->integer('jumlah_download')->default(0);
    
    $table->text('catatan_laporan')->nullable();
    
    $table->timestamps();
    
    // Indexes for performance
    $table->index('id_inspeksi');
    $table->index('status_laporan');
    $table->index('generated_by');
    $table->index('tanggal_generate');
    
    // Unique constraint
    $table->unique('id_inspeksi');
});
```

---

## Backup & Recovery

### What to Backup

1. **Database Table**
   ```bash
   mysqldump -u root kapalppkpl reports > reports_backup.sql
   ```

2. **PDF Files**
   ```bash
   cp -r storage/app/public/reports storage/backups/
   ```

3. **Full Database**
   ```bash
   mysqldump -u root kapalppkpl > full_backup.sql
   ```

### Recovery Steps

```bash
# Restore from backup
mysql -u root kapalppkpl < reports_backup.sql

# Restore files
cp -r storage/backups/reports storage/app/public/
```

---

## Storage Requirements

### Disk Space Estimation

- **Average PDF Size**: 500 KB per report
- **Monthly Volume**: 100 reports/month
- **Annual Storage**: ~600 MB

```
Monthly: 100 × 500 KB = ~50 MB
Yearly:  12 × 50 MB = ~600 MB
5 Years: 5 × 600 MB = ~3 GB
```

### File Organization

```
storage/
└── app/
    └── public/
        └── reports/
            ├── Laporan_UT_INS001_20260419120530.pdf
            ├── Laporan_UT_INS002_20260419121045.pdf
            ├── Laporan_UT_INS003_20260419121500.pdf
            └── ...
```

---

## Security Considerations

### 1. File Access Control
- PDF files stored in `public/` disk (accessible via URL)
- No sensitive data in file names (sanitized)
- File path not exposed in API response (only ID reference)

### 2. Database Access Control
- `generated_by` field tracks who created report
- Foreign key constraints prevent orphaned records
- `ON DELETE RESTRICT` on users FK prevents accidental deletion

### 3. Data Integrity
- `is_locked = true` ensures data immutability
- `status_validasi = 'validated'` before generation
- Unique constraint on id_inspeksi prevents duplicates

### 4. Timestamp Tracking
- `tanggal_generate` for audit trail
- `tanggal_download` for access tracking
- `created_at/updated_at` for system tracking

---

## Future Enhancements

1. **Archive Strategy**
   - Move old reports to archive storage
   - Compress archived PDFs

2. **Digital Signature**
   - Add signature verification
   - Hash file integrity check

3. **Versioning**
   - Allow multiple versions per inspection
   - Track revision history

4. **Async Generation**
   - Queue job for PDF generation
   - Email notification on completion

5. **Multi-format Export**
   - CSV export option
   - Excel export option
   - JSON API format

