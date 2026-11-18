# Portal Informasi Perusahaan & CRUD REST API

Repository ini berisi gabungan **Project 1 (Frontend & Web Dinamis PHP + Bootstrap 5)** dan **Project 2 (Backend CRUD REST API)** yang terhubung dengan database MySQL.

- **Nama:** Salahudin Yusuf
- **NPM:** 24552011349
- **Kelas:** TIF RP 24B CID

---

## Struktur Folder
- `/api` : Endpoint REST API (`koneksi.php` & `informasi.php`) untuk operasi CRUD.
- `/includes` : Komponen modular (`header.php`, `footer.php`, `data_dummy.php`).
- `/screenshots` : Dokumentasi hasil tampilan web dan pengujian API menggunakan Bruno.
- `db_perusahaan.sql` : File struktur dan data awal database MySQL.

---

Akun admin
Username: admin
Password: 12345

## Dokumentasi Project 1 (Tampilan Website)

### 1. Halaman Utama (Beranda)
![Halaman Utama](screenshots/web-beranda.png)

### 2. Halaman Detail Informasi
![Halaman Detail](screenshots/web-detail.png)

### 3. Halaman Login & Registrasi
![Halaman Login](screenshots/web-login.png)
![Halaman Registrasi](screenshots/web-register.png)

### 4. Halaman Menu Utama & Dashboard Admin (CRUD)
![Menu Utama](screenshots/web-menu-utama.png)
![Dashboard Admin](screenshots/web-dashboard.png)

---

## Dokumentasi Project 2 (Pengujian CRUD API dengan Bruno)

Endpoint utama: `http://localhost/web-perusahaan/api/informasi.php`

### 1. READ — Menampilkan Data (`GET`)
![Uji GET Bruno](screenshots/bruno-get.png)

### 2. CREATE — Menambah Data Baru (`POST`)
![Uji POST Bruno](screenshots/bruno-post.png)

### 3. UPDATE — Mengubah Data (`PUT`)
![Uji PUT Bruno](screenshots/bruno-put.png)

### 4. DELETE — Menghapus Data (`DELETE`)
![Uji DELETE Bruno](screenshots/bruno-delete.png)