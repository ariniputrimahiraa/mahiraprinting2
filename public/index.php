
<?php
require_once __DIR__ . '/../config/database.php';

function h($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$db = isset($conn) && $conn instanceof mysqli
    ? $conn
    : (isset($koneksi) && $koneksi instanceof mysqli ? $koneksi : null);

function ambilData($db, $sql) {
    $data = array();

    if ($db) {
        $hasil = $db->query($sql);

        if ($hasil) {
            while ($row = $hasil->fetch_assoc()) {
                $data[] = $row;
            }
        }
    }

    return $data;
}


$jasa = ambilData($db, "SELECT * FROM jasa LIMIT 4");

$produk = ambilData($db, "SELECT * FROM produk LIMIT 3");

$portfolio = ambilData($db, "SELECT * FROM portfolio LIMIT 3");

$whatsapp = "6288291614900";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mahira Printing | Cetak Apa Saja, Lebih Mudah</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<header class="navbar">
    <a href="#home" class="logo">
        <span class="logo-mark">M</span>
        <span class="logo-name">
            MAHIRA
            <small>PRINTING & CREATIVE</small>
        </span>
    </a>

    <button class="menu-toggle" id="menuToggle"
            aria-label="Buka menu" aria-expanded="false">
        ☰
    </button>

    <nav class="nav-menu" id="navMenu">
        <a href="#home">Beranda</a>
        <a href="#jasa">Jasa</a>
        <a href="#produk">Produk</a>
        <a href="#portfolio">Portfolio</a>
        <a href="#tentang">Tentang</a>
        <a href="#kontak" class="nav-cta">HUBUNGI KAMI</a>
    </nav>
</header>

<!-- HERO -->
<main>
<section class="hero" id="home">
    <div class="container hero-grid">
        <div class="hero-copy">
            <p class="eyebrow">PERCETAKAN & KREATIVITAS</p>

            <h1>Cetak Apa Saja,<br>
                <span>Lebih Mudah.</span>
            </h1>

            <p class="hero-description">
                Dari kebutuhan bisnis hingga ide kreatif,
                Mahira siap membantu mewujudkannya dengan
                hasil berkualitas dan proses yang mudah.
            </p>

            <div class="hero-actions">
                <a href="#produk" class="button button-yellow">
                    Lihat Produk ↗
                </a>
                <a href="#jasa" class="button button-outline">
                    Jelajahi Jasa
                </a>
            </div>
        </div>

        <!-- Ilustrasi produk cetak -->
        <div class="hero-art" aria-label="Ilustrasi produk percetakan">
            <div class="hero-ring"></div>

            <div class="print-sheet sheet-back">
                <span class="sheet-logo">M.</span>
                <div class="sheet-rule"></div>
                <div class="sheet-rule short"></div>
            </div>

            <div class="print-sheet sheet-front">
                <div class="sheet-brand">
                    <span class="mini-mark">M</span>
                    <b>MAHIRA</b>
                </div>

                <div class="sheet-title">
                    PRINTING<br>& CREATIVE
                </div>

                <div class="sheet-yellow-line"></div>
                <div class="sheet-rule"></div>
                <div class="sheet-rule short"></div>
            </div>

            <div class="art-tag">
                <span class="mini-mark">M</span>
                <span><b>MAHIRA</b><small>CREATIVE PRINT</small></span>
            </div>
        </div>
    </div>
</section>

<!-- KEUNGGULAN -->
<section class="benefits">
    <div class="container benefits-grid">
        <div class="benefit">
            <b>01</b><span>Kualitas Terjaga</span>
        </div>
        <div class="benefit">
            <b>02</b><span>Proses Cepat</span>
        </div>
        <div class="benefit">
            <b>03</b><span>Desain Fleksibel</span>
        </div>
        <div class="benefit">
            <b>04</b><span>Harga Bersahabat</span>
        </div>
    </div>
</section>

<!-- JASA -->
<section class="section" id="jasa">
    <div class="container">
        <div class="section-heading">
            <div>
                <p class="eyebrow">LAYANAN KAMI</p>
                <h2>Semua Kebutuhan Cetak<br>Ada di Sini.</h2>
                <p class="section-description">
                    Berbagai solusi percetakan untuk usaha,
                    acara, dan kebutuhan personal Anda.
                </p>
            </div>

            <a href="../jasa.php" class="text-link">
                Lihat semua jasa ↗
            </a>
        </div>

        <div class="service-grid">
            <?php
            $warnaJasa = array('cream', 'dark', 'white', 'yellow');

            $jasaContoh = array(
                array(
                    'nama' => 'Cetak & Sablon',
                    'deskripsi' => 'Cetak kebutuhan promosi dan pakaian.'
                ),
                array(
                    'nama' => 'Printing & Packaging',
                    'deskripsi' => 'Solusi cetak untuk kemasan bisnis.'
                ),
                array(
                    'nama' => 'Custom & Merchandise',
                    'deskripsi' => 'Produk custom untuk acara dan komunitas.'
                ),
                array(
                    'nama' => 'Nota & Dokumen',
                    'deskripsi' => 'Cetak nota dan kebutuhan administrasi.'
                )
            );

            foreach ($jasaContoh as $i => $contoh):
                $item = isset($jasa[$i]) ? $jasa[$i] : array();
                $nama = $item['nama_jasa'] ?? $contoh['nama'];
                $deskripsi = $item['deskripsi'] ?? $contoh['deskripsi'];
            ?>
                <article class="service-card <?= $warnaJasa[$i] ?>">
                    <span class="service-number">
                        <?= sprintf('%02d', $i + 1) ?>
                    </span>

                    <div class="service-icon">
                        <?= array('▧', '▤', '✳', '▣')[$i] ?>
                    </div>

                    <h3><?= h($nama) ?></h3>
                    <p><?= h($deskripsi) ?></p>

                    <a href="../jasa.php">Selengkapnya ↗</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PRODUK -->
<section class="section products-section" id="produk">
    <div class="container">
        <div class="section-heading">
            <div>
                <p class="eyebrow">PILIHAN PRODUK</p>
                <h2>Pilihan Produk Mahira.</h2>
                <p class="section-description">
                    Temukan produk yang sesuai dengan kebutuhan Anda.
                </p>
            </div>

            <a href="../produk.php" class="text-link">
                Lihat semua produk ↗
            </a>
        </div>

        <div class="product-grid">
            <?php
            $produkContoh = array(
                array('nama_produk' => 'Sticker Premium',
                      'deskripsi' => 'Sticker custom berkualitas.'),
                array('nama_produk' => 'Kaos Custom',
                      'deskripsi' => 'Kaos custom untuk berbagai kebutuhan.'),
                array('nama_produk' => 'Stempel',
                      'deskripsi' => 'Stempel untuk usaha dan kantor.')
            );

            foreach ($produkContoh as $i => $contoh):
                $item = isset($produk[$i]) ? $produk[$i] : array();
                $nama = $item['nama_produk'] ?? $contoh['nama_produk'];
                $deskripsi = $item['deskripsi'] ?? $contoh['deskripsi'];
                $gambar = $item['gambar'] ?? '';
            ?>
                <article class="product-card">
                    <div class="product-visual visual-<?= $i ?>">
                        <?php if ($gambar !== ''): ?>
                            <img src="<?= h($gambar) ?>"
                                 alt="<?= h($nama) ?>"
                                 loading="lazy">
                        <?php else: ?>
                            <span class="product-label">MAHIRA PRINT</span>

                            <?php if ($i === 0): ?>
                                <div class="sticker-art">
                                    <b>STICKER<br>PREMIUM</b>
                                    <span>● ● ● ●<br>● ● ● ●</span>
                                </div>
                            <?php elseif ($i === 1): ?>
                                <div class="shirt-art">
                                    <span>✳</span>
                                    <b>YOUR<br>DESIGN</b>
                                </div>
                            <?php else: ?>
                                <div class="stamp-art">
                                    <span></span>
                                    <b>MAHIRA</b>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="product-details">
                        <div>
                            <h3><?= h($nama) ?></h3>
                            <p><?= h($deskripsi) ?></p>
                        </div>

                        <a class="product-arrow"
                           target="_blank" rel="noopener"
                           href="https://wa.me/<?= h($whatsapp) ?>?text=<?= rawurlencode('Halo Mahira, saya ingin bertanya tentang produk ' . $nama) ?>"
                           aria-label="Tanyakan <?= h($nama) ?>">
                            ↗
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PORTFOLIO -->
<section class="section portfolio-section" id="portfolio">
    <div class="container portfolio-grid">
        <div class="portfolio-intro">
            <p class="eyebrow">PORTFOLIO KAMI</p>
            <h2>Ide Anda,<br>Kami Wujudkan.</h2>
            <p class="section-description">
                Lihat hasil karya dan proyek percetakan Mahira.
            </p>

            <a href="../portfolio.php" class="button button-dark">
                Lihat Portfolio ↗
            </a>
        </div>

        <div class="portfolio-list">
            <?php if (!empty($portfolio)): ?>
                <?php foreach ($portfolio as $item): ?>
                    <article class="portfolio-item">
                        <div class="portfolio-thumbnail">
                            <?= h(substr($item['judul'] ?? 'M', 0, 2)) ?>
                        </div>

                        <div class="portfolio-info">
                            <h3><?= h($item['judul'] ?? 'Portfolio Mahira') ?></h3>
                            <p><?= h($item['deskripsi'] ?? '') ?></p>
                        </div>

                        <span>↗</span>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <article class="portfolio-item">
                    <div class="portfolio-thumbnail">01</div>
                    <div class="portfolio-info">
                        <h3>Karya Percetakan</h3>
                        <p>Hasil cetak pilihan Mahira.</p>
                    </div>
                </article>

                <article class="portfolio-item">
                    <div class="portfolio-thumbnail">02</div>
                    <div class="portfolio-info">
                        <h3>Proyek Custom</h3>
                        <p>Kreasi sesuai kebutuhan pelanggan.</p>
                    </div>
                </article>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- TENTANG DAN KONTAK -->
<section class="contact-section" id="tentang">
    <div class="container contact-content">
        <div>
            <p class="eyebrow">PUNYA KEBUTUHAN CETAK?</p>
            <h2>Mari kita kerjakan.</h2>
            <p>
                Punya ide atau kebutuhan cetak?
                Konsultasikan bersama tim Mahira.
            </p>
        </div>

        <a class="button button-yellow"
           id="kontak"
           target="_blank" rel="noopener"
           href="https://wa.me/<?= h($whatsapp) ?>?text=<?= rawurlencode('Halo Mahira, saya ingin konsultasi kebutuhan cetak.') ?>">
            Hubungi Kami ↗
        </a>
    </div>
</section>
</main>

<!-- FOOTER -->
<footer class="footer">
    <div class="container footer-content">
        <a href="#home" class="logo">
            <span class="logo-mark">M</span>
            <span class="logo-name">
                MAHIRA
                <small>PRINTING & CREATIVE</small>
            </span>
        </a>

        <p>Solusi cetak untuk ide yang lebih berarti.</p>
        <span>© <?= date('Y') ?> Mahira Printing.</span>
    </div>
</footer>

<script src="assets/js/script.js"></script>
</body>
</html>