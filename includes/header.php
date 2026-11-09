<?php
// Mendapatkan nama file halaman yang sedang dibuka (contoh: jasa.php, portofolio.php, dll)
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahira Printing</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'brand-yellow': '#FFD028',
              'brand-bg': '#F3F0E6',
            }
          }
        }
      }
    </script>
</head>
<body class="bg-[#F3F0E6] min-h-screen flex flex-col font-sans">

<!-- Header / Navbar Hitam sesuai Figma -->
<header class="bg-black text-white py-4 px-6 md:px-12 border-b border-gray-800">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <!-- Logo Image Mahira Printing -->
        <a href="jasa.php" class="flex items-center">
            <img src="../assets/images/logo.JPEG" alt="Mahira Printing" class="h-8 md:h-10 object-contain">
        </a>

        <!-- Menu Navigasi Dinamis -->
        <nav class="hidden md:flex items-center gap-8 text-xs font-semibold">
            <a href="dashboard.php" class="<?= ($current_page == 'index.php') ? 'text-brand-yellow font-bold border-b-2 border-brand-yellow pb-1' : 'text-gray-300 hover:text-white transition' ?>">Beranda</a>
            
            <a href="produk.php" class="<?= ($current_page == 'produk.php') ? 'text-brand-yellow font-bold border-b-2 border-brand-yellow pb-1' : 'text-gray-300 hover:text-white transition' ?>">Produk</a>
            
            <a href="jasa.php" class="<?= ($current_page == 'jasa.php') ? 'text-brand-yellow font-bold border-b-2 border-brand-yellow pb-1' : 'text-gray-300 hover:text-white transition' ?>">Jasa</a>
            
            <a href="portofolio.php" class="<?= ($current_page == 'portofolio.php' || $current_page == 'detail_portofolio.php') ? 'text-brand-yellow font-bold border-b-2 border-brand-yellow pb-1' : 'text-gray-300 hover:text-white transition' ?>">Portofolio</a>
            
            <a href="tentang.php" class="<?= ($current_page == 'tentang.php') ? 'text-brand-yellow font-bold border-b-2 border-brand-yellow pb-1' : 'text-gray-300 hover:text-white transition' ?>">Tentang</a>
            
            <a href="review.php" class="<?= ($current_page == 'review.php') ? 'text-brand-yellow font-bold border-b-2 border-brand-yellow pb-1' : 'text-gray-300 hover:text-white transition' ?>">Review</a>
        </nav>

        <!-- Tombol Pesan Sekarang -->
        <div>
            <a href="https://wa.me/6288291614900?text=Halo%20Mahira%20Printing!%20Saya%20mau%20konsultasi%20dan%20pesan%20jasa%20cetak.%20Boleh%20minta%20bantuannya%3F" target="_blank" class="bg-brand-yellow text-black text-xs font-extrabold px-5 py-2.5 rounded-lg hover:bg-yellow-400 transition">
                Pesan Sekarang
            </a>
        </div>
    </div>
</header>