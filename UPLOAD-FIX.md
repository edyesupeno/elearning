# Perbaikan Upload Logo & Favicon

## Masalah yang Ditemukan

PHP development server (`php artisan serve`) memiliki batasan upload file default:
- `upload_max_filesize` = 2MB
- `post_max_size` = 8MB

File `.user.ini` tidak bekerja dengan `php artisan serve`, hanya bekerja dengan Apache/nginx + PHP-FPM.

## Solusi yang Diterapkan

### 1. Update Validasi
- Logo: maksimal 2MB (sebelumnya 5MB)
- Favicon: maksimal 1MB (sebelumnya 2MB)

### 2. Client-side Validation
Ditambahkan pengecekan ukuran file di browser sebelum upload untuk memberikan peringatan lebih awal.

### 3. Script untuk Menjalankan Server

Gunakan script `start-server.sh` untuk menjalankan server dengan konfigurasi PHP yang lebih besar:

```bash
./start-server.sh
```

Script ini akan menjalankan:
```bash
php -d upload_max_filesize=10M -d post_max_size=20M -d memory_limit=256M artisan serve
```

## Cara Menggunakan

### Opsi 1: Gunakan Script (Rekomendasi)
```bash
./start-server.sh
```

### Opsi 2: Manual dengan Custom PHP Settings
```bash
php -d upload_max_filesize=10M -d post_max_size=20M artisan serve
```

### Opsi 3: Gunakan dengan Port Custom
```bash
php -d upload_max_filesize=10M -d post_max_size=20M artisan serve --port=8080
```

## Tips Upload Logo

1. **Kompres gambar** sebelum upload menggunakan tools online:
   - TinyPNG (https://tinypng.com)
   - Squoosh (https://squoosh.app)
   - ImageOptim (untuk Mac)

2. **Ukuran rekomendasi**:
   - Logo: 512x512px, format PNG/JPG, < 2MB
   - Favicon: 32x32px atau 64x64px, format PNG/ICO, < 1MB

3. **Format yang didukung**:
   - Logo: JPG, PNG, SVG
   - Favicon: PNG, ICO

## Testing

1. Restart server menggunakan `./start-server.sh`
2. Login sebagai admin
3. Buka menu Setting
4. Upload logo (maksimal 2MB)
5. Upload favicon (maksimal 1MB)
6. Klik "Simpan Pengaturan"

Jika berhasil, akan muncul pesan sukses dan gambar akan langsung terlihat.

## Troubleshooting

### Upload masih gagal?
1. Pastikan server dijalankan dengan `./start-server.sh`
2. Cek ukuran file di console browser (F12 > Console)
3. Pastikan file < 2MB untuk logo, < 1MB untuk favicon
4. Cek log Laravel: `tail -f storage/logs/laravel.log`

### Gambar tidak muncul?
1. Pastikan storage link sudah dibuat: `php artisan storage:link`
2. Cek folder `storage/app/public/settings/` ada file yang diupload
3. Cek folder `public/storage/` adalah symlink ke `storage/app/public/`

### Error "No resource with given identifier found"?
Ini adalah warning browser yang bisa diabaikan, bukan error yang mempengaruhi upload.
