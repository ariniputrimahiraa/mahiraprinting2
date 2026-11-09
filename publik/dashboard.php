<?php
require_once '../config/database.php';

// Mengambil data produk aktif dari database mahira_database[cite: 1]
try {
    $stmt = $pdo->query("SELECT id_produk, nama_produk, gambar, deskripsi, spesifikasi, estimasi_pengerjaan, minimal_order, kategori 
                         FROM produk 
                         WHERE status = 'aktif' 
                         ORDER BY id_produk DESC");
    $produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $produk = [];
}

$whatsapp = 'https://wa.me/6288291614900';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahira Printing - Beranda</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- GOOGLE FONTS ICONS (Material Symbols Outlined) -->
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

        .hero-ring {
            position: absolute;
            width: 360px;
            height: 360px;
            right: -160px;
            top: 25px;
            border: 35px solid #392c00;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-sheet {
            transform: rotate(-7deg);
            box-shadow: 0 20px 55px rgba(0, 0, 0, .3);
        }

        .product-image img {
            transition: transform .35s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        @media (max-width: 767px) {
            .hero-ring {
                width: 250px;
                height: 250px;
                right: -150px;
                border-width: 25px;
            }
        }
    </style>
</head>

<body class="bg-brand-bg min-h-screen flex flex-col font-sans">

<!-- HEADER / NAVBAR -->
<header class="bg-black text-white py-4 px-6 md:px-12 border-b border-gray-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center gap-4">

        <a href="dashboard.php" class="flex items-center shrink-0">
            <img src="../assets/images/logo.JPEG" alt="Mahira Printing" class="h-8 md:h-10 object-contain">
        </a>

        <!-- Menu Navigasi Desktop -->
        <nav id="navMenu" class="hidden md:flex items-center gap-5 lg:gap-8 text-xs font-semibold text-gray-300">
            <a href="dashboard.php" class="text-brand-yellow font-bold border-b-2 border-brand-yellow pb-1">Beranda</a>
            <a href="produk.php" class="hover:text-brand-yellow transition">Produk</a>
            <a href="jasa.php" class="hover:text-brand-yellow transition">Jasa</a>
            <a href="portfolio.php" class="hover:text-brand-yellow transition">Portofolio</a>
            <a href="tentang.php" class="hover:text-brand-yellow transition">Tentang</a>
            <a href="review.php" class="hover:text-brand-yellow transition">Review</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="<?= htmlspecialchars($whatsapp) ?>"
               target="_blank"
               rel="noopener noreferrer"
               class="bg-brand-yellow text-black text-xs font-extrabold px-4 md:px-5 py-2.5 rounded-lg hover:bg-yellow-400 transition whitespace-nowrap shadow-sm">
                Pesan Sekarang
            </a>

            <button id="menuToggle"
                    type="button"
                    class="md:hidden text-white border border-gray-700 rounded px-3 py-2 hover:bg-gray-800 transition"
                    aria-label="Buka menu"
                    aria-expanded="false">
                ☰
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <nav id="mobileMenu" class="hidden md:hidden max-w-7xl mx-auto pt-4 text-sm text-gray-300">
        <a href="dashboard.php" class="block py-3 border-b border-gray-800 text-brand-yellow font-bold">Beranda</a>
        <a href="produk.php" class="block py-3 border-b border-gray-800">Produk</a>
        <a href="jasa.php" class="block py-3 border-b border-gray-800">Jasa</a>
        <a href="portfolio.php" class="block py-3 border-b border-gray-800">Portofolio</a>
        <a href="tentang.php" class="block py-3 border-b border-gray-800">Tentang</a>
        <a href="review.php" class="block py-3">Review</a>
    </nav>
</header>

<main class="flex-1">

    <!-- HERO SECTION -->
    <section class="relative bg-black text-white overflow-hidden">
        <div class="hero-ring"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 py-14 md:py-20">
            <div class="grid md:grid-cols-2 gap-10 items-center relative z-10">

                <div>
                    <p class="text-brand-yellow text-[10px] font-bold tracking-[3px] uppercase mb-5">
                        Percetakan • Kreatif • Berkualitas
                    </p>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight tracking-tight mb-5">
                        Cetak Apa Saja,<br>
                        <span class="text-brand-yellow">Lebih Mudah.</span>
                    </h1>

                    <p class="text-gray-400 text-sm leading-7 max-w-md mb-7">
                        Butuh cetak untuk bisnis, acara, atau kebutuhan pribadi?
                        Mahira Printing siap membantu ide kamu jadi nyata dengan
                        hasil berkualitas dan proses yang praktis.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="produk.php"
                           class="bg-brand-yellow text-black px-6 py-3 rounded-md text-xs font-extrabold hover:bg-yellow-400 transition shadow-sm">
                            Lihat Produk
                        </a>

                        <a href="<?= htmlspecialchars($whatsapp . '?text=' . rawurlencode('Halo Mahira Printing, saya ingin konsultasi mengenai layanan percetakan.'), ENT_QUOTES, 'UTF-8') ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="border border-gray-600 text-white px-6 py-3 rounded-md text-xs font-bold hover:border-brand-yellow hover:text-brand-yellow transition">
                            Konsultasi WA
                        </a>
                    </div>
                </div>

                <!-- Ilustrasi Cetak -->
                <div class="relative min-h-[280px] md:min-h-[330px] flex items-center justify-center">
                    <div class="absolute w-64 h-64 bg-yellow-900/20 rounded-full blur-3xl"></div>

                    <div class="absolute left-2 top-8 md:left-5 bg-gray-950 border border-yellow-900 p-3 w-24 rotate-[-10deg] z-10 shadow-lg">
                        <div class="bg-brand-yellow text-black text-[10px] font-black text-center py-5">
                            MAHIRA
                        </div>
                        <div class="h-1 bg-gray-600 mt-2 w-4/5 mx-auto"></div>
                        <div class="h-1 bg-gray-600 mt-1 w-1/2 mx-auto"></div>
                    </div>

                    <div class="hero-sheet relative z-10 bg-[#f5f0df] text-black border-[5px] border-[#e3d9b7] p-5 w-64 md:w-72 min-h-52">
                        <div class="flex items-center justify-between border-b border-[#d5cda9] pb-3">
                            <span class="font-black text-sm">
                                <span class="text-yellow-600">✦</span> MAHIRA
                            </span>
                            <span class="text-[8px] tracking-widest">PRINT YOUR IDEA</span>
                        </div>

                        <div class="text-2xl md:text-3xl font-black leading-tight mt-6 mb-3">
                            IDE KAMU,<br>
                            <span class="text-yellow-600">JADI NYATA.</span>
                        </div>

                        <div class="h-1 bg-[#d8d0b6] w-3/4 mb-2"></div>
                        <div class="h-1 bg-[#d8d0b6] w-2/5"></div>

                        <p class="text-[8px] tracking-widest font-bold mt-5">
                            PRINTING • DESIGN • CREATIVE
                        </p>
                    </div>

                    <div class="absolute right-1 bottom-7 bg-gray-950 border border-yellow-900 p-3 w-20 rotate-[10deg] z-10 shadow-lg">
                        <div class="bg-brand-yellow text-black text-[9px] font-black text-center py-4">
                            PRINT IT!
                        </div>
                        <div class="h-1 bg-gray-600 mt-2 w-4/5 mx-auto"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- KEUNGGULAN -->
    <section class="bg-brand-yellow text-black border-y border-yellow-500">
        <div class="max-w-7xl mx-auto px-6 md:px-12 grid grid-cols-2 md:grid-cols-4">

            <div class="py-5 px-3 md:px-5 border-r border-black/15">
                <p class="font-black text-xl">01</p>
                <p class="text-xs font-bold mt-1">Harga Bersahabat</p>
            </div>

            <div class="py-5 px-3 md:px-5 md:border-r border-black/15">
                <p class="font-black text-xl">02</p>
                <p class="text-xs font-bold mt-1">Hasil Berkualitas</p>
            </div>

            <div class="py-5 px-3 md:px-5 border-r border-black/15 border-t md:border-t-0">
                <p class="font-black text-xl">03</p>
                <p class="text-xs font-bold mt-1">Berbagai Pilihan Produk</p>
            </div>

            <div class="py-5 px-3 md:px-5 border-t md:border-t-0 border-black/15">
                <p class="font-black text-xl">04</p>
                <p class="text-xs font-bold mt-1">Proses Praktis & Cepat</p>
            </div>

        </div>
    </section>

    <!-- LAYANAN (DENGAN GOOGLE FONTS ICONS) -->
    <section id="layanan" class="bg-brand-bg text-black py-14 md:py-20">
        <div class="max-w-7xl mx-auto px-6 md:px-12">

            <div class="flex flex-col md:flex-row justify-between md:items-end gap-5 mb-9">
                <div>
                    <p class="text-xs text-yellow-700 font-bold tracking-[3px] uppercase mb-3">
                        Layanan Kami
                    </p>
                    <h2 class="text-3xl md:text-4xl font-black leading-tight">
                        Semua Kebutuhan Cetak<br>Ada di Sini.
                    </h2>
                    <p class="text-gray-600 text-xs leading-6 max-w-lg mt-3">
                        Dari kebutuhan personal sampai bisnis, temukan layanan cetak yang sesuai dengan ide dan kebutuhanmu.
                    </p>
                </div>

                <a href="jasa.php" class="text-xs font-bold border-b-2 border-black pb-1 hover:text-yellow-700 hover:border-yellow-700 transition self-start md:self-auto">
                    Tanya Semua Layanan
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Layanan 1 -->
                <div class="bg-[#e9e4d7] p-5 min-h-48 rounded-lg border border-[#ded8c8] flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 bg-white border border-gray-300 rounded-md flex items-center justify-center mb-5 text-gray-800 shadow-xs">
                            <span class="material-symbols-outlined text-xl">print</span>
                        </div>
                        <h3 class="font-black text-sm mb-2">Printing & Stationery</h3>
                        <p class="text-xs text-gray-600 leading-5">
                            Cetak dokumen, kartu nama, brosur, dan kebutuhan kantor.
                        </p>
                    </div>
                </div>

                <!-- Layanan 2 -->
                <div class="bg-black text-white p-5 min-h-48 rounded-lg flex flex-col justify-between shadow-md">
                    <div>
                        <div class="w-10 h-10 bg-zinc-900 border border-zinc-700 rounded-md flex items-center justify-center mb-5 text-brand-yellow shadow-xs">
                            <span class="material-symbols-outlined text-xl">palette</span>
                        </div>
                        <h3 class="font-black text-sm mb-2">Design & Branding</h3>
                        <p class="text-xs text-gray-400 leading-5">
                            Bantu wujudkan identitas visual untuk brand kamu.
                        </p>
                    </div>
                </div>

                <!-- Layanan 3 -->
                <div class="bg-[#e9e4d7] p-5 min-h-48 rounded-lg border border-[#ded8c8] flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 bg-white border border-gray-300 rounded-md flex items-center justify-center mb-5 text-gray-800 shadow-xs">
                            <span class="material-symbols-outlined text-xl">card_giftcard</span>
                        </div>
                        <h3 class="font-black text-sm mb-2">Custom Merchandise</h3>
                        <p class="text-xs text-gray-600 leading-5">
                            Produk custom untuk hadiah, komunitas, dan promosi.
                        </p>
                    </div>
                </div>

                <!-- Layanan 4 -->
                <div class="bg-brand-yellow text-black p-5 min-h-48 rounded-lg flex flex-col justify-between shadow-sm">
                    <div>
                        <div class="w-10 h-10 bg-black/10 border border-black/20 rounded-md flex items-center justify-center mb-5 text-black shadow-xs">
                            <span class="material-symbols-outlined text-xl">star</span>
                        </div>
                        <h3 class="font-black text-sm mb-2">Produk & Lainnya</h3>
                        <p class="text-xs leading-5">
                            Pilihan produk cetak sesuai kebutuhanmu.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- PRODUK PILIHAN -->
    <section id="produk" class="bg-brand-bg text-black pb-16 md:pb-20">
        <div class="max-w-7xl mx-auto px-6 md:px-12">

            <div class="flex flex-col md:flex-row justify-between md:items-end gap-5 mb-8">
                <div>
                    <p class="text-xs text-yellow-700 font-bold tracking-[3px] uppercase mb-3">
                        Produk Pilihan
                    </p>
                    <h2 class="text-3xl md:text-4xl font-black">
                        Pilihan Produk Mahira.
                    </h2>
                    <p class="text-gray-600 text-xs leading-6 max-w-lg mt-3">
                        Cari produk yang kamu butuhkan, lalu konsultasikan desain dan jumlah cetaknya dengan kami.
                    </p>
                </div>

                <a href="produk.php" class="text-xs font-bold border-b-2 border-black pb-1 hover:text-yellow-700 hover:border-yellow-700 transition self-start md:self-auto">
                    Lihat Semua Produk
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-6">

                <?php if (count($produk) > 0): ?>
                    <?php foreach ($produk as $item): 
                        $kategori = !empty($item['kategori']) ? $item['kategori'] : 'PERCETAKAN';
                        $gambar = !empty($item['gambar']) ? '../uploads/' . $item['gambar'] : '../assets/images/logo.JPEG';
                        $min_order = !empty($item['minimal_order']) ? 'Min. ' . $item['minimal_order'] . ' pcs' : 'Custom Order';
                        $estimasi = !empty($item['estimasi_pengerjaan']) ? $item['estimasi_pengerjaan'] . ' Hari Kerja' : 'Proses Cepat';
                        $ukuran = !empty($item['spesifikasi']) ? $item['spesifikasi'] : 'Lembar A5 / A4 / Custom';
                    ?>

                        <article class="product-card bg-white border border-[#e2ddcf] rounded-xl overflow-hidden flex flex-col justify-between shadow-sm hover:shadow-md transition duration-300">

                            <div>
                                <a href="detail_produk.php?id=<?= $item['id_produk'] ?>" class="product-image block relative h-80 md:h-96 overflow-hidden bg-[#e7e0cc]">
                                    <img src="<?= htmlspecialchars($gambar, ENT_QUOTES, 'UTF-8') ?>"
                                         alt="<?= htmlspecialchars($item['nama_produk'], ENT_QUOTES, 'UTF-8') ?>"
                                         class="w-full h-full object-cover object-center"
                                         loading="lazy">

                                    <span class="absolute top-3 left-3 bg-brand-yellow text-black text-[9px] font-black tracking-widest px-3 py-1.5 rounded uppercase shadow-sm z-10">
                                        <?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </a>

                                <div class="p-5">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="text-[10px] bg-yellow-100 text-yellow-800 font-bold px-2 py-0.5 rounded flex items-center gap-1">
                                            📏 <?= htmlspecialchars($ukuran, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                        <span class="text-[10px] bg-gray-100 text-gray-700 font-bold px-2 py-0.5 rounded">
                                            <?= htmlspecialchars($estimasi, ENT_QUOTES, 'UTF-8') ?>
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

                            <div class="px-5 pb-5 pt-3 border-t border-gray-100 mt-auto bg-gray-50/50">
                                <div class="flex justify-between items-center gap-3">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-gray-400 uppercase font-semibold">Minimal Order</span>
                                        <span class="font-bold text-xs text-gray-800">
                                            <?= htmlspecialchars($min_order, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </div>

                                    <a href="<?= htmlspecialchars($whatsapp . '?text=' . rawurlencode('Halo Mahira Printing, saya ingin memesan produk ' . $item['nama_produk'] . ' ukuran lembaran (A5/A4/Custom).'), ENT_QUOTES, 'UTF-8') ?>"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="bg-brand-yellow hover:bg-yellow-400 text-black font-extrabold text-xs px-3.5 py-2 rounded-lg transition flex items-center gap-1.5 shadow-sm shrink-0"
                                       aria-label="Pesan <?= htmlspecialchars($item['nama_produk'], ENT_QUOTES, 'UTF-8') ?>">
                                        <span>Pesan</span>
                                    </a>
                                </div>
                            </div>

                        </article>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-3 text-center py-16 bg-white border border-[#e2ddcf] rounded-xl">
                        <p class="text-xs text-gray-500 font-bold">Belum ada produk yang tersedia saat ini.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </section>

    <!-- CALL TO ACTION (CTA) -->
    <section id="tentang" class="bg-brand-yellow text-black py-12">
        <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row justify-between md:items-center gap-6">

            <div>
                <p class="text-[10px] text-yellow-900 font-bold tracking-[3px] uppercase mb-2">
                    Punya Kebutuhan Cetak?
                </p>

                <h2 class="text-3xl md:text-4xl font-black leading-tight">
                    Mari Kita Kerjakan.
                </h2>

                <p class="text-xs text-yellow-950 mt-2">
                    Ceritakan kebutuhanmu, kami bantu cari solusi cetak yang tepat.
                </p>
            </div>

            <a href="<?= htmlspecialchars($whatsapp . '?text=' . rawurlencode('Halo Mahira Printing, saya ingin bertanya mengenai layanan percetakan.'), ENT_QUOTES, 'UTF-8') ?>"
               target="_blank"
               rel="noopener noreferrer"
               class="bg-black text-brand-yellow text-xs font-extrabold px-7 py-4 rounded-lg hover:bg-gray-900 transition text-center shadow-md shrink-0">
                Hubungi MAHIRA
            </a>

        </div>
    </section>

</main>

<!-- FOOTER -->
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