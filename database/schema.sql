CREATE DATABASE IF NOT EXISTS akademik
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE akademik;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    program_studi VARCHAR(100) NOT NULL,
    semester TINYINT UNSIGNED NOT NULL,
    email VARCHAR(100) NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS jadwal_kuliah (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_mk VARCHAR(20) NOT NULL,
    mata_kuliah VARCHAR(100) NOT NULL,
    hari VARCHAR(12) NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    ruang VARCHAR(50) NOT NULL,
    dosen VARCHAR(100) NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS nilai_akademik (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT UNSIGNED NOT NULL,
    mata_kuliah VARCHAR(100) NOT NULL,
    semester TINYINT UNSIGNED NOT NULL,
    nilai_tugas DECIMAL(5,2) NOT NULL,
    nilai_uts DECIMAL(5,2) NOT NULL,
    nilai_uas DECIMAL(5,2) NOT NULL,
    nilai_akhir DECIMAL(5,2) NOT NULL,
    nilai_huruf VARCHAR(2) NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_nilai_mahasiswa
        FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT unik_nilai_mahasiswa
        UNIQUE (mahasiswa_id, mata_kuliah, semester)
) ENGINE=InnoDB;

INSERT IGNORE INTO mahasiswa (nim, nama, program_studi, semester, email) VALUES
    ('231001', 'Alya Putri', 'Sistem Informasi', 3, 'alya@example.com'),
    ('231002', 'Bima Pratama', 'Teknik Informatika', 3, 'bima@example.com');

INSERT INTO jadwal_kuliah (kode_mk, mata_kuliah, hari, jam_mulai, jam_selesai, ruang, dosen)
SELECT 'IF301', 'Pemrograman Web', 'Senin', '08:00:00', '10:00:00', 'Lab Komputer', 'Dr. Rina Wijaya'
WHERE NOT EXISTS (SELECT 1 FROM jadwal_kuliah WHERE kode_mk = 'IF301');
