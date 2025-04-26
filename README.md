# RESTful API Ecommerce Sederhana

Proyek ini adalah implementasi RESTful API sederhana untuk aplikasi ecommerce menggunakan PHP dan MySQL. API ini mendukung fitur manajemen user, produk, kategori, cart, dan order.

## 🛠️ Setup

1. Clone repo atau copy semua file ke `htdocs`.
2. Buat database `ecommerce_db`.
3. Import struktur tabel: users, categories, products, shopping_cart, orders, order_items.
4. Pastikan koneksi database di `config/connect_db.php` sudah benar.

## Struktur Folder

- `cart/` — Endpoint untuk manajemen keranjang belanja (add, get, update, delete cart item)
- `categories/` — Endpoint untuk CRUD kategori produk
- `config/` — Konfigurasi koneksi database
- `orders/` — Endpoint untuk membuat dan melihat order
- `produk/` — Endpoint untuk CRUD produk
- `users/` — Endpoint untuk CRUD user

## Kebutuhan Sistem
- PHP 7.x atau lebih baru
- MySQL
- Web server (disarankan: XAMPP/LAMPP)

## Konfigurasi Database
Edit file `config/connect_db.php` jika diperlukan:
```php
define('HOST', 'localhost');
define('USER', 'root');
define('DB','ecommerce_db');
define('PASS','');
```

Buat database dan tabel sesuai kebutuhan (tabel: users, products, categories, shopping_cart, orders, order_items).

## Contoh Penggunaan Endpoint

### User
- `POST /users/create_user.php` — Register user baru
  - Body: `{ "username": "user1", "password": "pass", "email": "mail@mail.com" }`

### Produk
- `POST /produk/create_product.php` — Tambah produk baru
  - Body: `{ "name": "Produk A", "description": "desc", "price": 10000, "stock": 10, "category_id": 1 }`

### Cart
- `POST /cart/api_add_to_cart.php` — Tambah produk ke cart
  - Body: `{ "user_id": 1, "product_id": 2, "quantity": 3 }`

### Order
- `POST /orders/create_order.php` — Buat order dari cart user
  - Body: `{ "user_id": 1 }`

## Menjalankan
1. Pastikan XAMPP/LAMPP aktif dan database sudah dibuat.
2. Letakkan folder `e-commerce_kelompok5` di dalam `htdocs` (untuk XAMPP).
3. Akses endpoint melalui Postman/cURL sesuai kebutuhan.

## Catatan
- Semua endpoint menerima dan mengembalikan data dalam format JSON.
- Pastikan struktur tabel database sesuai dengan kebutuhan API.

---
### Contoh:
- Products bertindak sebagai Provider saat Cart mengambil harga produk.
- Cart bertindak sebagai Consumer saat mengambil `product_id` untuk ditambahkan ke keranjang.
- Orders bertindak sebagai Provider saat User ingin melihat daftar pesanan.
- Orders juga sebagai Consumer saat mengambil isi Cart untuk membuat order baru.

Dibuat untuk keperluan tugas UTS IAE. 