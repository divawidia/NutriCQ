# NutriCQ - Backend Capstone Project

NutriCQ merupakan aplikasi yang dapat membantu
pengguna dalam mengontrol dan melacak asupan gizi 
mereka, serta membantu pengguna dalam membantu menjawab 
pertanyaan atau keluhan mereka terkait dengan gizi dengan 
mengadakan konsultasi langsung dengan dokter gizi. Dengan itu
maka pengguna akan lebih teredukasi serta lebih peduli dengan
kecukupan gizi mereka.

Dibuatnya aplikasi NutriCQ ini datang dari suatu permasalahan, 
dimana berdasarkan studi pada tahun 2014 yang dilakukan Kementerian Kesehatan,
pola diet yang tidak sehat dikarenakan tidak terpenuhinya gizi seimbang masih 
menjadi masalah di Indonesia.  Merujuk pada studi tersebut diketahui juga perbandingan 
konsumsi protein nabati dan hewani masih tidak berimbang sehingga mempengaruhi kualitas makanan masyarakat.

## Fitur Aplikasi
Aplikasi ini memiliki 3 sisi pengguna, yaitu user, admin, dan dokter. Berikut ini merupakan fitur-fitur dari aplikasi NutriCQ:
- Menghitung gizi dari suatu makanan
- Mencatat kebutuhan gizi dari pengguna
- Mencatat semua asupan gizi yang diinputkan oleh user
- Pengguna bisa berkonsultasi dengan dokter gizi
- Pengguna bisa membuat booking konsultasi langsung dengan dokter gizi
- Dokter bisa menyetujui booking dari pengguna dan melayani konsultasi
- Pengguna bisa mengubah kebutuhan gizi perharinya sesuai yg diinginkan
- Admin bisa menambahkan, mengubah, dan menghapus data makanan
- Admin bisa menyetujui calon dokter yang mendaftarkan diri di aplikasi ini

## Tech Stack
* PHP version: `7.4`
* Laravel version: `8`
* Database: `MySql`
* Laratrust version: `7.1`
* Sanctum auth version: `2.11`
* PHPUnit version: `9.5`

## How to Run

Jika ingin menjalankan aplikasi ini di local bisa dengan mengikuti step berikut:
1. Clone repositori ini
 ```
git clone https://github.com/divawidia/capstone-project.git
```
2. Update modul yang ada di composer 
```
composer update
```
3. Rename file `.env.example` menjadi `.env`
4. Buat database baru pada MySql sesuai dengan nama database di `DB_DATABASE` yang ada di file `.env`
5. Jalankan migrasi dan seeder database 
```
php artisan migrate --seed
```
6. Import data makanan yang ada di file `foodData.sql` pada database yang telah dibuat
7. Set app key
```
php artisan key:generate
```
8. Jalankan project
```
php artisan serve
```
9. navigate to `http://127.0.0.1:8000/api/`

## API Documentation
Dokumentasi API dari aplikasi NutriCQ bisa dilihat pada collection Postman berikut ini:
