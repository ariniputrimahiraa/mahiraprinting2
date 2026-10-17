<?php 
require_once '../config/database.php'; 
include '../includes/header.php'; 

// Filter Kategori
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

try {
    if (!empty($kategori_filter)) {
        $stmt = $pdo->prepare("SELECT * FROM jasa WHERE status = 'Aktif' AND (kategori LIKE :kategori OR nama_jasa LIKE :kategori) ORDER BY id_jasa DESC");
        $stmt->execute(['kategori' => '%' . $kategori_filter . '%']);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM jasa WHERE status = 'Aktif' ORDER BY id_jasa DESC");
        $stmt->execute();
    }
    $data_jasa = $stmt->fetchAll();
} catch (PDOException $e) {
    $stmt = $pdo->prepare("SELECT * FROM jasa ORDER BY id_jasa DESC");
    $stmt->execute();
    $data_jasa = $stmt->fetchAll();
}
?>

<!-- CDN Tailwind CSS & Custom Config -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          'brand-yellow': '#FFD028',
          'brand-bg': '#F3F0E6',
          'figma-box': '#EEF1F6',
          'figma-stroke': '#E5E7EB',
        }
      }
    }
  }
</script>

<main class="flex-1 bg-brand-bg min-h-screen font-sans">
    <!-- 1. Hero Banner Hitam (Full Width) -->
    <section class="bg-black text-white py-16 text-center px-4 w-full">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl md:text-5xl font-black mb-4 leading-tight">
                Solusi Lengkap Cetak <br><span class="text-brand-yellow">Desain Kreatif.</span>
            </h1>
            <p class="text-gray-300 text-xs md:text-sm max-w-xl mx-auto font-normal">
                Jelajahi berbagai kebutuhan percetakan, custom, promosi, souvenir, dan desain dari Mahira Printing
            </p>
        </div>
    </section>

    <!-- 2. Filter Buttons & Cards -->
    <section class="max-w-7xl mx-auto px-4 pt-10 pb-8">
        <div class="flex flex-wrap justify-center gap-2 md:gap-3 mb-10">
            <a href="jasa.php" class="px-5 py-2.5 rounded-lg text-xs font-semibold transition <?= empty($kategori_filter) ? 'bg-brand-yellow text-black font-bold' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' ?>">Semua Layanan</a>
            <a href="jasa.php?kategori=Desain" class="px-5 py-2.5 rounded-lg text-xs font-semibold transition <?= $kategori_filter == 'Desain' ? 'bg-brand-yellow text-black font-bold' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' ?>">Jasa Desain</a>
            <a href="jasa.php?kategori=Cetak" class="px-5 py-2.5 rounded-lg text-xs font-semibold transition <?= $kategori_filter == 'Cetak' ? 'bg-brand-yellow text-black font-bold' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' ?>">Cetak & Sablon</a>
            <a href="jasa.php?kategori=Advertising" class="px-5 py-2.5 rounded-lg text-xs font-semibold transition <?= $kategori_filter == 'Advertising' ? 'bg-brand-yellow text-black font-bold' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' ?>">Advertising</a>
            <a href="jasa.php?kategori=Souvenir" class="px-5 py-2.5 rounded-lg text-xs font-semibold transition <?= $kategori_filter == 'Souvenir' ? 'bg-brand-yellow text-black font-bold' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' ?>">Custom & Souvenir</a>
            <a href="jasa.php?kategori=Administrasi" class="px-5 py-2.5 rounded-lg text-xs font-semibold transition <?= $kategori_filter == 'Administrasi' ? 'bg-brand-yellow text-black font-bold' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' ?>">Administrasi & Kertas</a>
        </div>

        <!-- Grid Kartu Jasa -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            <?php if (count($data_jasa) > 0): ?>
                <?php foreach ($data_jasa as $row): ?>
                    <div class="bg-brand-yellow p-6 rounded-2xl flex flex-col justify-between shadow-xs">
                        <div>
                            <!-- Header Badge & Icon -->
                            <div class="flex justify-between items-center mb-4">
                                <div class="w-9 h-9 rounded-xl bg-white/90 flex items-center justify-center text-black">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                                </div>
                                <span class="bg-white/80 text-black text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-wider">
                                    <?= htmlspecialchars($row['kategori'] ?? 'Layanan') ?>
                                </span>
                            </div>

                            <h3 class="text-lg font-black mb-2 text-black leading-snug"><?= htmlspecialchars($row['nama_jasa']) ?></h3>
                            <p class="text-[11px] text-gray-900 leading-relaxed mb-4 font-medium"><?= htmlspecialchars($row['deskripsi']) ?></p>

                            <!-- Poin Checklist -->
                            <div class="space-y-1.5 mb-6 text-[11px] text-gray-900 font-medium">
                                <div class="flex items-center gap-1.5"><span>✓</span> <span>Kualitas cetak presisi & tajam</span></div>
                                <div class="flex items-center gap-1.5"><span>✓</span> <span>Pilihan bahan lengkap & premium</span></div>
                                <div class="flex items-center gap-1.5"><span>✓</span> <span>Konsultasi gratis via WhatsApp</span></div>
                            </div>
                        </div>

                        <!-- Tombol Putih Konsultasi -->
                        <a href="https://wa.me/" target="_blank" class="w-full bg-white text-black font-bold text-xs py-2.5 rounded-xl text-center hover:bg-gray-50 transition block shadow-xs">
                            Konsultasi Jasa →
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-12 text-gray-500 bg-white/50 rounded-2xl border border-dashed border-gray-300">Belum ada data jasa.</div>
            <?php endif; ?>
        </div>

        <!-- 3. Bagian "Cara Kerja Pemesanan Jasa" Presisi Figma (Radius 0, Border #E5E7EB, Fill #EEF1F6) -->
        <div class="bg-[#EEF1F6] border border-[#E5E7EB] p-8 md:p-10 rounded-none mb-16">
            <span class="text-[10px] uppercase font-bold text-gray-500 tracking-wider block mb-1">4 LANGKAH PRAKTIS</span>
            <h3 class="text-2xl font-bold mb-8 text-[#1E293B]">Cara Kerja Pemesanan Jasa</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-6 rounded-2xl bg-white shadow-xs">
                    <h4 class="font-bold text-sm text-black mb-3">1. Konsultasi & Brief</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Diskusikan kebutuhan & file desain via WhatsApp.</p>
                </div>
                <div class="p-6 rounded-2xl bg-white shadow-xs">
                    <h4 class="font-bold text-sm text-black mb-3">2. Estimasi & Mockup</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Penawaran harga terbaik dan preview produk.</p>
                </div>
                <div class="p-6 rounded-2xl bg-white shadow-xs">
                    <h4 class="font-bold text-sm text-black mb-3">3. Proses Produksi</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Pencetakan presisi dengan quality control ketat.</p>
                </div>
                <div class="p-6 rounded-2xl bg-white shadow-xs">
                    <h4 class="font-bold text-sm text-black mb-3">4. Finishing & Kirim</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Dikemas aman dan siap dikirim ke lokasi Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Banner Hitam Bottom "Punya kebutuhan cetak?" (Full Width Persegi / Siku) -->
    <section class="bg-black text-white py-16 px-6 md:px-16 w-full border-t border-gray-800">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <span class="text-[11px] font-bold text-brand-yellow tracking-widest uppercase block mb-2">LAYANAN KAMI</span>
                <h2 class="text-2xl md:text-4xl font-extrabold leading-tight">
                    Punya kebutuhan cetak?<br>
                    <span class="text-brand-yellow">Mari kita kerjakan.</span>
                </h2>
            </div>
            <div>
                <a href="https://wa.me/" target="_blank" class="inline-block bg-brand-yellow text-black font-extrabold px-8 py-3.5 rounded-xl hover:bg-yellow-400 transition text-xs shadow-md">
                    Hubungi Mahira
                </a>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>