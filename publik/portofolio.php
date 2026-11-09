<?php 
require_once '../config/database.php'; 
include '../includes/header.php'; 

// Menangkap keyword pencarian jika ada
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Mengambil data portofolio dari tabel `portfolio` sesuai database mahira_database
try {
    if (!empty($search)) {
        $stmt = $pdo->prepare("SELECT * FROM portfolio WHERE judul LIKE :search OR deskripsi LIKE :search OR tahun LIKE :search ORDER BY id_portfolio DESC");
        $stmt->execute(['search' => '%' . $search . '%']);
    } else {
        $stmt = $pdo->query("SELECT * FROM portfolio ORDER BY id_portfolio DESC");
    }
    $data_portofolio = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $data_portofolio = [];
}
?>

<!-- Load Tailwind CSS & Google Material Icons -->
<script src="https://cdn.tailwindcss.com"></script>
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

<main class="flex-1 w-full bg-[#F3F0E6] min-h-screen flex flex-col font-sans">

    <!-- Header Banner Portofolio -->
    <section class="bg-black text-white py-14 px-6 md:px-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto">
            <span class="text-brand-yellow text-xs font-bold uppercase tracking-widest block mb-2">PORTOFOLIO KAMI</span>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-3">Portofolio & Hasil Cetak</h1>
            <p class="text-gray-400 text-xs md:text-sm max-w-xl leading-relaxed">
                Kumpulan hasil pengerjaan produk percetakan dan desain terbaik dari Mahira Printing untuk pelanggan kami.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-10 w-full flex-grow">

        <!-- Search Bar Profesional -->
        <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-200 mb-10">
            <form action="portofolio.php" method="GET" class="flex flex-col md:flex-row items-center gap-3">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                        <span class="material-symbols-outlined text-xl">search</span>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        value="<?= htmlspecialchars($search) ?>" 
                        placeholder="Cari portofolio berdasarkan judul, deskripsi, atau tahun..." 
                        class="w-full bg-gray-50 border border-gray-200 pl-12 pr-4 py-3.5 rounded-xl text-xs md:text-sm focus:outline-none focus:border-black focus:bg-white text-gray-800 placeholder-gray-400 transition"
                    >
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto bg-black hover:bg-gray-800 text-white font-bold text-xs px-6 py-3.5 rounded-xl transition whitespace-nowrap shadow-sm">
                        Cari Portofolio
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="portofolio.php" class="w-full md:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs px-5 py-3.5 rounded-xl transition text-center">
                            Reset
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Section Heading -->
        <div class="mb-8 flex justify-between items-end">
            <div>
                <span class="text-xs font-bold text-amber-700 uppercase tracking-widest block mb-1">HASIL KERJA NYATA</span>
                <h2 class="text-2xl md:text-3xl font-black text-black">Galeri Portofolio Mahira.</h2>
            </div>
            <?php if (!empty($search)): ?>
                <p class="text-xs text-gray-600">Hasil pencarian untuk: <b>"<?= htmlspecialchars($search) ?>"</b></p>
            <?php endif; ?>
        </div>

        <!-- Grid Kartu Portofolio (Terhubung Database Tabel `portfolio`) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <?php if (count($data_portofolio) > 0): ?>
                <?php foreach ($data_portofolio as $p): 
                    $judul = $p['judul'] ?? 'Hasil Cetak Mahira';
                    // Menyesuaikan folder penyimpanan gambar dari uploads database
                    $gambar = !empty($p['gambar']) ? '../uploads/' . $p['gambar'] : '../assets/images/logo.JPEG';
                    $kategori = isset($p['tahun']) && !empty($p['tahun']) ? 'Tahun ' . $p['tahun'] : 'Percetakan';
                    $deskripsi = $p['deskripsi'] ?? 'Hasil pengerjaan cetak berkualitas presisi tinggi dari Mahira Printing.';
                    $id = $p['id_portfolio'] ?? 1;
                ?>
                    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div class="h-64 bg-gray-100 overflow-hidden relative">
                            <img src="<?= htmlspecialchars($gambar, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover object-center">
                            
                            <!-- Badge Tahun / Kategori -->
                            <div class="absolute top-3 left-3 z-10">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-gray-800 shadow-sm" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(4px); border: 1px solid rgba(0, 0, 0, 0.1);">
                                    <span class="w-2 h-2 rounded-full" style="background-color: #FFD028;"></span>
                                    <?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </div>
                        </div>

                        <div class="p-5">
                            <h3 class="font-black text-base text-black mb-2 line-clamp-1">
                                <?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?>
                            </h3>
                            <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed min-h-[2.5rem]">
                                <?= htmlspecialchars($deskripsi, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                        <div class="px-5 pb-5 pt-0">
                            <a href="detail_portofolio.php?id=<?= $id ?>" class="inline-block w-full text-center bg-black hover:bg-gray-800 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-xs">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Tampilan Jika Database Kosong -->
                <div class="col-span-3 text-center py-16 bg-white border border-gray-200 rounded-2xl shadow-sm">
                    <p class="text-sm text-gray-600 font-bold mb-1">Belum ada portofolio yang tersedia di database.</p>
                    <p class="text-xs text-gray-400">Silakan tambahkan data portofolio melalui panel admin.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Banner CTA Hitam Bawah -->
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
                   rel="noopener noreferrer"
                   class="inline-block bg-brand-yellow hover:bg-yellow-400 text-black font-extrabold text-xs px-8 py-3.5 rounded-xl transition duration-200 shadow-md">
                    Hubungi MAHIRA
                </a>
            </div>
        </div>
    </section>

</main>

<!-- Footer Resmi -->
<?php include '../includes/footer.php'; ?>