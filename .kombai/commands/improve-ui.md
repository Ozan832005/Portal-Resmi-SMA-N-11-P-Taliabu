---
name: improve-ui
description: Audit dan modernisasi UI SMA11TALIABU (backend admin, frontend publik, modul) memakai lapisan desain km- yang sudah ada, sekaligus membereskan masalah fungsional ringan pada tampilan (form, route, aset, JS widget).
---

# Perapian UI Aplikasi

Tujuan: menjadikan seluruh tampilan aplikasi konsisten, rapi, dan terasa modern tanpa merombak arsitektur halaman, sekaligus membereskan gangguan kecil yang membuat halaman terasa "rusak" (form tidak bisa submit, tombol mati, ikon tidak muncul, tabel tidak rapi).

Prinsip kerja:
1. **Konsistensi di atas variasi.** Semua halaman mengikuti satu bahasa desain yang sudah ada (lihat bagian "Sumber kebenaran desain"), bukan selera baru per halaman.
2. **Perbaiki, jangan rombak.** Struktur dan alur halaman dipertahankan. Yang diubah: hierarki, tipografi, spasi, state (kosong/aktif/error/loading), dan kelengkapan komponen.
3. **Sedikit tapi tuntas.** Lebih baik 6 halaman benar-benar rapi dan terverifikasi daripada 30 halaman setengah jadi.
4. **Domain dulu, dekorasi kemudian.** Setiap halaman harus menjawab: apa objeknya, berapa banyak, apa aksi utamanya, apa yang terjadi kalau kosong.

## Input

Argumen opsional setelah `/improve_ui`. Tanpa argumen = audit seluruh aplikasi dengan urutan prioritas di bawah.

| Argumen | Cakupan |
|---|---|
| *(kosong)* | Seluruh aplikasi, urutan prioritas: dashboard per role → CRUD inti index → create/edit → auth & profil → frontend publik → modul |
| `backend` | `resources/views/backend/**`, `resources/views/layouts/backend/**` |
| `frontend` | `resources/views/frontend/**`, `resources/views/layouts/Frontend/**` |
| `modul` atau nama modul (`perpustakaan`, `ppdb`, `spp`, `murid`, `backup`) | `Modules/<Nama>/Resources/views/**` |
| path folder atau file view | Hanya path tersebut, mis. `/improve_ui resources/views/backend/pengguna/murid` |
| nama route atau URL | Halaman yang bisa dicapai route tersebut |

Jika argumen ambigu (mis. hanya "halaman murid" padahal ada `backend/pengguna/murid` dan `Modules/Murid`), tanyakan satu kali lalu lanjut.

## Sumber kebenaran desain (baca sebelum mengubah apa pun)

| Berkas | Gunanya |
|---|---|
| `public/Assets/Backend/css/modern-ui.css` | Token `--km-*` (warna, radius, font) + kelas `km-*`. **Ini satu-satunya tempat menambah gaya backend baru.** |
| `resources/views/backend/website/content/event/index.blade.php` | Contoh baku halaman daftar: page head, panel, tabel, status domain, empty state, aksi baris. |
| `resources/views/backend/website/content/berita/index.blade.php`, `resources/views/backend/website/home.blade.php` | Contoh baku halaman daftar dan dashboard. |
| `resources/views/backend/website/partials/agenda.blade.php` | Partial agenda bersama; dipakai dashboard Admin, Guru/Staf, dan Murid. Pakai ulang, jangan menggandakan markup acara. |
| `resources/views/layouts/backend/{app,style,scripts,menu,header}.blade.php` | Kerangka admin: template Bootstrap 4 + feather icon + DataTables/Select2/flatpickr. |
| `resources/views/layouts/Frontend/{app,style,scripts}.blade.php` + `public/Assets/Frontend/style.css` | Kerangka situs publik (template HTML sekolah). Layar baru untuk frontend masuk ke `public/Assets/Frontend/css/modern.css` yang dimuat paling akhir. |
| `routes/web.php`, `Modules/*/Routes/web.php` | Daftar route & nama route; semua tautan di Blade wajib `route()`. |
| Skill `laravel-module-dashboard-conventions` | Aturan modul: provider, view alias `alias::path`, menu sidebar, migrasi. |

Jalankan inventaris dulu agar keputusan berbasis data, bukan tebakan:

```powershell
rg --files -g '*.blade.php' resources/views Modules/*/Resources/views
rg -l 'km-page' --glob '*.blade.php'
rg -l 'content-wrapper container-xxl' --glob '*.blade.php'
php artisan route:list --except-vendor
```

File yang cocok pola pertama tapi tidak cocok `km-page` = backlog halaman yang belum ikut sistem desain. Itu daftar kerja utama.

## Langkah kerja

1. **Tentukan cakupan**, lalu tulis daftar berkas yang akan disentuh (maksimal 8 berkas per giliran, lalu laporkan progres).
2. **Jalankan inventaris** dan catat tiga angka: total view, view sudah pakai `km-*`, view masih markup template lama.
3. **Audit fungsional ringan** (bagian berikut) untuk berkas dalam cakupan. Perbaiki yang jelas rusak dan berbiaya rendah. Jangan menyentuh controller, model, atau logika bisnis kecuali kerusakannya memang di sana dan perbaikannya terbatas.
4. **Audit visual** per jenis halaman memakai checklist di bawah, catat temuan sebagai daftar singkat (berkas + masalah + perbaikan).
5. **Implementasi backend**: ubah markup Blade ke kosakata `km-*`, tambah kelas/token baru **hanya** di `modern-ui.css` (blok komentar terpisah per komponen). Jangan menaruh warna hardcoded di Blade.
6. **Implementasi frontend**: buat/perluas `public/Assets/Frontend/css/modern.css` dengan token `--fe-*` dan muat setelah `style.css`. Jangan menghapus kelas/ID yang dipakai JavaScript template (Owl Carousel, nivo slider, meanmenu, preloader).
7. **Implementasi modul**: ikuti skill modul. View backend modul tetap `@extends('layouts.backend.app')` dan memakai kelas `km-*` yang sama; tidak ada CSS khusus per modul. View publik PPDB cukup mewarisi lapisan `modern.css` lewat `ppdb::layouts.master`.
8. **Tailwind**: hanya untuk halaman/komponen baru yang memang dilayani Vite (`resources/css/app.css`), bukan halaman template admin/frontend yang memuat CSS dari `public/Assets`. Jangan mencampur Tailwind ke halaman template.
9. **Verifikasi** dengan daftar perintah di bawah, lalu buka halaman yang berubah di browser dan periksa pada lebar 1440, 1024, dan 390 px.
10. **Laporkan** memakai format laporan di bagian akhir.

## Audit fungsional ringan (boleh langsung diperbaiki)

| Gejala | Cara cek | Tindakan |
|---|---|---|
| Aset 404 | Bandingkan setiap `asset('...')` dengan keberadaan berkas di `public/`. Jalankan pemeriksa: `Select-String -Path <layout file> -Pattern "asset\('([^']+)'\)" -AllMatches` lalu `Test-Path "public/<path>"` untuk tiap hasil. Contoh yang pernah ada: `Assets/Backend/css/style.css` dan `Assets/Backend/css-rtl/pages/page-blog.css`. | Hapus referensi yang berkasnya tidak ada, atau buat berkasnya. Jangan biarkan tautan 404. |
| Halaman blank / error view | `php artisan view:clear`, lalu buka route-nya. | Perbaiki `@include`/`@extends` yang menunjuk view tidak ada (modul wajib pakai `alias::`). |
| `Route [x] not defined` | Cocokkan setiap `route('...')` di Blade dengan `php artisan route:list`. | Ganti ke nama route yang benar; jangan menulis URL literal. |
| Form tidak menyimpan | Tiap `<form method="post">` punya `@csrf`; update pakai `@method('PUT')`/`@method('PATCH')`; delete pakai `@method('DELETE')`. | Lengkapi direktif yang hilang. |
| Validasi tidak terlihat | Tiap input punya `@error('field') ... @enderror` dan kelas `is-invalid`. | Tambahkan blok error di bawah field. |
| Widget mati (Select2, flatpickr, DataTables) | Elemen butuh class/id yang dicari skrip di `public/Assets/Backend/js/scripts/**` (mis. `.select2`, `.dt-responsive`, `#dp-*`). | Samakan selektor, atau hapus kelas widget bila memang tidak diperlukan. |
| Tabel `.dt-responsive` kehilangan kolom pertama atau panah urut dobel | Init bersama ada di `public/Assets/Backend/js/scripts/tables/table-datatables-advanced.js`: kolom 0 diperlakukan sebagai kolom kontrol dan kolom terakhir dirender sebagai badge status. | Kolom kontrol hanya untuk tabel yang `<th>` pertamanya kosong; jangan paksakan `responsive` pada tabel yang sudah dibungkus `.table-responsive`; sisakan satu indikator urut lewat CSS. |
| Gambar unggahan tidak tampil | `php artisan storage:link` sudah jalan? Path `asset('storage/...')` cocok dengan `storeAs('public/...')`? | Perbaiki path; jalankan `storage:link` bila belum. |
| Tombol tema (bulan) tidak berefek | Toggle ada di `layouts/backend/header.blade.php`, tetapi token `--km-*` tidak punya varian gelap. | Buat override `--km-*` pada `body.dark-layout` di `modern-ui.css` (template sudah memuat `themes/dark-layout.css`). Jangan biarkan tombol mati. |
| Teks terpotong / overflow | Cek tabel panjang dan judul panjang pada 390 px. | `text-truncate` pada sel tertentu, `table-responsive` untuk tabel, atau susun ulang aksi baris. |
| Aksesibilitas dasar | Input tanpa `<label for>`, tombol ikon tanpa `aria-label`, kontras teks di bawah 4.5:1. | Lengkapi label/aria; pakai `--km-text-soft` untuk teks sekunder, bukan `--km-text-faint` untuk isi penting. |

Batas: perbaikan fungsional maksimal menyentuh view, aset, dan CSS. Jika akar masalah ada di controller/route/migrasi, catat sebagai temuan + usulan, jangan ubah tanpa persetujuan.

## Audit visual (checklist per jenis halaman)

**Halaman daftar/index** — kerangka: `.km-page` → `.km-page-head` (judul, subjudul berisi jumlah data + konteks domain, satu aksi utama) → `.km-panel` → tabel `dt-responsive`.
- Kolom aksi rata kanan, dibungkus `.km-row-actions`, tombol `btn-sm`; hapus destruktif memakai `.km-btn-danger` + konfirmasi.
- Status memakai `.km-status--live` / `.km-status--draft`, bukan badge generik atau emoji.
- Gambar kecil memakai `.km-thumb`; tanpa gambar memakai `.km-thumb--empty` dengan teks, bukan kotak kosong.
- Data kosong memakai `.km-empty` berisi penjelasan + aksi, bukan tabel kosong.
- Kolom tanggal dan angka memakai `font-variant-numeric: tabular-nums` (sudah ada di token), format tanggal Indonesia (`translatedFormat`).
- Data mentah yang tak berguna bagi pengguna (id internal, flag `is_active` mentah) diganti label yang bisa dibaca.

**Halaman form create/edit** — kerangka: `.km-page` → `.km-page-head` → satu atau dua `.km-panel`.
- Susun dua kolom: kolom utama (field inti) dan kolom samping (media, status, meta). Di bawah 992 px menjadi satu kolom.
- Pratinjau unggahan memakai `.km-form-preview`; tampilkan nama berkas lama dan akibatnya bila diganti.
- Field wajib ditandai jelas; teks bantuan ditaruh sebagai hint, bukan placeholder panjang.
- Aksi: tombol simpan (primary) + batal (outline) di akhir form, tidak mengambang.
- Form panjang dikelompokkan dengan judul kelompok (`.km-panel-title`), bukan satu tumpukan field.

**Dashboard per role** — kerangka: `.km-page` → sapaan singkat bernada kerja (bukan "Have fun your day") → `.km-figures` untuk angka kunci → panel agenda/aktivitas.
- Angka yang tidak bisa diklik jangan diberi gaya tautan; angka yang punya halaman tujuan dijadikan `a.km-figure`.
- Hilangkan ilustrasi template yang tidak berkaitan dengan domain (medali, dekorasi) atau ganti dengan ringkasan data nyata.
- Setiap role (`Admin`, `Guru`, `Staf`, `Murid`, `Orang Tua`, `Perpustakaan`, `PPDB`, `Bendahara`) memakai komponen yang sama dengan angka yang relevan bagi role tersebut.

**Auth & profil** — satu kartu terpusat, maksimal 420 px, logo sekolah, label jelas, pesan error di bawah field, tanpa ilustrasi besar.

**Sidebar & navigasi** (`layouts/backend/menu.blade.php`, `layouts/backend/header.blade.php`) — tanpa merombak struktur `nav-item`/`menu-content` yang dibaca `Assets/Backend/js/core/app-menu.js`:
- Satu kosakata untuk semua peran: label Bahasa Indonesia dengan huruf kalimat. Tidak boleh campur bahasa seperti "Books", "Category", "Members", "Publisher".
- Susunan tetap: Dashboard sebagai baris pertama tanpa grup → paling banyak tiga penanda bagian (`<li class="km-menu-section">` + `<span class="km-menu-section-label">`) → baris "Profil saya" di bagian Akun.
- Peran hanya menyaring item, tidak menukar posisi atau mengganti label item yang sama.
- Grup induk wajib diberi `open sidebar-group-active` bila salah satu anaknya aktif (template tidak membuka grup lewat `closest()` untuk anak), sedangkan anak aktif cukup `active`.
- Ikon feather hanya di tingkat atas dan harus bermakna isinya (bukan `credit-card` untuk data murid). Anak menu tidak memakai ikon; titik penanda dibuat CSS.
- Hapus item tanpa halaman tujuan (`href=""`, `#` tanpa anak) dan item yang route-nya tidak boleh diakses peran tersebut — cek middleware `role:` di `Modules/*/Routes/web.php`.
- Semua tautan memakai `route()`; bila route tidak bernama, pakai `url()` dan status aktif `request()->is(...)`.
- State aktif memakai rel 2px brand di gutter, bukan pil biru penuh. Sidebar tetap panel ink; jangan menambah kartu promo, kartu profil, atau tombol di kaki sidebar.
- Header: tombol tema wajib punya `aria-label` dan benar-benar mengubah token warna (`html.dark-layout` di `modern-ui.css`), bukan tombol mati.

**Frontend publik** — pertahankan grid template, perbaiki: hierarki hero (judul, tanggal, aksi), kartu berita/agenda (gambar rasio tetap, judul 2 baris, tanggal + kategori sebagai metadata), daftar guru (foto seragam), footer (kontak + tautan cepat). Jangan menambah slider baru atau animasi berat.

**Umum** — satu jenis pemisah per permukaan: garis tipis, tanpa bayangan; bayangan hanya untuk elemen mengambang (dropdown, modal). Tidak ada gradien dekoratif, glassmorphism, atau badge bertumpuk. Tipografi memakai token font yang ada, bobot 600–700 untuk judul, lebar baca wajar.

## Batasan

- Jangan menambah dependensi PHP/JS, dan jangan mengubah `composer.json`/`package.json` tanpa persetujuan.
- Jangan mengubah nama route, struktur database, atau migrasi. Dilarang `migrate:fresh` dan `db:wipe`.
- Jangan menyalin kelas atau view modul ke `app/`/`resources/views` untuk "memperbaiki" tampilan.
- Jangan menyentuh `vendor/` dan berkas template pihak ketiga di `public/Assets/Backend/vendors/**`.
- Jangan menaruh hex warna baru di Blade; wajib lewat token di CSS layer.
- Ikon memakai feather yang sudah tersedia (`data-feather`), tanpa pustaka ikon baru dan tanpa emoji di area admin.
- Mikrokopi Bahasa Indonesia, kalimat biasa, tanpa pemisah "·" sebagai pengganti ikon atau label.
- Kepatuhan kontras minimal 4.5:1 untuk teks isi; state fokus wajib terlihat pada input, tautan, dan tombol.
- Jangan membuat berkas dokumentasi baru di dalam repo.

## Verifikasi

```powershell
php artisan view:clear
php artisan route:list --except-vendor
php artisan module:list                 # hanya bila menyentuh Modules/*
vendor/bin/pint --dirty --format agent  # hanya bila menyentuh berkas PHP
npm run build                           # hanya bila mengubah resources/css, resources/js, atau @vite
php artisan test --compact              # hanya bila menyentuh controller/route
```

Lalu periksa visual di browser: minimal satu halaman daftar, satu form, dashboard role yang terdampak, dan (bila relevan) satu halaman publik, pada lebar 1440/1024/390 px. Sebutkan URL yang diperiksa di laporan.

## Format laporan

Tutup pekerjaan dengan bagian berikut, ringkas dan berbasis berkas:

1. **Ringkasan** — apa yang berubah dan dampaknya bagi pengguna (3–5 kalimat).
2. **Perubahan UI** — tabel: berkas → masalah → perbaikan → kelas/token yang dipakai.
3. **Perbaikan fungsional** — tabel: gejala → penyebab → perbaikan.
4. **Token/kelas baru** — nama, alasan, di mana ditambahkan.
5. **Belum dikerjakan** — temuan di luar cakupan atau berisiko, beserta alasan dan usulan.
6. **Verifikasi** — perintah yang dijalankan + hasil, URL yang diperiksa.

## Definisi selesai

- [ ] Semua berkas dalam cakupan tidak lagi memakai markup template lama tanpa alasan; memakai kosakata `km-*` (backend) atau lapisan `modern.css` (frontend).
- [ ] Tidak ada aset 404, tautan route mati, form tanpa `@csrf`, atau input tanpa tampilan error pada berkas yang disentuh.
- [ ] Setiap halaman yang disentuh punya state kosong, state error, dan tampilan responsif yang wajar.
- [ ] Sidebar memakai satu bahasa, tanpa tautan mati, grup induk terbuka saat halaman aktif, dan ikon hanya pada tingkat atas.
- [ ] Tombol tema mengubah token gelap (`html.dark-layout`), bukan tombol tanpa efek.
- [ ] Tidak ada warna hardcoded baru di Blade; semua lewat token.
- [ ] Verifikasi di atas dijalankan dan dilaporkan, termasuk halaman yang dilihat di browser.
