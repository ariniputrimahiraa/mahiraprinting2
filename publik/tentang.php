<?php
$whatsapp = 'https://wa.me/6288291614900';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Mahira Printing</title>

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

<!-- HEADER / NAVBAR -->
<header class="bg-black text-white py-4 px-6 md:px-12 border-b border-gray-800">
    <div class="max-w-7xl mx-auto flex justify-between items-center gap-4">

        <!-- Logo Image Mahira Printing -->
        <a href="dashboard.php" class="flex items-center shrink-0">
            <img src="../assets/images/logo.JPEG" alt="Mahira Printing" class="h-8 md:h-10 object-contain">
        </a>

        <!-- Menu Navigasi Desktop (Aktif pada Tentang) -->
        <nav id="navMenu" class="hidden md:flex items-center gap-5 lg:gap-8 text-xs font-semibold text-gray-300">
            <a href="dashboard.php" class="hover:text-white transition">Beranda</a>
            <a href="produk.php" class="hover:text-white transition">Produk</a>
            <a href="jasa.php" class="hover:text-white transition">Jasa</a>
            <a href="portofolio.php" class="hover:text-white transition">Portofolio</a>
            <a href="tentang.php" class="text-brand-yellow font-bold border-b-2 border-brand-yellow pb-1">Tentang</a>
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
        <a href="produk.php" class="block py-3 border-b border-gray-800">Produk</a>
        <a href="jasa.php" class="block py-3 border-b border-gray-800">Jasa</a>
        <a href="portofolio.php" class="block py-3 border-b border-gray-800">Portofolio</a>
        <a href="tentang.php" class="block py-3 border-b border-gray-800 text-brand-yellow font-bold">Tentang</a>
        <a href="review.php" class="block py-3">Review</a>
    </nav>
</header>

<main class="flex-1">

    <!-- HERO SECTION TENTANG -->
    <section class="bg-black text-white py-14 md:py-20 px-6 md:px-12">
        <div class="max-w-7xl mx-auto">
            <p class="text-brand-yellow text-[10px] font-bold tracking-[3px] uppercase mb-4">
                TENTANG MAHIRA PRINTING
            </p>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight tracking-tight">
                Lebih dari Sekedar<br>
                <span class="text-brand-yellow">Percetakan.</span>
            </h1>
        </div>
    </section>

    <!-- CERITA KAMI SECTION -->
    <section class="py-14 md:py-20 px-6 md:px-12 border-b border-[#e2ddcf]">
        <div class="max-w-7xl mx-auto grid md:grid-cols-12 gap-10 items-center">
            
            <div class="md:col-span-7">
                <p class="text-xs text-yellow-700 font-bold tracking-[3px] uppercase mb-3">
                    CERITA KAMI
                </p>

                <h2 class="text-3xl md:text-4xl font-black leading-tight mb-6">
                    Solusi Cetak Untuk<br>
                    <span class="text-yellow-600">Berbagai Kebutuhan.</span>
                </h2>

                <div class="space-y-4 text-gray-700 text-xs md:text-sm leading-relaxed max-w-2xl">
                    <p>
                        Mahira Printing merupakan usaha yang menyediakan layanan percetakan dan digital printing dengan cepat, harga terjangkau, serta hasil produk yang memuaskan.
                    </p>
                    <p>
                        Kami melayani berbagai kebutuhan cetak bisnis, acara, promosi, organisasi, maupun kebutuhan pribadi. Setiap pesanan dapat disesuaikan dengan kebutuhan pelanggan, mulai dari produk kemasan, brosur, stiker, spanduk, hingga merchandise khusus.
                    </p>
                    <p>
                        Mahira Printing berkomitmen selalu memberikan pengalaman cetak yang mudah dan membantu mewujudkan ide-ide kreatif yang sesuai dengan kebutuhan Anda.
                    </p>
                </div>
            </div>

            <!-- Lingkaran & Foto Toko -->
            <div class="md:col-span-5 flex justify-center relative">
                <div class="relative w-64 h-64 md:w-80 md:h-80 flex items-center justify-center">
                    <!-- Background Circle Yellow -->
                    <div class="absolute w-64 h-64 md:w-72 md:h-72 bg-brand-yellow rounded-full -left-4 top-0"></div>
                    
                    <!-- Store Image Card -->
                    <div class="relative z-10 w-52 md:w-60 bg-black p-2 shadow-2xl rounded-lg translate-x-4 translate-y-4">
                        <img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?w=500&q=80" alt="Mahira Printing Store" class="w-full h-64 md:h-72 object-cover rounded">
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- NILAI KAMI SECTION -->
    <section class="py-14 md:py-20 px-6 md:px-12">
        <div class="max-w-7xl mx-auto">
            
            <div class="mb-10">
                <p class="text-xs text-yellow-700 font-bold tracking-[3px] uppercase mb-3">
                    NILAI KAMI
                </p>

                <h2 class="text-3xl md:text-4xl font-black leading-tight max-w-xl">
                    Yang Kami Utamakan Dalam Melayani.
                </h2>

                <p class="text-gray-600 text-xs leading-6 mt-3 max-w-xl">
                    Kepuasan dan kepercayaan pelanggan merupakan bagian terpenting dalam memberikan pelayanan berkualitas.
                </p>
            </div>

            <!-- 3 Grid Card Nilai -->
            <div class="grid md:grid-cols-3 gap-5">
                
                <!-- Card 1: Kualitas -->
                <div class="bg-[#EAE4D4] p-6 flex flex-col justify-between min-h-48 border border-[#DFD9C8]">
                    <div>
                        <span class="text-[10px] font-bold text-gray-500 block mb-4">01</span>
                        <h3 class="font-black text-lg mb-3">Kualitas</h3>
                        <p class="text-xs text-gray-700 leading-relaxed">
                            Mengutamakan hasil cetakan yang rapi dan sesuai dengan kebutuhan pelanggan.
                        </p>
                    </div>
                </div>

                <!-- Card 2: Jadwal (Hitam) -->
                <div class="bg-black text-white p-6 flex flex-col justify-between min-h-48">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 block mb-4">02</span>
                        <h3 class="font-black text-lg mb-3">Jadwal</h3>
                        <p class="text-xs text-gray-300 leading-relaxed">
                            Memproses setiap pesanan secara efisien, presisi, dan selesai sesuai waktu yang ditentukan.
                        </p>
                    </div>
                </div>

                <!-- Card 3: Pelayanan (Kuning) -->
                <div class="bg-brand-yellow text-black p-6 flex flex-col justify-between min-h-48">
                    <div>
                        <span class="text-[10px] font-bold text-yellow-900 block mb-4">03</span>
                        <h3 class="font-black text-lg mb-3">Pelayanan</h3>
                        <p class="text-xs text-black/80 leading-relaxed">
                            Memberikan komunikasi yang ramah serta fleksibilitas membantu pelayanan dengan pelanggan.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- CONTACT INFO SECTION (Hitam) -->
    <section class="bg-black text-white py-14 px-6 md:px-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto grid md:grid-cols-12 gap-8 items-center">
            
            <div class="md:col-span-7">
                <p class="text-brand-yellow text-[10px] font-bold tracking-[3px] uppercase mb-2">
                    HUBUNGI KAMI
                </p>
                <h2 class="text-3xl md:text-4xl font-black leading-tight mb-3">
                    Ada kebutuhan <span class="text-brand-yellow">cetak?</span>
                </h2>
                <p class="text-gray-400 text-xs leading-relaxed max-w-lg">
                    Konsultasikan kebutuhan cetak, ukuran, atau kuantitas untuk bisnis maupun kebutuhan pribadi Anda ke Mahira Printing.
                </p>
            </div>

            <div class="md:col-span-5 space-y-4 text-xs border-l border-gray-800 pl-0 md:pl-8">
                <div>
                    <span class="text-gray-500 uppercase text-[10px] tracking-wider block mb-1">WHATSAPP</span>
                    <a href="<?= htmlspecialchars($whatsapp) ?>" target="_blank" class="font-bold text-white hover:text-brand-yellow transition">
                        0882-9161-4900
                    </a>
                </div>

                <div>
                    <span class="text-gray-500 uppercase text-[10px] tracking-wider block mb-1">INSTAGRAM</span>
                    <a href="https://instagram.com/mahiraprinting" target="_blank" class="font-bold text-white hover:text-brand-yellow transition">
                        @mahiraprinting
                    </a>
                </div>

                <div>
                    <span class="text-gray-500 uppercase text-[10px] tracking-wider block mb-1">JAM OPERASIONAL</span>
                    <p class="font-bold text-white">Monday - Sunday • 09.00 - 21.00</p>
                </div>
            </div>

        </div>
    </section>

    <!-- BOTTOM CTA BANNER (Kuning) -->
    <section class="bg-brand-yellow text-black py-10 px-6 md:px-12">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between md:items-center gap-6">
            <div>
                <p class="text-[10px] text-yellow-900 font-bold tracking-[3px] uppercase mb-1">
                    SIAP BANTU?
                </p>
                <h3 class="text-2xl md:text-3xl font-black">
                    Punya kebutuhan?<br>
                    Mari kita kerjakan.
                </h3>
            </div>

            <a href="<?= htmlspecialchars($whatsapp) ?>" target="_blank" rel="noopener noreferrer" class="bg-black text-brand-yellow text-xs font-extrabold px-6 py-3.5 rounded-lg hover:bg-gray-900 transition text-center self-start md:self-auto">
                Hubungi Kami ↗
            </a>
        </div>
    </section>

</main>

<!-- FOOTER SESUAI FIGMA -->
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