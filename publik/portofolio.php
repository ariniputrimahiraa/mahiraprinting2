<?php 
require_once '../config/database.php'; 
include '../includes/header.php'; 

// Mengambil data portofolio dari tabel `portfolio` sesuai database
try {
    $stmt =$pdo->query("SELECT * FROM portfolio ORDER BY id_portfolio DESC");
    $data_portofolio =$stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Jika query error atau tabel belum ada
    $data_portofolio = [];
}
?>

<!-- Load Tailwind CSS & Custom Config -->
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

<main class="flex-1 w-full bg-[#F3F0E6] min-h-screen">

    <!-- Header Banner Portofolio -->
    <section class="bg-black text-white py-14 text-center px-4 border-t border-gray-800">
        <h1 class="text-2xl md:text-3xl font-extrabold mb-2">Portofolio & Hasil Cetak</h1>
        <p class="text-xs text-gray-400 max-w-xl mx-auto">
            Kumpulan hasil pengerjaan produk percetakan dan desain terbaik dari Mahira Printing untuk pelanggan kami.
        </p>
    </section>

    <!-- Grid Kartu Portofolio -->
    <section class="max-w-7xl mx-auto px-6 md:px-12 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php if (count($data_portofolio) > 0): ?>
                <?php foreach ($data_portofolio as$p): 
                    $judul =$p['judul'] ?? 'Hasil Cetak Mahira';
                    // Menyesuaikan folder penyimpanan gambar dari uploads database
                    $gambar = !empty($p['gambar']) ? '../uploads/' .$p['gambar'] : '../assets/images/logo.JPEG';
                    $kategori = isset($p['tahun']) && !empty($p['tahun']) ? 'Tahun ' .$p['tahun'] : 'Percetakan';
                    $deskripsi =$p['deskripsi'] ?? 'Hasil pengerjaan cetak berkualitas presisi tinggi dari Mahira Printing.';
                    $id =$p['id_portfolio'] ?? 1;
                ?>
                    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            <img src="<?= htmlspecialchars($gambar) ?>" alt="<?= htmlspecialchars($judul) ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="p-5">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 block">
                                <?= htmlspecialchars($kategori) ?>
                            </span>
                            <h3 class="font-extrabold text-sm text-black mb-2">
                                <?= htmlspecialchars($judul) ?>
                            </h3>
                            <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">
                                <?= htmlspecialchars($deskripsi) ?>
                            </p>
                        </div>
                        <div class="px-5 pb-5 pt-0">
                            <a href="detail_portofolio.php?id=<?= $id ?>" class="inline-block w-full text-center bg-[#F3F0E6] text-black font-bold text-xs py-2 rounded-lg hover:bg-brand-yellow transition">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Tampilan Contoh / Dummy jika database portofolio masih kosong -->
                <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm flex flex-col justify-between">
                    <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-400 font-bold text-xs">
                        [Foto Brosur / Banner]
                    </div>
                    <div class="p-5">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 block">Digital Printing</span>
                        <h3 class="font-extrabold text-sm text-black mb-2">Cetak Banner & Spanduk High Res</h3>
                        <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">Cetak spanduk bahan flexy tajam, tahan cuaca, dan warna pekat.</p>
                    </div>
                    <div class="px-5 pb-5 pt-0">
                        <a href="https://wa.me/6288291614900" target="_blank" class="inline-block w-full text-center bg-[#F3F0E6] text-black font-bold text-xs py-2 rounded-lg hover:bg-brand-yellow transition">
                            Tanyakan Produk Ini →
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm flex flex-col justify-between">
                    <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-400 font-bold text-xs">
                        [Foto Stiker & Label]
                    </div>
                    <div class="p-5">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 block">Packaging & Label</span>
                        <h3 class="font-extrabold text-sm text-black mb-2">Stiker Label Kemasan UMKM</h3>
                        <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">Stiker Chromo & Vinyl anti air dengan potong presisi (*kiss cut*).</p>
                    </div>
                    <div class="px-5 pb-5 pt-0">
                        <a href="https://wa.me/6288291614900" target="_blank" class="inline-block w-full text-center bg-[#F3F0E6] text-black font-bold text-xs py-2 rounded-lg hover:bg-brand-yellow transition">
                            Tanyakan Produk Ini →
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm flex flex-col justify-between">
                    <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-400 font-bold text-xs">
                        [Foto Kartu Nama / Nota]
                    </div>
                    <div class="p-5">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 block">Perlengkapan Usaha</span>
                        <h3 class="font-extrabold text-sm text-black mb-2">Nota NCR & Kartu Nama Premium</h3>
                        <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">Nota bertingkat langsung tembus dan kartu nama bahan Art Paper tebal.</p>
                    </div>
                    <div class="px-5 pb-5 pt-0">
                        <a href="https://wa.me/6288291614900" target="_blank" class="inline-block w-full text-center bg-[#F3F0E6] text-black font-bold text-xs py-2 rounded-lg hover:bg-brand-yellow transition">
                            Tanyakan Produk Ini →
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Banner CTA Hitam Bawah (Presisi Figma) -->
    <section class="bg-black text-white py-12 md:py-16 px-6 md:px-16 w-full border-t border-gray-900">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <span class="text-brand-yellow text-xs font-black uppercase tracking-widest block mb-2">
                    LAYANAN KAMI
                </span>
                <h2 class="text-2xl md:text-4xl font-extrabold leading-tight">
                    Punya kebutuhan cetak?<br>
                    <span class="text-brand-yellow">Mari kita kerjakan.</span>
                </h2>
            </div>
            <div>
                <a href="https://wa.me/6288291614900?text=Halo%20Mahira%20Printing!%20Saya%20punya%20kebutuhan%20cetak%20dan%20ingin%20konsultasi." 
                   target="_blank" 
                   class="inline-block bg-brand-yellow hover:bg-yellow-400 text-black font-extrabold text-sm px-8 py-3.5 rounded-2xl transition duration-200 shadow-md">
                    Hubungi Mahira
                </a>
            </div>
        </div>
    </section>

</main>

<!-- Footer Resmi -->
<?php include '../includes/footer.php'; ?>