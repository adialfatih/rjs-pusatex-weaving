# 🧵 Sistem Monitoring & Manajemen Produksi Tekstil - PT. Rindang Jati

![GitHub repo size](https://img.shields.io/github/repo-size/username/rjs-pusatex-weaving)
![GitHub stars](https://img.shields.io/github/stars/username/rjs-pusatex-weaving)
![GitHub forks](https://img.shields.io/github/forks/username/rjs-pusatex-weaving)
![GitHub license](https://img.shields.io/github/license/username/rjs-pusatex-weaving)
![Last Commit](https://img.shields.io/github/last-commit/username/rjs-pusatex-weaving)

> *Gantilah `username/repo-name` sesuai dengan nama GitHub kamu, Bos.*

---

## 📘 Deskripsi

Aplikasi ini dikembangkan untuk PT. Rindang Jati sebagai solusi digital end-to-end dalam mengelola dan memonitor proses produksi tekstil. Mulai dari pembelian benang hingga distribusi kain jadi ke customer, semua proses tercatat dan termonitor secara **real-time** melalui aplikasi mobile berbasis **Progressive Web App (PWA)**.

---

## 🧭 Alur Proses Produksi

```mermaid
graph TD
  A[Pembelian Benang] --> B[Warping]
  B --> C[Sizing]
  C --> D[Weaving (Mesin AJL)]
  D --> E[Inspecting Kain]
  E --> F[Pengiriman ke PT. Pusatex]
  F --> G[Penerimaan Kain]
  G --> H[Washing / Putihan]
  H --> I[Final Inspecting]
  I --> J[Folding]
  J --> K[Gudang Kain]
  K --> L[Penjualan ke Customer]
  L --> M[Pengelolaan Utang Piutang]
****


🏗️ Arsitektur Sistem

graph LR
  A[Operator (Mobile PWA)] -->|Input Data Realtime| B[REST API Backend]
  B --> C[Database]
  D[Owner/Direksi (Web Dashboard)] -->|Monitoring| B


🚀 Fitur Utama
1. Manajemen Produksi: Warping, sizing, weaving, inspecting, washing, folding, hingga stok gudang.
2. Distribusi: Pengiriman ke PT. Pusatex dan manajemen proses penerimaan kain.
3. Monitoring Realtime: Semua data dapat dipantau langsung oleh owner dan direksi.
4. Progressive Web App: Bisa diakses dari perangkat mobile tanpa install aplikasi.
5. Transparansi Keuangan: Pencatatan penjualan dan pengelolaan utang-piutang secara terintegrasi.


# 1. Clone repository
git clone https://github.com/adialfatih/rjs-pusatex-weaving
cd repo-name

# 2. Install dependencies
npm install

# 3. Copy dan konfigurasi file environment
cp .env.example .env
# Edit .env sesuai konfigurasi lokal Anda

# 4. Jalankan server (contoh: Next.js atau Express)
npm run dev


📦 Teknologi yang Digunakan
Frontend: HTML 5 (PWA ready)
Backend: PHP / Codeigniter3
Database: MySQL
Deployment: VPS

Monitoring: Realtime logs & dashboards
