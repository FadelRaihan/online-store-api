# Online Store API

Online Store API adalah REST API sederhana yang dibuat menggunakan Laravel 10 untuk mengelola data produk dan pemesanan. API ini menerapkan validasi request, database transaction, serta manajemen stok agar data tetap konsisten.

## Teknologi

- Laravel 10
- PHP 8.1+
- MySQL
- Composer
- Postman

## Instalasi

Clone repository

```bash
git clone https://github.com/FadelRaihan/online-store-api.git
```

Masuk ke folder project

```bash
cd online-store-api
```

Install dependency

```bash
composer install
```

Salin file environment

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Sesuaikan konfigurasi database pada file `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=online_store
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migration

```bash
php artisan migrate
```

Jalankan aplikasi

```bash
php artisan serve
```

API dapat diakses melalui:

```
http://127.0.0.1:8000/api
```

## Endpoint

### Product

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | /api/products | Menampilkan seluruh produk |
| POST | /api/products | Menambahkan produk |
| GET | /api/products/{id} | Menampilkan detail produk |
| PUT | /api/products/{id} | Mengubah data produk |
| DELETE | /api/products/{id} | Menghapus produk |

### Order

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | /api/orders | Menampilkan seluruh order |
| POST | /api/orders | Membuat order |
| GET | /api/orders/{id} | Menampilkan detail order |

## Contoh Request

Membuat produk

```json
{
    "name": "Gaming Mouse Pro",
    "price": 300000,
    "stock": 20
}
```

Membuat order

```json
{
    "items": [
        {
            "product_id": 1,
            "quantity": 2
        }
    ]
}
```

## Fitur

- CRUD Product
- Create Order
- Validasi menggunakan Form Request
- Database Transaction
- Perhitungan total harga secara otomatis
- Pengurangan stok secara otomatis
- Rollback transaksi ketika stok tidak mencukupi
- Relasi Eloquent antar tabel

## Struktur Database

Terdapat tiga tabel utama:

- products
- orders
- order_items

Relasi:

- Satu order memiliki banyak order item.
- Satu produk dapat dimiliki oleh banyak order item.
- Order item menghubungkan order dengan product.

## Pengujian

Beberapa pengujian yang telah dilakukan:

- Menambahkan produk
- Mengubah produk
- Menghapus produk
- Membuat order
- Mengurangi stok setelah order berhasil
- Menolak order ketika stok tidak mencukupi
- Memastikan rollback berjalan saat transaksi gagal

## Author

Fadel Raihan

GitHub:
https://github.com/FadelRaihan

LinkedIn:
https://www.linkedin.com/in/fadel-raihan-asshiddiqie-a42389312