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
        <!-- Logo Image Mahira Printing (Sesuaikan path gambarmu) -->
        <a href="jasa.php" class="flex items-center">
            <img src="../assets/images/logo.JPEG" alt="Mahira Printing" class="h-8 md:h-10 object-contain">
        </a>

        <!-- Menu Navigasi -->
        <nav class="hidden md:flex items-center gap-8 text-xs font-semibold text-gray-300">
            <a href="#" class="hover:text-white transition">Beranda</a>
            <a href="#" class="hover:text-white transition">Produk</a>
            <a href="jasa.php" class="text-brand-yellow font-bold border-b-2 border-brand-yellow pb-1">Jasa</a>
            <a href="portfolio.php" class="hover:text-white transition">Portofolio</a>
            <a href="#" class="hover:text-white transition">Tentang</a>
            <a href="review.php" class="hover:text-white transition">Review</a>
        </nav>

        <!-- Tombol Pesan Sekarang -->
        <div>
            <a href="https://wa.me/6288291614900" target="_blank" class="bg-brand-yellow text-black text-xs font-extrabold px-5 py-2.5 rounded-lg hover:bg-yellow-400 transition">
                Pesan Sekarang
            </a>
        </div>
    </div>
</header>