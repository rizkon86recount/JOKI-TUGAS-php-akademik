# Portal Akademik PHP Native

Aplikasi akademik sederhana dengan empat fitur utama:

- CRUD data mahasiswa
- CRUD jadwal kuliah
- CRUD nilai akademik dengan perhitungan nilai akhir otomatis
- Kalkulator aritmetika dasar

## Teknologi

- PHP 8.2 Native
- MySQL 8.4
- MySQLi prepared statement
- Bootstrap 5.3 dan CSS kustom
- Docker Compose

## Menjalankan aplikasi

Pastikan Docker Desktop aktif, kemudian jalankan:

```powershell
docker compose up --build
```

Buka aplikasi di [http://localhost:8080](http://localhost:8080).

Database dan tabel dibuat otomatis dari `database/schema.sql` ketika volume database pertama kali dibuat.

## Struktur utama

```text
database/
  schema.sql              Skema dan data awal database
src/
  assets/css/style.css    Tampilan aplikasi
  config/database.php     Koneksi database
  includes/               Helper dan layout bersama
  index.php               Menu utama
  mahasiswa*.php          Modul data mahasiswa
  jadwal*.php             Modul jadwal kuliah
  nilai*.php              Modul nilai akademik
  kalkulator.php          Kalkulator
```

## Rumus nilai akhir

```text
Nilai akhir = (Tugas × 30%) + (UTS × 30%) + (UAS × 40%)
```

Jika konfigurasi database ingin diubah, sesuaikan environment `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, dan `DB_PASS` pada `docker-compose.yml`.
