<?php 
require_once '../config/database.php'; 
include '../includes/header.php'; 

$stmt = $pdo->query("SELECT * FROM review WHERE status = 'Aktif' ORDER BY id_review DESC");
$data_review = $stmt->fetchAll();
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

<main class="flex-1 w-full bg-white min-h-screen">
    <!-- Banner Ulasan -->
    <section class="bg-black text-white py-14 text-center px-4 border-t border-gray-800">
        <h2 class="text-2xl md:text-3xl font-extrabold mb-2">Apa Kata Mereka Tentang Mahira Printing?</h2>
        <div class="flex justify-center items-center gap-2 text-brand-yellow font-bold text-lg">
            <span>★ ★ ★ ★ ★ 4.9</span>
            <span class="text-gray-400 text-xs font-normal">[Berdasarkan 1,200+ ulasan]</span>
        </div>
    </section>

    <!-- Cards Testimoni & FAQ -->
    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            <?php if (count($data_review) > 0): ?>
                <?php foreach ($data_review as $r): ?>
                    <div class="bg-brand-yellow p-6 rounded-2xl text-black shadow-sm flex flex-col justify-between border border-yellow-300">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center font-bold text-xs">
                                    <?= strtoupper(substr($r['nama'], 0, 2)) ?>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-black"><?= htmlspecialchars($r['nama']) ?></h4>
                                    <span class="text-[10px] text-gray-800 font-medium"><?= $r['tanggal'] ?> (via <?= htmlspecialchars($r['sumber']) ?>)</span>
                                </div>
                            </div>
                            <div class="text-black font-bold mb-2">
                                <?= str_repeat('★ ', intval($r['rating'])) ?>
                            </div>
                            <p class="text-xs text-gray-900 leading-relaxed mb-4 font-medium"><?= htmlspecialchars($r['ulasan']) ?></p>
                        </div>
                        <?php if (!empty($r['link_review'])): ?>
                            <div class="h-28 rounded-xl overflow-hidden border border-yellow-400/50 bg-white shadow-inner">
                                <img src="<?= htmlspecialchars($r['link_review']) ?>" class="w-full h-full object-cover">
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-16 text-gray-400 bg-gray-50 rounded-2xl border border-dashed border-gray-200">Belum ada review pelanggan.</div>
            <?php endif; ?>
        </div>

        <!-- Accordion FAQ -->
        <div class="max-w-3xl mx-auto">
            <h3 class="text-2xl font-extrabold text-center text-black mb-1">Frequently Asked Questions</h3>
            <p class="text-xs text-gray-500 text-center mb-8">Dipercaya oleh ribuan pelanggan untuk berbagai kebutuhan cetak</p>

            <div class="space-y-3" id="faq-accordion">
                <div class="border border-yellow-400 rounded-2xl bg-brand-yellow overflow-hidden shadow-sm">
                    <button onclick="toggleFaq(this)" class="w-full text-left p-4 font-bold text-sm flex justify-between items-center text-black">
                        <span>Pengiriman ke luar daerah menggunakan jasa apa?</span>
                        <span>▲</span>
                    </button>
                    <div class="p-4 pt-0 text-xs text-gray-900 leading-relaxed font-medium border-t border-yellow-300/60">
                        Pengiriman dapat menggunakan J&T Express atau J&T Cargo. Untuk wilayah lokal, pengiriman juga dapat menggunakan jasa ojek online sesuai kesepakatan.
                    </div>
                </div>

                <div class="border border-gray-200 rounded-2xl bg-white overflow-hidden shadow-sm">
                    <button onclick="toggleFaq(this)" class="w-full text-left p-4 font-bold text-sm flex justify-between items-center text-gray-800">
                        <span>Lokasi pengiriman dari mana?</span>
                        <span>▼</span>
                    </button>
                    <div class="p-4 pt-0 text-xs text-gray-600 leading-relaxed hidden">
                        Pengiriman utama dilakukan dari workshop kami di Bondowoso / Jember, Jawa Timur.
                    </div>
                </div>

                <div class="border border-gray-200 rounded-2xl bg-white overflow-hidden shadow-sm">
                    <button onclick="toggleFaq(this)" class="w-full text-left p-4 font-bold text-sm flex justify-between items-center text-gray-800">
                        <span>Berapa lama waktu pengerjaannya?</span>
                        <span>▼</span>
                    </button>
                    <div class="p-4 pt-0 text-xs text-gray-600 leading-relaxed hidden">
                        Waktu pengerjaan bervariasi mulai dari 1-3 hari kerja tergantung jenis pesanan dan kuantitas.
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function toggleFaq(btn) {
        const content = btn.nextElementSibling;
        const arrow = btn.querySelector('span:last-child');
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            arrow.innerText = '▲';
        } else {
            content.classList.add('hidden');
            arrow.innerText = '▼';
        }
    }
</script>

<?php include '../includes/footer.php'; ?>