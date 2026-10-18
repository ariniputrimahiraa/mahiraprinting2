<?php
$produk = [
    [
        'kategori' => 'STIKER',
        'nama' => 'Stiker Printing',
        'deskripsi' => 'Stiker custom untuk kebutuhan promosi dan personal.',
        'harga' => 'Mulai Rp10.000',
        'gambar' => 'https://images.unsplash.com/photo-1634942536790-253e6e7a97f5?auto=format&fit=crop&w=900&q=85'
    ],
    [
        'kategori' => 'APPAREL',
        'nama' => 'DTF Printing',
        'deskripsi' => 'Cetak desain pilihanmu untuk kaos dan merchandise.',
        'harga' => 'Mulai Rp25.000',
        'gambar' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=85'
    ],
    [
        'kategori' => 'MERCHANDISE',
        'nama' => 'Stempel',
        'deskripsi' => 'Stempel praktis untuk kebutuhan usaha dan kantor.',
        'harga' => 'Mulai Rp20.000',
        'gambar' => 'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?auto=format&fit=crop&w=900&q=85'
    ]
];

$whatsapp = 'https://wa.me/6288291614900';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahira Printing</title>

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
<header class="bg-black text-white py-4 px-6 md:px-12 border-b border-gray-800">
    <div class="max-w-7xl mx-auto flex justify-between items-center gap-4">

        <a href="dashboard.php" class="flex items-center shrink-0">
            <img
                src="../assets/images/logo.JPEG"
                alt="Mahira Printing"
                class="h-8 md:h-10 object-contain">
        </a>

        <nav id="navMenu"
             class="hidden md:flex items-center gap-5 lg:gap-8 text-xs font-semibold text-gray-300">
            <a href="dashboard.php" class="hover:text-brand-yellow transition">
                Beranda
            </a>

            <a href="#produk" class="hover:text-brand-yellow transition">
                Produk
            </a>

            <a href="jasa.php" class="hover:text-brand-yellow transition">
                Jasa
            </a>

            <a href="portofolio.php" class="hover:text-brand-yellow transition">
                Portofolio
            </a>

            <a href="#tentang" class="hover:text-brand-yellow transition">
                Tentang
            </a>

            <a href="review.php" class="hover:text-brand-yellow transition">
                Review
            </a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="<?= htmlspecialchars($whatsapp) ?>"
               target="_blank"
               rel="noopener noreferrer"
               class="bg-brand-yellow text-black text-xs font-extrabold px-4 md:px-5 py-2.5 rounded-lg hover:bg-yellow-400 transition whitespace-nowrap">
                Pesan Sekarang
            </a>

            <button id="menuToggle"
                    type="button"
                    class="md:hidden text-white border border-gray-700 rounded px-3 py-2"
                    aria-label="Buka menu"
                    aria-expanded="false">
                ☰
            </button>
        </div>
    </div>

    <nav id="mobileMenu"
         class="hidden md:hidden max-w-7xl mx-auto pt-4 text-sm text-gray-300">
        <a href="dashboard.php" class="block py-3 border-b border-gray-800">Beranda</a>
        <a href="#produk" class="block py-3 border-b border-gray-800">Produk</a>
        <a href="jasa.php" class="block py-3 border-b border-gray-800">Jasa</a>
        <a href="portofolio.php" class="block py-3 border-b border-gray-800">Portofolio</a>
        <a href="#tentang" class="block py-3 border-b border-gray-800">Tentang</a>
        <a href="review.php" class="block py-3">Review</a>
    </nav>
</header>

<main class="flex-1">

    <!-- HERO -->
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

                    <div class="flex flex-wrap gap-3">
                        <a href="#produk"
                           class="bg-brand-yellow text-black px-5 py-3 rounded-md text-xs font-extrabold hover:bg-yellow-400 transition">
                            Lihat Produk ↗
                        </a>

                        <a href="jasa.php"
                           class="border border-gray-600 text-white px-5 py-3 rounded-md text-xs font-bold hover:border-brand-yellow hover:text-brand-yellow transition">
                            Pelajari Layanan
                        </a>
                    </div>
                </div>

                <!-- Ilustrasi cetak -->
                <div class="relative min-h-[280px] md:min-h-[330px] flex items-center justify-center">

                    <div class="absolute w-64 h-64 bg-yellow-900/20 rounded-full blur-3xl"></div>

                    <div class="absolute left-2 top-8 md:left-5 bg-gray-950 border border-yellow-900 p-3 w-24 rotate-[-10deg] z-10">
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

                    <div class="absolute right-1 bottom-7 bg-gray-950 border border-yellow-900 p-3 w-20 rotate-[10deg] z-10">
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
    <section class="bg-brand-yellow text-black">
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

    <!-- LAYANAN -->
    <section id="layanan" class="bg-brand-bg text-black py-14 md:py-20">
        <div class="max-w-7xl mx-auto px-6 md:px-12">

            <div class="flex flex-col md:flex-row justify-between md:items-end gap-5 mb-9">
                <div>
                    <p class="text-xs text-yellow-700 font-bold tracking-[3px] uppercase mb-3">
                        Layanan Kami
                    </p>

                    <h2 class="text-3xl md:text-4xl font-black leading-tight">
                        Semua Kebutuhan Cetak<br>
                        Ada di Sini.
                    </h2>

                    <p class="text-gray-600 text-xs leading-6 max-w-lg mt-3">
                        Dari kebutuhan personal sampai bisnis, temukan layanan
                        cetak yang sesuai dengan ide dan kebutuhanmu.
                    </p>
                </div>

                <a href="jasa.php"
                   class="text-xs font-bold border-b border-black pb-1 self-start md:self-auto">
                    Tanya Semua Layanan ↗
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

                <div class="bg-[#e9e4d7] p-5 min-h-48">
                    <div class="w-9 h-9 border border-black flex items-center justify-center mb-5">▧</div>
                    <h3 class="font-black text-sm mb-2">Printing & Stationery</h3>
                    <p class="text-xs text-gray-600 leading-5">
                        Cetak dokumen, kartu nama, brosur, dan kebutuhan kantor.
                    </p>
                </div>

                <div class="bg-black text-white p-5 min-h-48">
                    <div class="w-9 h-9 border border-white flex items-center justify-center mb-5">✳</div>
                    <h3 class="font-black text-sm mb-2">Design & Branding</h3>
                    <p class="text-xs text-gray-400 leading-5">
                        Bantu wujudkan identitas visual untuk brand kamu.
                    </p>
                </div>

                <div class="bg-[#e9e4d7] p-5 min-h-48">
                    <div class="w-9 h-9 border border-black flex items-center justify-center mb-5">▣</div>
                    <h3 class="font-black text-sm mb-2">Custom Merchandise</h3>
                    <p class="text-xs text-gray-600 leading-5">
                        Produk custom untuk hadiah, komunitas, dan promosi.
                    </p>
                </div>

                <div class="bg-brand-yellow text-black p-5 min-h-48">
                    <div class="w-9 h-9 border border-black flex items-center justify-center mb-5">✦</div>
                    <h3 class="font-black text-sm mb-2">Produk & Lainnya</h3>
                    <p class="text-xs leading-5">
                        Pilihan produk cetak sesuai kebutuhanmu.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- PRODUK -->
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
                        Cari produk yang kamu butuhkan, lalu konsultasikan desain
                        dan jumlah cetaknya dengan kami.
                    </p>
                </div>

                <a href="jasa.php"
                   class="text-xs font-bold border-b border-black pb-1 self-start md:self-auto">
                    Lihat Semua Produk ↗
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-5">

                <?php foreach ($produk as $item): ?>

                    <article class="product-card bg-white border border-[#e2ddcf] overflow-hidden">

                        <div class="product-image relative h-56 md:h-64 overflow-hidden bg-[#e7e0cc]">
                            <img
                                src="<?= htmlspecialchars($item['gambar'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8') ?>"
                                class="w-full h-full object-cover"
                                loading="lazy">

                            <span class="absolute top-3 left-3 bg-brand-yellow text-black text-[9px] font-black tracking-widest px-3 py-2">
                                <?= htmlspecialchars($item['kategori'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>

                        <div class="p-5">

                            <p class="text-[9px] text-yellow-700 font-bold tracking-widest mb-2">
                                <?= htmlspecialchars($item['kategori'], ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <h3 class="font-black text-lg mb-2">
                                <?= htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8') ?>
                            </h3>

                            <p class="text-gray-600 text-xs leading-5 min-h-9">
                                <?= htmlspecialchars($item['deskripsi'], ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <div class="flex justify-between items-center border-t border-gray-200 mt-4 pt-4">
                                <span class="font-bold text-xs">
                                    <?= htmlspecialchars($item['harga'], ENT_QUOTES, 'UTF-8') ?>
                                </span>

                                <a
                                    href="<?= htmlspecialchars($whatsapp . '?text=' . rawurlencode('Halo Mahira Printing, saya ingin bertanya tentang ' . $item['nama'] . '.'), ENT_QUOTES, 'UTF-8') ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="bg-brand-yellow w-8 h-8 flex items-center justify-center font-bold hover:bg-yellow-400 transition"
                                    aria-label="Pesan <?= htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8') ?>">
                                    ↗
                                </a>
                            </div>

                        </div>
                    </article>

                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <!-- CTA -->
    <section id="tentang" class="bg-brand-yellow text-black py-10 md:py-12">
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

            <a
                href="<?= htmlspecialchars($whatsapp . '?text=' . rawurlencode('Halo Mahira Printing, saya ingin bertanya mengenai layanan percetakan.'), ENT_QUOTES, 'UTF-8') ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="bg-black text-brand-yellow text-xs font-extrabold px-6 py-4 rounded-md hover:bg-gray-900 transition text-center">
                Hubungi MAHIRA ↗
            </a>

        </div>
    </section>

</main>

<!-- FOOTER SESUAI FIGMA -->
<footer class="bg-black text-white border-t border-gray-900 py-6 px-6 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4 text-xs">

        <div class="flex items-center gap-4">
            <img
                src="../assets/images/logo.JPEG"
                alt="Mahira Printing"
                class="h-7 md:h-8 object-contain">

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