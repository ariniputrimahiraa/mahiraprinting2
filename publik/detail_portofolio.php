<?php 
require_once '../config/database.php'; 
include '../includes/header.php'; 

$stmt = $pdo->query("SELECT * FROM portfolio ORDER BY id_portfolio DESC");
$data_portfolio = $stmt->fetchAll();
?>

<!-- Load Tailwind CSS & Custom Color Brand -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          'brand-yellow': '#FFD028',
        }
      }
    }
  }
</script>

<main class="flex-1 max-w-7xl mx-auto px-4 py-12 w-full bg-white min-h-screen">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-extrabold text-black mb-2">Hasil Pekerjaan & Portofolio</h2>
        <p class="text-xs text-gray-500">Lihat berbagai hasil cetak dan proyek desain yang telah kami selesaikan</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php if (count($data_portfolio) > 0): ?>
            <?php foreach ($data_portfolio as $row): ?>
                <a href="detail_portfolio.php?id=<?= $row['id_portfolio'] ?>" class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-lg transition block group">
                    <div class="h-52 overflow-hidden bg-gray-100 relative">
                        <img src="<?= htmlspecialchars($row['gambar']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-5 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider block mb-1"><?= htmlspecialchars($row['kategori'] ?? 'Portofolio') ?></span>
                            <h4 class="font-bold text-sm text-black group-hover:text-amber-600 transition"><?= htmlspecialchars($row['judul']) ?></h4>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-black text-brand-yellow flex items-center justify-center text-sm font-bold shadow-sm group-hover:bg-brand-yellow group-hover:text-black transition">→</div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-3 text-center py-16 text-gray-400 bg-gray-50 rounded-2xl border border-dashed border-gray-200">Belum ada portofolio tersedia.</div>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>