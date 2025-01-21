# INTERAKSI : Sistem Manajemen Kerja Tim Kehumasan dan Protokoler BPS Provinsi Kepulauan Riau (Teman Humas)

### Proyek RPL Kelompok 1 Kelas 3SD2

**Disusun Oleh:** 
- Amir Mumtaz Siregar (222212493) 
- Arih Rahmawati (222212512) 
- Azizah Najla (222212531) 
- Meuthia Nazhifah Muthmainnah (222212729) 
- Muh. Alfian Amnur (222212736) 
- Nazlya Rahma Susanto (222212787) 
- Sabastian Alfons Bahy (222212865) 
- Yoga Regita Hamzah Ashari (222212924)

## Website : <https://temanhumas.xath.site>

Sistem Manajemen Kerja Tim Kehumasan dan Protokoler BPS Provinsi
Kepulauan Riau (Teman Humas) merupakan sistem berbasis web yang dapat
membantu Tim Kehumasan dan Protokoler BPS Provinsi Kepulauan Riau dalam
mengelola tugas, meningkatkan kolaborasi, dan menyediakan fasilitas
pengingat serta pelaporan kinerja. Web ini dirancang untuk menyelesaikan
permasalahan bisnis yang ada pada Tim Kehumasan dan Protokoler BPS
Provinsi Kepulauan Riau, yaitu sulitnya memantau progres kegiatan, dan
kurangnya dokumentasi. Aplikasi ini dibuat dengan tujuan untuk memenuhi
tugas mata kuliah Rekayasa Perangkat Lunak di Politeknik Statistika
STIS.

## 📁 Struktur Proyek

``` tree
.
├───app
│   ├───Console
│   │   └───Commands    # Perintah artisan kustom untuk menjalankan tugas otomatis.
│   ├───Events          # Event aplikasi untuk pola event-driven.
│   ├───Exceptions      # Kelas untuk menangani error dan exception.
│   ├───Exports         # File untuk fitur ekspor data seperti laporan proyek.
│   ├───Filament        # Berisi konfigurasi dashboard admin menggunakan Filament.
│   │   ├───Pages           # Halaman khusus di dashboard Filament.
│   │   ├───Resources       # Mengelola resource entitas (proyek, tiket, pengguna, dll).
│   │   │   ├───GuidebookResource      # Resource untuk panduan proyek.
│   │   │   ├───PermissionResource     # Resource untuk manajemen izin.
│   │   │   ├───ProjectResource        # Resource untuk manajemen proyek.
│   │   │   │   ├───Pages                  # Halaman terkait proyek.
│   │   │   │   └───RelationManagers       # Manajemen hubungan antar resource proyek.
│   │   │   ├───RoleResource          # Resource untuk manajemen peran.
│   │   │   ├───TicketResource        # Resource untuk manajemen tiket tugas.
│   │   │   ├───UserResource          # Resource untuk data pengguna.
│   │   └───Widgets           # Widget seperti visualisasi data (e.g., timesheet).
│   ├───Helpers         # Fungsi atau utilitas kustom aplikasi.
│   ├───Http            # Logika utama aplikasi.
│   │   ├───Controllers     # Mengatur request dan response.
│   │   │   ├───Auth            # Logika autentikasi pengguna.
│   │   │   └───RoadMap         # Logika untuk membuat roadmap proyek.
│   │   ├───Livewire        # Komponen Livewire untuk fitur dinamis.
│   │   │   ├───Ticket          # Fitur manajemen tiket.
│   │   │   └───Timesheet       # Fitur manajemen timesheet.
│   │   └───Middleware      # Filter request sebelum masuk ke controller.
│   ├───Jobs             # Tugas asinkron seperti pengiriman reminder.
│   ├───Listeners        # Listener event untuk aksi otomatis.
│   ├───Models           # Model Eloquent untuk interaksi database.
│   ├───Notifications    # Notifikasi melalui email atau kanal lainnya.
│   ├───Policies         # Aturan otorisasi untuk akses data.
│   ├───Providers        # Konfigurasi dependency dan service aplikasi.
│   ├───Services         # Logika layanan yang terpisah dari controller.
│   ├───Settings         # Konfigurasi untuk pengaturan aplikasi.
│   └───View
│       └───Components    # Komponen blade kustom untuk tampilan.
├───bootstrap
│   └───cache         # Cache aplikasi untuk mempercepat proses.
├───config            # File konfigurasi aplikasi (database, queue, dll).
├───database
│   ├───factories     # Membuat data dummy untuk pengujian.
│   ├───migrations    # File untuk membuat atau mengubah tabel database.
│   ├───seeders       # Skrip untuk mengisi data awal ke database.
│   └───settings      # Data pengaturan aplikasi dalam database.
├───docs
│   └───_media        # Media pendukung untuk dokumentasi.
├───lang
│   ├───en            # File terjemahan bahasa Inggris.
│   └───id            # File terjemahan bahasa Indonesia.
├───public
│   ├───css           # File CSS untuk tampilan.
│   ├───img           # Gambar aplikasi.
│   └───js            # File JavaScript untuk fitur dinamis.
├───resources
│   ├───css           # Sumber daya CSS.
│   ├───js            # Sumber daya JavaScript.
│   └───views         # Template blade untuk tampilan aplikasi.
│       ├───components    # Komponen kecil tampilan.
│       ├───filament      # Template untuk halaman Filament.
│       │   ├───pages         # Halaman Filament.
│       │   └───resources     # Resource tambahan Filament.
│       │       └───tickets      # Template terkait tiket.
│       ├───livewire       # Template Livewire untuk fitur dinamis.
│       │   ├───ticket         # Template untuk tiket.
│       │   └───timesheet      # Template untuk timesheet.
│       ├───partials       # Bagian kecil tampilan seperti header/footer.
│       └───vendor         # Komponen dari pihak ketiga (Filament Breezy, dll).
├───routes            # Rute aplikasi, seperti web.php dan api.php.
├───storage
│   ├───app
│   │   └───public    # File unggahan pengguna.
│   ├───framework     # Data sementara seperti sesi dan cache.
│   └───logs          # File log aktivitas aplikasi.
├───tests
│   ├───Feature       # Pengujian fitur aplikasi.
│   └───Unit          # Pengujian unit untuk logika spesifik.
└───vendor            # Dependensi dari Composer.
```

## 📃 Panduan Installasi Lokal

> ### Persyaratan
>
> -   [Composer](https://getcomposer.org/).
> -   PHP 8.1+ dan MySQL atau Laragon versi 8.1+
> -   NPM
>
> ### Instalasi
>
> -   **Langkah 1**
>
>     Kloning repositori ke localhost menggunakan perintah berikut:
>
>     ``` shell
>     git clone https://github.com/devaslanphp/project-management.git
>     ```
>
> -   **Langkah 2**
>
>     Jalankan perintah berikut untuk menginstal Back Dependencies dan
>     Front Dependencies
>
>     ``` shell
>     composer install
>     npm install
>     ```
>
> -   **Langkah 3**
>
>     Salin file `.env.example` ke `.env` dan konfigurasi parameter di
>     file `.env`
>
> -   **Langkah 4**
>
>     Pada langkah ini akan dilakukan instalasi dan konfigurasi
>     database.
>
>     1.  Buat Database pada server yang sama
>
>     2.  Jalankan Migrasi Database
>
>     ``` shell
>     php artisan migrate
>     ```
>
>     3.  Jalankan database seeder, untuk memasukkan default user,
>         referentials and permissions yang digunakan oleh platform
>
>     ``` shell
>     php artisan db:seed
>     ```
>
>     ``` shell
>     composer update
>     composer install
>     ```
>
>     Kredensial pengguna default yang dibuat oleh seeder adalah:
>
>     -   **Email**:
>         [temanhumas@gmail.com](mailto:temanhumas@gmail.com)
>
>     -   **Password**: temanhumas01*
>
> -   **Langkah 5**
>
>     Langkah terakhir sebelum dapat menyajikan proyek adalah
>     menjalankan perintah berikut untuk menghasilkan *vite assets*:
>
>     Generate assets for production:
>
>     ``` shell
>     npm run build
>     ```
>
> -   **Langkah 6**
>
>     Untuk menjalankan platform, gunakan perintah berikut:
>
>     ``` shell
>     php artisan serve`
>     ```

## Kesimpulan

Aplikasi web **TEMAN HUMAS** dirancang untuk membantu Tim Kehumasan dan
Protokoler BPS Provinsi Kepulauan Riau dalam mengelola tugas,
meningkatkan kolaborasi, serta menyediakan fasilitas pengingat dan
pelaporan kinerja secara efisien. Sistem ini dikembangkan sebagai solusi
terhadap permasalahan seperti sulitnya memantau progres kegiatan dan
kurangnya dokumentasi kerja tim.

Proses instalasi mencakup beberapa langkah penting, seperti konfigurasi
lingkungan, instalasi dependensi, migrasi database, dan pengaturan
server. Dengan adanya panduan yang jelas, aplikasi ini dapat dengan
mudah diimplementasikan di lingkungan pengembangan maupun produksi.

Selain itu, proyek ini juga dirancang agar dapat dikembangkan lebih
lanjut sesuai kebutuhan instansi. Implementasi aplikasi ini diharapkan
mampu memberikan dampak positif dalam meningkatkan efisiensi kerja tim
dan pengelolaan data di lingkungan BPS Provinsi Kepulauan Riau, serta
menjadi contoh praktis dalam penerapan mata kuliah Rekayasa Perangkat
Lunak.

Proyek ini tidak hanya bermanfaat bagi tim pengguna tetapi juga dapat
menjadi acuan untuk pengembangan sistem serupa di instansi lain. Dengan
fleksibilitasnya, sistem ini dapat disesuaikan untuk memenuhi kebutuhan
khusus organisasi, memastikan operasional yang lebih efektif dan
terorganisir.
