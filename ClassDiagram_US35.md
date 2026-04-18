# Class Diagram - US 3.5: Generate Laporan Ultrasonic Testing

## PlantUML Text Format

```
@startuml ClassDiagram_US35_GenerateLaporan

!define CLASSNAME_FONT_SIZE 12

' =================== MODELS ===================

class InspeksiUltrasonic {
    -# id: BIGINT (PK)
    -# id_inspeksi: STRING (UK,FK)
    -# jenis_kapal: STRING
    -# area_kapal: STRING
    - nilai_ketebalan: DECIMAL
    - batas_standar: DECIMAL
    - echo_amplitude: STRING
    - status_ketebalan: STRING
    - status_akseptansi: STRING
    - status_validasi: ENUM ['pending','validated','rejected']
    - validated_at: DATETIME
    - validated_by: BIGINT
    - is_locked: BOOLEAN
    - catatan_validasi: TEXT
    --
    + getValidationStatus(): STRING
    + isValidated(): BOOLEAN
    + isLocked(): BOOLEAN
    + reports(): HAS_MANY
}

class Report {
    -# id_laporan: BIGINT (PK)
    -# id_inspeksi: STRING (FK,UK)
    -# generated_by: BIGINT (FK)
    - nama_laporan: STRING
    - file_path: STRING
    - status_laporan: ENUM ['generated','downloaded','archived']
    - tanggal_generate: DATETIME
    - tanggal_download: DATETIME (nullable)
    - jumlah_download: INT
    - catatan_laporan: TEXT
    - created_at: TIMESTAMP
    - updated_at: TIMESTAMP
    --
    + inspeksiUltrasonic(): BELONGS_TO
    + userGenerator(): BELONGS_TO
    + scopeByInspection($query, $id): QUERY_BUILDER
    + scopeDownloaded($query): QUERY_BUILDER
    + incrementDownloadCount(): VOID
    + getDownloadCount(): INT
    + isGenerated(): BOOLEAN
    + isDownloaded(): BOOLEAN
}

class User {
    -# id: BIGINT (PK)
    - name: STRING
    - email: STRING
    - password: STRING
    - created_at: TIMESTAMP
    - updated_at: TIMESTAMP
    --
    + reports(): HAS_MANY
}

' =================== CONTROLLERS ===================

class ReportController {
    -# reportGeneratorService: ReportGeneratorService
    --
    + generate(idInspeksi): JSON_RESPONSE
    + download(idLaporan): PDF_RESPONSE
    + preview(idLaporan): PDF_INLINE
    + listByInspection(idInspeksi): JSON_ARRAY
}

' =================== SERVICES ===================

class ReportGeneratorService {
    --
    + generate(idInspeksi, userId): Report
    - validateInspectionData(inspeksi): BOOLEAN
    - prepareData(inspeksi): ARRAY
    - generatePDF(data): PDF
    - saveFile(pdf): STRING
    - createDatabaseRecord(idInspeksi, filePath, userId): Report
}

' =================== REQUESTS (Form Validation) ===================

class GenerateLaporanRequest {
    #rules(): ARRAY
    #messages(): ARRAY
}

' =================== RESOURCES / RESPONSES ===================

class ReportResource {
    +toArray(): ARRAY
}

' =================== RELATIONSHIPS ===================

InspeksiUltrasonic "1" -- "*" Report : generates
Report "1" -- "1" User : generatedBy
User "1" -- "*" Report : creates

ReportController --> ReportGeneratorService : uses
ReportGeneratorService --> InspeksiUltrasonic : reads
ReportGeneratorService --> Report : creates

' Add some styling
skinparam classBorderthickness 1.5
skinparam classBackgroundColor #E1F5FE / #FFFFFF
skinparam arrowColor #1976D2

@enduml
```

---

## ASCII Class Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                     InspeksiUltrasonic                          │
├─────────────────────────────────────────────────────────────────┤
│ - id_inspeksi: STRING (PK, FK)                                 │
│ - jenis_kapal: STRING                                           │
│ - area_kapal: STRING                                            │
│ - nilai_ketebalan: DECIMAL                                      │
│ - status_validasi: ENUM ('pending','validated','rejected')     │
│ - validated_at: DATETIME                                        │
│ - validated_by: BIGINT                                          │
│ - is_locked: BOOLEAN                                            │
│ - catatan_validasi: TEXT                                        │
├─────────────────────────────────────────────────────────────────┤
│ + getValidationStatus(): STRING                                 │
│ + isValidated(): BOOLEAN                                        │
│ + isLocked(): BOOLEAN                                           │
│ + reports(): HAS_MANY<Report>                                  │
└─────────────────────────────────────────────────────────────────┘
                              △
                              │ 1:*
                              │ generates
                              │
┌─────────────────────────────────────────────────────────────────┐
│                         Report                                  │
├─────────────────────────────────────────────────────────────────┤
│ - id_laporan: BIGINT (PK)                                      │
│ - id_inspeksi: STRING (FK, UNIQUE)                             │
│ - nama_laporan: STRING                                          │
│ - file_path: STRING                                             │
│ - status_laporan: ENUM ('generated','downloaded','archived')   │
│ - tanggal_generate: DATETIME                                    │
│ - tanggal_download: DATETIME (nullable)                        │
│ - jumlah_download: INT                                          │
│ - generated_by: BIGINT (FK)                                    │
│ - catatan_laporan: TEXT                                         │
├─────────────────────────────────────────────────────────────────┤
│ + inspeksiUltrasonic(): BELONGS_TO<InspeksiUltrasonic>         │
│ + userGenerator(): BELONGS_TO<User>                            │
│ + scopeByInspection(query, id): QueryBuilder                  │
│ + scopeDownloaded(query): QueryBuilder                         │
│ + incrementDownloadCount(): VOID                               │
│ + getDownloadCount(): INT                                       │
│ + isGenerated(): BOOLEAN                                        │
│ + isDownloaded(): BOOLEAN                                       │
└─────────────────────────────────────────────────────────────────┘
                 │                              △
                 │ 1:*                         │ 1:1
                 │ creates                     │ generatedBy
                 │                             │
                 └─────────────────────────────┘
                          │
                          │
       ┌──────────────────────────────────┐
       │            User                  │
       ├──────────────────────────────────┤
       │ - id: BIGINT (PK)               │
       │ - name: STRING                  │
       │ - email: STRING                 │
       │ - password: STRING              │
       ├──────────────────────────────────┤
       │ + reports(): HAS_MANY<Report>   │
       └──────────────────────────────────┘


┌──────────────────────────────────────────────────────────┐
│           ReportController                             │
├──────────────────────────────────────────────────────────┤
│ - reportGeneratorService: ReportGeneratorService       │
├──────────────────────────────────────────────────────────┤
│ + generate(idInspeksi): JSON RESPONSE (201/403/404)    │
│ + download(idLaporan): PDF BINARY (200/404)            │
│ + preview(idLaporan): PDF INLINE (200/404)             │
│ + listByInspection(idInspeksi): JSON ARRAY (200/500)   │
└──────────────────────────────────────────────────────────┘
              △
              │ uses
              │
┌──────────────────────────────────────────────────────────┐
│      ReportGeneratorService                            │
├──────────────────────────────────────────────────────────┤
│                                                         │
├──────────────────────────────────────────────────────────┤
│ + generate(idInspeksi, userId): Report               │
│                                                         │
│ PRIVATE METHODS:                                       │
│ - validateInspectionData(inspeksi): BOOLEAN          │
│   * Check: status_validasi == "validated"?           │
│   * Check: is_locked == true?                        │
│                                                         │
│ - prepareData(inspeksi): ARRAY                        │
│   * Extract all fields from inspeksi                 │
│   * Format for Blade template                        │
│                                                         │
│ - generatePDF(data): PDF_STREAM                       │
│   * Load view: report_template.blade.php             │
│   * Render via DomPDF                                │
│                                                         │
│ - saveFile(pdf): STRING                              │
│   * Save to storage/app/public/reports/              │
│   * Return file path                                 │
│                                                         │
│ - createDatabaseRecord(...): Report                  │
│   * Create Report model                              │
│   * Save to database                                 │
│                                                         │
│ DEPENDENCIES:                                          │
│   • Barryvdh\DomPDF\Facade\Pdf                       │
│   • Illuminate\Support\Facades\Storage               │
│   • App\Models\InspeksiUltrasonic                    │
│   • App\Models\Report                                │
└──────────────────────────────────────────────────────────┘
              △
              │ uses
              │
┌──────────────────────────────────────────────────────────┐
│    Blade Template: report_template.blade.php           │
├──────────────────────────────────────────────────────────┤
│                                                         │
│ Sections:                                              │
│ 1. Header                                             │
│    - Title: "LAPORAN HASIL PENGUJIAN ULTRASONIC"     │
│    - Date & timestamp                                │
│                                                         │
│ 2. Informasi Inspeksi                                │
│    - ID Inspeksi                                     │
│    - Jenis Kapal                                     │
│    - Area Kapal                                      │
│    - Status Validasi (badge)                         │
│                                                         │
│ 3. Data Pengujian UT (Table)                         │
│    - t_origin                                        │
│    - nilai_ketebalan                                 │
│    - batas_standar                                   │
│    - frekuensi_ut                                    │
│    - level_pengujian                                 │
│                                                         │
│ 4. Hasil Analisis (Table)                            │
│    - jenis_cacat                                     │
│    - kedalaman_cacat                                 │
│    - echo_amplitude                                  │
│    - status_ketebalan                                │
│    - status_akseptansi                               │
│                                                         │
│ 5. Catatan Validasi (if not null)                    │
│                                                         │
│ 6. Footer                                             │
│    - Legal notice                                     │
│    - Generation timestamp                             │
│                                                         │
└──────────────────────────────────────────────────────────┘
```

---

## Method Details

### ReportController::generate()

```
HTTP: POST /reports/{idInspeksi}/generate

Flow:
1. Receive idInspeksi parameter
2. Find InspeksiUltrasonic WHERE id_inspeksi = idInspeksi
   | If not found → 404 Error
   
3. Validate: inspeksi.status_validasi == "validated"
   | If NOT → 403 Forbidden Error
   | If YES → Continue
   
4. Check: Report exists for id_inspeksi?
   | If YES → Return 200 OK (existing report info)
   | If NO → Continue to generation
   
5. Call ReportGeneratorService.generate(idInspeksi, auth().id())
   | Returns Report model
   
6. Return 201 Created + Report JSON

Error Codes:
- 200 OK: Report already exists
- 201 Created: Report generated successfully
- 403 Forbidden: Data not validated
- 404 Not Found: Inspection not found
- 500 Internal Server Error: Exception during generation
```

### ReportGeneratorService::generate()

```
Flow:
1. Load InspeksiUltrasonic data
   
2. Validate: status_validasi == "validated" && is_locked == true
   | If invalid → throw Exception
   
3. Prepare data array:
   - Copy all inspeksi fields
   - Add tanggal_generate = NOW()
   - Add calculated fields
   
4. Load Blade view:
   - View: resources/views/reports/report_template.blade.php
   - Pass: $inspeksi, $tanggal_generate
   
5. Generate PDF via DomPDF:
   - Pdf::loadView('reports.report_template', $data)
   - Return PDF stream
   
6. Save PDF file:
   - Filename: Laporan_UT_{id_inspeksi}_{timestamp}.pdf
   - Path: storage/app/public/reports/
   - Storage::disk('public')->put($filePath, $pdf->output())
   
7. Create database record:
   - INSERT INTO reports (
       id_inspeksi, nama_laporan, file_path,
       status_laporan, tanggal_generate, generated_by
     )
   
8. Return Report model for API response
```

### ReportController::download()

```
HTTP: GET /reports/{idLaporan}/download

Flow:
1. Find Report BY id_laporan
   | If not found → 404 Error
   
2. Check: File exists in storage?
   | If not → 404 Error
   
3. Call report.incrementDownloadCount()
   - Increment jumlah_download
   - Set tanggal_download = NOW() (if null only)
   - Update status_laporan = "downloaded"
   
4. Return file download response:
   - Content-Type: application/pdf
   - Content-Disposition: attachment

Response:
- 200 OK: PDF file (as binary attachment)
- 404 Not Found: File not found
- 500 Internal Server Error: Exception during download
```

### ReportController::preview()

```
HTTP: GET /reports/{idLaporan}/preview

Flow:
1. Find Report BY id_laporan
   | If not found → 404 Error
   
2. Check: File exists in storage?
   | If not → 404 Error
   
3. Call report.incrementDownloadCount()
   - Same as download
   
4. Return file inline response:
   - Content-Type: application/pdf
   - Content-Disposition: inline

Response:
- 200 OK: PDF file displayed inline in browser
- 404 Not Found: File not found
- 500 Internal Server Error: Exception
```

---

## Data Flow Diagram

```
Request
  │
  ├─► ReportController::generate()
  │    │
  │    ├─► Validate: data exists?
  │    │    └─► 404 if NOT found
  │    │
  │    ├─► Validate: status_validasi == "validated"?
  │    │    └─► 403 Forbidden if NOT
  │    │
  │    ├─► Check: report exists?
  │    │    └─► 200 OK return existing if YES
  │    │
  │    └─► Call ReportGeneratorService::generate()
  │         │
  │         ├─► Load inspeksi data
  │         ├─► Validate immutability (is_locked=true)
  │         ├─► Prepare data array
  │         ├─► Load Blade template
  │         ├─► Generate PDF via DomPDF
  │         ├─► Save file to storage/
  │         ├─► Create Report record in DB
  │         └─► Return Report model
  │
  └─► Return JSON Response (201 or error code)
```

---

## Class Relationships Summary

| From | To | Type | Cardinality |
|------|-----|------|------------|
| Report | InspeksiUltrasonic | belongsTo | 1:1 |
| Report | User | belongsTo | 1:1 |
| InspeksiUltrasonic | Report | hasMany | 1:* |
| User | Report | hasMany | 1:* |
| ReportController | ReportGeneratorService | composition | 1:1 |
| ReportGeneratorService | Report | creates | 1:* |

---

## Design Patterns Used

1. **Service Layer Pattern**
   - ReportGeneratorService encapsulates business logic
   - Separates concerns from controller

2. **Model Relationships**
   - Eloquent belongsTo/hasMany relationships
   - Type-safe relationships between models

3. **Scopes**
   - Report::byInspection() local scope
   - Report::downloaded() local scope
   - Chainable query builders

4. **Facade Pattern**
   - DomPDF Facade for PDF generation
   - Storage Facade for file operations

5. **DTO/Payload Pattern**
   - Data arrays prepared for views
   - Structured responses for API

