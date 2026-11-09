<?php
require_once '../config/database.php';

// Menangkap keyword pencarian jika user memasukkan kata kunci
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    if (!empty($search)) {
        // Query pencarian produk berdasarkan nama, deskripsi, atau kategori
        $stmt = $pdo->prepare("SELECT id_produk, nama_produk, gambar, deskripsi, spesifikasi, estimasi_pengerjaan, minimal_order, kategori 
                               FROM produk 
                               WHERE status = 'aktif' AND (nama_produk LIKE :search OR deskripsi LIKE :search OR kategori LIKE :search)
                               ORDER BY id_produk DESC");
        $stmt->execute(['search' => '%' . $search . '%']);
    } else {
        // Query default menampilkan seluruh produk aktif dari database
        $stmt = $pdo->query("SELECT id_produk, nama_produk, gambar, deskripsi, spesifikasi, estimasi_pengerjaan, minimal_order, kategori 
                             FROM produk 
                             WHERE status = 'aktif' 
                             ORDER BY id_produk DESC");
    }
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
}

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
    
    <!-- Google Material Symbols Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

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
        .product-image img {
            transition: transform .35s ease;
        }
        .product-card:hover .product-image img {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-[#F3F0E6] min-h-screen flex flex-col font-sans">

    <!-- Header / Navbar Hitam -->
    <header class="bg-black text-white py-4 px-6 md:px-12 border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-4">
            <!-- Logo Image Mahira Printing -->
            <a href="dashboard.php" class="flex items-center shrink-0">
                <img src="../assets/images/logo.JPEG" alt="Mahira Printing" class="h-8 md:h-10 object-contain">
            </a>

            <!-- Menu Navigasi Desktop -->
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
                <a href="<?= htmlspecialchars($whatsapp) ?>" target="_blank" rel="noopener noreferrer" class="bg-brand-yellow text-black text-xs font-extrabold px-4 md:px-5 py-2.5 rounded-lg hover:bg-yellow-400 transition whitespace-nowrap shadow-sm">
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

        <!-- Sub-Hero Banner Hitam -->
        <section class="bg-black text-white py-12 px-6 md:px-12 border-b border-gray-800">
            <div class="max-w-7xl mx-auto">
                <span class="text-brand-yellow text-xs font-bold uppercase tracking-widest block mb-2">KATALOG MAHIRA PRINTING</span>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
                    Temukan Produk Yang<br>
                    <span class="text-brand-yellow">Kamu Butuhkan.</span>
                </h1>
                <p class="text-gray-400 max-w-xl text-sm leading-relaxed">
                    Jelajahi berbagai produk cetak berkualitas tinggi dari Mahira Printing untuk kebutuhan personal maupun bisnis Anda.
                </p>
            </div>
        </section>

        <!-- Main Content Katalog Produk -->
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-10">

            <!-- Search Bar Profesional (Menggunakan Google Material Icon) -->
            <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-200 mb-12">
                <form action="produk.php" method="GET" class="flex flex-col md:flex-row items-center gap-3">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                            <span class="material-symbols-outlined text-xl">search</span>
                        </span>
                        <input 
                            type="text" 
                            name="search" 
                            value="<?= htmlspecialchars($search) ?>" 
                            placeholder="Cari nama produk, kategori, atau deskripsi..." 
                            class="w-full bg-gray-50 border border-gray-200 pl-12 pr-4 py-3.5 rounded-xl text-xs md:text-sm focus:outline-none focus:border-black focus:bg-white text-gray-800 placeholder-gray-400 transition"
                        >
                    </div>
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <button type="submit" class="w-full md:w-auto bg-black hover:bg-gray-800 text-white font-bold text-xs px-6 py-3.5 rounded-xl transition whitespace-nowrap shadow-sm">
                            Cari Produk
                        </button>
                        <?php if (!empty($search)): ?>
                            <a href="produk.php" class="w-full md:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs px-5 py-3.5 rounded-xl transition text-center">
                                Reset
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Section Heading -->
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <span class="text-xs font-bold text-amber-700 uppercase tracking-widest block mb-1">PRODUK TERBAIK</span>
                    <h2 class="text-2xl md:text-3xl font-black text-black">Pilihan Produk Mahira.</h2>
                </div>
                <?php if (!empty($search)): ?>
                    <p class="text-xs text-gray-600">Hasil pencarian untuk: <b>"<?= htmlspecialchars($search) ?>"</b></p>
                <?php endif; ?>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $item): 
                        $kategori = !empty($item['kategori']) ? $item['kategori'] : 'PERCETAKAN';
                        $gambar = !empty($item['gambar']) ? '../uploads/' . $item['gambar'] : '../assets/images/logo.JPEG';
                        $min_order = !empty($item['minimal_order']) ? 'Min. ' . $item['minimal_order'] . ' pcs' : 'Custom Order';
                        $estimasi = !empty($item['estimasi_pengerjaan']) ? $item['estimasi_pengerjaan'] . ' Hari Kerja' : 'Proses Cepat';
                        $ukuran = !empty($item['spesifikasi']) ? $item['spesifikasi'] : 'Custom Size';
                    ?>
                        <article class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200 flex flex-col justify-between hover:shadow-md transition-all duration-300">
                            
                            <div>
                                <!-- Gambar Produk dengan ukuran portrait -->
                                <a href="detail_produk.php?id=<?= $item['id_produk'] ?>" class="product-image block relative h-80 md:h-96 overflow-hidden bg-gray-100">
                                    <img 
                                        src="<?= htmlspecialchars($gambar, ENT_QUOTES, 'UTF-8') ?>" 
                                        alt="<?= htmlspecialchars($item['nama_produk'], ENT_QUOTES, 'UTF-8') ?>" 
                                        class="w-full h-full object-cover object-center"
                                        loading="lazy"
                                    >
                                    <!-- Badge Kategori ala Google Sites Minimalis -->
                                    <div class="absolute top-3 left-3 z-10">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-gray-800 shadow-sm" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(4px); border: 1px solid rgba(0, 0, 0, 0.1);">
                                            <span class="w-2 h-2 rounded-full" style="background-color: #FFD028;"></span>
                                            <?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </div>
                                </a>

                                <div class="p-5">
                                    <!-- Badge Spesifikasi & Estimasi -->
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="text-[10px] bg-yellow-50 text-yellow-800 font-bold px-2 py-0.5 rounded border border-yellow-200 flex items-center gap-1">
                                            📏 <?= htmlspecialchars($ukuran, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                        <span class="text-[10px] bg-gray-100 text-gray-700 font-bold px-2 py-0.5 rounded">
                                            ⏱️ <?= htmlspecialchars($estimasi, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </div>

                                    <a href="detail_produk.php?id=<?= $item['id_produk'] ?>" class="font-black text-lg mb-2 block hover:text-yellow-600 transition capitalize line-clamp-1">
                                        <?= htmlspecialchars($item['nama_produk'], ENT_QUOTES, 'UTF-8') ?>
                                    </a>

                                    <p class="text-gray-600 text-xs leading-5 line-clamp-2 min-h-[2.5rem]">
                                        <?= htmlspecialchars($item['deskripsi'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Card Footer & Tombol Order WA -->
                            <div class="px-5 pb-5 pt-3 border-t border-gray-100 mt-auto bg-gray-50/50">
                                <div class="flex justify-between items-center gap-3">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-gray-400 uppercase font-semibold">Minimal Order</span>
                                        <span class="font-bold text-xs text-gray-800">
                                            <?= htmlspecialchars($min_order, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </div>

                                    <a href="<?= htmlspecialchars($whatsapp . '?text=' . rawurlencode('Halo Mahira Printing, saya ingin memesan produk ' . $item['nama_produk'] . '.'), ENT_QUOTES, 'UTF-8') ?>"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="bg-brand-yellow hover:bg-yellow-400 text-black font-extrabold text-xs px-4 py-2.5 rounded-lg transition flex items-center gap-1.5 shadow-xs shrink-0"
                                       aria-label="Pesan <?= htmlspecialchars($item['nama_produk'], ENT_QUOTES, 'UTF-8') ?>">
                                        <span>Pesan WA</span>
                                        <span class="text-sm leading-none">↗</span>
                                    </a>
                                </div>
                            </div>

                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-3 text-center py-16 bg-white border border-gray-200 rounded-2xl shadow-sm">
                        <p class="text-sm text-gray-600 font-bold mb-1">Belum ada produk yang tersedia saat ini.</p>
                        <p class="text-xs text-gray-400">Silakan tambahkan data produk melalui panel admin atau gunakan kata kunci lain.</p>
                    </div>
                <?php endif; ?>
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
                <a href="<?= htmlspecialchars($whatsapp) ?>" target="_blank" rel="noopener noreferrer" class="bg-brand-yellow hover:bg-yellow-400 text-black font-extrabold text-xs px-6 py-3.5 rounded-lg transition shadow-md">
                    Hubungi MAHIRA
                </a>
            </div>
        </section>

    </main>

    <!-- Footer Hitam -->
    <footer class="bg-black text-white border-t border-gray-900 py-6 px-6 md:px-12">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
            <div class="flex items-center gap-4">
                <img src="../assets/images/logo.JPEG" alt="Mahira Printing" class="h-7 md:h-8 object-contain">
                <span class="text-gray-500 text-[10px] uppercase font-bold tracking-widest pl-4 border-l border-gray-800">
                    PERCETAKAN & DIGITAL PRINTING
                </span>
            </div>

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
            menuToggle.setAttribute('aria-expanded', String(!isOpen));
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