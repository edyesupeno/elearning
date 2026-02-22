# E-Learning System

Sistem E-Learning dengan Laravel 12 dan Bootstrap 5

## Fitur
- 3 Role: Admin, Guru, Murid
- Manajemen Kursus
- Manajemen Materi Pembelajaran
- Enrollment Murid ke Kursus
- Responsive Design dengan Sticky Bottom Menu untuk Mobile

## Setup

1. Buat database MySQL dengan nama `elearning`
```sql
CREATE DATABASE elearning;
```

2. Update file `.env` dengan kredensial database Anda:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elearning
DB_USERNAME=root
DB_PASSWORD=your_password
```

3. Jalankan migration dan seeder:
```bash
php artisan migrate
php artisan db:seed --class=RoleSeeder
```

4. Jalankan aplikasi:
```bash
php artisan serve
```

5. Akses aplikasi di `http://localhost:8000`

## Default Login

Admin:
- Email: admin@elearning.com
- Password: password

## Role & Akses

### Admin
- Melihat statistik sistem
- Akses ke semua fitur

### Guru
- Membuat dan mengelola kursus
- Menambah, edit, hapus materi pembelajaran
- Melihat jumlah murid yang terdaftar

### Murid
- Mendaftar ke kursus
- Melihat materi pembelajaran
- Akses kursus yang sudah didaftarkan

## Teknologi
- Laravel 12
- MySQL
- Bootstrap 5
- Bootstrap Icons
