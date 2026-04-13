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
