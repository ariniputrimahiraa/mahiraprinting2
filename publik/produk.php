<?php
// Mock Data Produk sesuai referensi gambar Mahira Printing
$products = [
    [
        'id' => 1,
        'category' => 'LABEL & STIKER',
        'name' => 'Stiker Printing',
        'subtext' => 'Kategori Printing',
        'image' => 'https://images.unsplash.com/photo-1572375992501-4b0892d50c69?w=500&q=80',
    ],
    [
        'id' => 2,
        'category' => 'SABLON',
        'name' => 'DTF Printing',
        'subtext' => 'Kategori Sablon',
        'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500&q=80',
    ],
    [
        'id' => 3,
        'category' => 'STEMPEL',
        'name' => 'Stempel',
        'subtext' => 'Kategori Stempel',
        'image' => 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?w=500&q=80',
    ],
    [
        'id' => 4,
        'category' => 'SABLON MUG',
        'name' => 'Mug Printing',
        'subtext' => 'Kategori Printing',
        'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=500&q=80',
    ],
    [
        'id' => 5,
        'category' => 'CETAK BROSUR',
        'name' => 'Brosur',
        'subtext' => 'Kategori Cetak',
        'image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=500&q=80',
    ],
    [
        'id' => 6,
        'category' => 'CETAK UNDANGAN',
        'name' => 'Undangan',
        'subtext' => 'Kategori Cetak',
        'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=500&q=80',
    ],
];

$whatsapp = 'https://wa.me/6288291614900';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - Mahira Printing</title>

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

    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body class="bg-[#F3F0E6] min-h-screen flex flex-col font-sans">

    <!-- Header / Navbar Hitam -->
    <header class="bg-black text-white py-4 px-6 md:px-12 border-b border-gray-800">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-4">
            <!-- Logo Image Mahira Printing -->
            <a href="dashboard.php" class="flex items-center shrink-0">
                <img src="../assets/images/logo.JPEG" alt="Mahira Printing" class="h-8 md:h-10 object-contain">
            </a>

            <!-- Menu Navigasi Desktop (Aktif pada Produk) -->
            <nav id="navMenu" class="hidden md:flex items-center gap-5 lg:gap-8 text-xs font-semibold text-gray-300">
                <a href="dashboard.php" class="hover:text-white transition">Beranda</a>
                <a href="produk.php" class="text-brand-yellow font-bold border-b-2 border-brand-yellow pb-1">Produk</a>
                <a href="jasa.php" class="hover:text-white transition">Jasa</a>
                <a href="portofolio.php" class="hover:text-white transition">Portofolio</a>
                <a href="tentang.php" class="hover:text-white transition">Tentang</a>
                <a href="review.php" class="hover:text-white transition">Review</a>
            </nav>

            <!-- Tombol Pesan Sekarang -->
            <div class="flex items-center gap-3">
                <a href="<?= htmlspecialchars($whatsapp) ?>" target="_blank" rel="noopener noreferrer" class="bg-brand-yellow text-black text-xs font-extrabold px-4 md:px-5 py-2.5 rounded-lg hover:bg-yellow-400 transition whitespace-nowrap">
                    Pesan Sekarang
                </a>

                <button id="menuToggle" type="button" class="md:hidden text-white border border-gray-700 rounded px-3 py-2" aria-label="Buka menu" aria-expanded="false">
                    ☰
                </button>
            </div>
        </div>

        <!-- Mobile Menu Navigasi -->
        <nav id="mobileMenu" class="hidden md:hidden max-w-7xl mx-auto pt-4 text-sm text-gray-300">
            <a href="dashboard.php" class="block py-3 border-b border-gray-800">Beranda</a>
            <a href="produk.php" class="block py-3 border-b border-gray-800 text-brand-yellow font-bold">Produk</a>
            <a href="jasa.php" class="block py-3 border-b border-gray-800">Jasa</a>
            <a href="portofolio.php" class="block py-3 border-b border-gray-800">Portofolio</a>
            <a href="tentang.php" class="block py-3 border-b border-gray-800">Tentang</a>
            <a href="review.php" class="block py-3">Review</a>
        </nav>
    </header>

    <main class="flex-grow w-full">

        <!-- Sub-Hero Banner Hitam (Judul Katalog) -->
        <section class="bg-black text-white py-12 px-6 md:px-12 border-b border-gray-800">
            <div class="max-w-7xl mx-auto">
                <span class="text-brand-yellow text-xs font-bold uppercase tracking-widest block mb-2">KATALOG MAHIRA PRINTING</span>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
                    Temukan Produk Yang<br>
                    <span class="text-brand-yellow">Kamu Butuhkan.</span>
                </h1>
                <p class="text-gray-400 max-w-xl text-sm leading-relaxed">
                    Jelajahi berbagai produk cetak berkualitas, seperti stiker, mug, brosur, banner, dan souvenir dari Mahira Printing.
                </p>
            </div>
        </section>

        <!-- Main Content Katalog Produk -->
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-10">

            <!-- Search Bar Container -->
            <div class="bg-brand-yellow p-4 rounded-xl mb-10 shadow-sm">
                <label class="block text-xs font-bold text-black uppercase mb-2">CARI PRODUK</label>
                <input 
                    type="text" 
                    placeholder="Cari nama produk..." 
                    class="w-full bg-white px-4 py-3 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black text-gray-800 placeholder-gray-400 shadow-inner"
                >
            </div>

            <!-- Section Heading -->
            <div class="mb-8">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-widest block mb-1">PRODUK TERBAIK</span>
                <h2 class="text-2xl font-black text-black">Pilihan Produk Mahira.</h2>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($products as $item): ?>
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200 flex flex-col justify-between hover:shadow-lg transition-all duration-300">
                        
                        <!-- Card Top Graphic/Banner (Kuning) -->
                        <div class="bg-gradient-to-b from-yellow-300 to-brand-yellow p-4 relative min-h-[220px] flex flex-col justify-between">
                            <!-- Category Badge -->
                            <div>
                                <span class="bg-black text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                                    <?= htmlspecialchars($item['category']) ?>
                                </span>
                            </div>
                            
                            <!-- Product Image -->
                            <div class="my-3 flex justify-center items-center h-32">
                                <img 
                                    src="<?= htmlspecialchars($item['image']) ?>" 
                                    alt="<?= htmlspecialchars($item['name']) ?>" 
                                    class="h-full object-contain drop-shadow-md rounded"
                                >
                            </div>

                            <!-- Badge Footer inside Card -->
                            <div class="bg-white/80 backdrop-blur-sm rounded-md px-3 py-1 flex justify-between items-center text-[10px] text-gray-800 font-semibold">
                                <span>CETAK Cepat & Rapi</span>
                                <span>BEST QUALITY</span>
                            </div>
                        </div>

                        <!-- Card Info Bottom -->
                        <div class="p-4 flex items-center justify-between bg-white border-t border-gray-100">
                            <div>
                                <span class="text-[11px] text-gray-400 block"><?= htmlspecialchars($item['subtext']) ?></span>
                                <h3 class="font-bold text-gray-900 text-base"><?= htmlspecialchars($item['name']) ?></h3>
                            </div>
                            <a href="detail_portofolio.php?id=<?= $item['id'] ?>" class="bg-black hover:bg-gray-800 text-white font-bold text-xs px-4 py-2 rounded-lg transition">
                                Detail
                            </a>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

        </div>

        <!-- Bottom CTA Banner Hitam -->
        <section class="bg-black text-white py-12 px-6 md:px-12 border-t border-gray-800">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <span class="text-brand-yellow text-xs font-bold uppercase tracking-widest block mb-1">LAYANAN KAMI</span>
                    <h3 class="text-2xl font-extrabold">Punya kebutuhan cetak?</h3>
                    <p class="text-brand-yellow text-2xl font-extrabold">Mari kita kerjakan.</p>
                </div>
                <a href="<?= htmlspecialchars($whatsapp) ?>" target="_blank" rel="noopener noreferrer" class="bg-brand-yellow hover:bg-yellow-400 text-black font-extrabold text-xs uppercase px-6 py-3 rounded-lg transition">
                    HUBUNGI KAMI
                </a>
            </div>
        </section>

    </main>

    <!-- Footer Hitam -->
    <footer class="bg-black text-white border-t border-gray-900 py-6 px-6 md:px-12">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
            <!-- Logo Image & Subtitle -->
            <div class="flex items-center gap-4">
                <img src="../assets/images/logo.JPEG" alt="Mahira Printing" class="h-7 md:h-8 object-contain">
                <span class="text-gray-500 text-[10px] uppercase font-bold tracking-widest pl-4 border-l border-gray-800">
                    PERCETAKAN & DIGITAL PRINTING
                </span>
            </div>

            <!-- Copyright -->
            <div class="text-gray-500 text-[11px]">
                &copy; <?= date('Y') ?> Mahira Printing. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        menuToggle.addEventListener('click', function () {
            const isOpen = !mobileMenu.classList.contains('hidden');

            mobileMenu.classList.toggle('hidden');

            menuToggle.setAttribute(
                'aria-expanded',
                String(!isOpen)
            );

            menuToggle.textContent = isOpen ? '☰' : '×';
        });

        mobileMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                mobileMenu.classList.add('hidden');
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.textContent = '☰';
            });
        });
    </script>

</body>
</html>