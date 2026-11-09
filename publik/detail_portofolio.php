<?php 
require_once '../config/database.php'; 
include '../includes/header.php'; 

// Mengambil ID portofolio dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM portfolio WHERE id_portfolio = ?");
    $stmt->execute([$id]);
    $portfolio = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $portfolio = null;
}

// Jika data tidak ditemukan di database
if (!$portfolio) {
    echo "<main class='flex-1 w-full bg-[#F3F0E6] min-h-screen flex items-center justify-center p-6'>
            <div class='bg-white p-8 rounded-2xl shadow-sm border border-gray-200 text-center max-w-md w-full'>
                <h2 class='text-xl font-extrabold text-black mb-2'>Portofolio Tidak Ditemukan</h2>
                <p class='text-xs text-gray-500 mb-6'>Maaf, data portofolio yang Anda cari tidak tersedia atau telah dihapus.</p>
                <a href='portfolio.php' class='inline-block bg-black text-white hover:bg-brand-yellow hover:text-black font-extrabold text-xs px-6 py-3 rounded-xl transition duration-200'>
                    ← Kembali ke Portofolio
                </a>
            </div>
          </main>";
    include '../includes/footer.php';
    exit;
}

// Menyiapkan path gambar utama dari folder uploads
$gambar_utama = !empty($portfolio['gambar']) ? '../uploads/' . $portfolio['gambar'] : '../assets/images/logo.JPEG';

// Menyiapkan deskripsi proyek
$deskripsi_full = $portfolio['deskripsi'] ?? 'Proyek pengerjaan cetak berkualitas presisi tinggi dari Mahira Printing.';
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

<main class="flex-1 w-full bg-[#F3F0E6] min-h-screen py-10 md:py-14">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        
        <!-- Judul Portofolio (H1 Utama) -->
        <h1 class="text-2xl md:text-4xl font-extrabold text-black mb-8 tracking-tight">
            <?= htmlspecialchars($portfolio['judul']) ?>
        </h1>

        <!-- Grid 2 Kolom Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Kolom Kiri: Hanya Gambar Utama Saja (Tanpa Thumbnail Bawah) -->
            <div class="lg:col-span-5">
                <div class="w-full aspect-square rounded-2xl overflow-hidden bg-gray-200 shadow-sm border border-gray-300">
                    <img src="<?= htmlspecialchars($gambar_utama) ?>" alt="<?= htmlspecialchars($portfolio['judul']) ?>" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Kolom Kanan: Detail Deskripsi Proyek -->
            <div class="lg:col-span-7 space-y-6 pr-0 lg:pr-6 bg-white/60 p-6 md:p-8 rounded-2xl border border-gray-200 shadow-sm">
                
                <div>
                    <h3 class="text-base font-bold text-brand-yellow mb-2 tracking-wide uppercase">
                        Deskripsi Proyek
                    </h3>
                    <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                        <?= nl2br(htmlspecialchars($deskripsi_full)) ?>
                    </p>
                </div>

                <?php if (!empty($portfolio['tahun'])): ?>
                    <div class="pt-4 border-t border-gray-200 text-xs text-gray-500 font-semibold flex items-center gap-2">
                        <span>Tahun Pengerjaan:</span>
                        <span class="text-black font-extrabold"><?= htmlspecialchars($portfolio['tahun']) ?></span>
                    </div>
                <?php endif; ?>

                <div class="pt-2">
                    <a href="https://wa.me/6288291614900?text=Halo%20Mahira%20Printing!%20Saya%20tertarik%20dengan%20proyek%20portofolio:%20<?= urlencode($portfolio['judul']) ?>" 
                       target="_blank" 
                       class="inline-block bg-brand-yellow hover:bg-yellow-400 text-black font-extrabold text-xs px-6 py-3 rounded-xl transition shadow-sm">
                        Tanya Proyek Serupa via WhatsApp →
                    </a>
                </div>

            </div>

        </div>

    </div>
</main>

<?php include '../includes/footer.php'; ?>