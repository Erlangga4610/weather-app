<?php
// helper untuk format tanggal ke Indonesia
function tanggalIndo(string $tanggal): string {
    $bulanIndo = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $ts = strtotime($tanggal);
    $tgl = date('j', $ts);
    $bln = $bulanIndo[(int)date('n', $ts)];
    $thn = date('Y', $ts);
    return "$tgl $bln $thn";
}

// Fungsi terjemahan manual yang Anda buat
function cuacaIndo(string $text): string {
    $map = [
        'Sunny' => 'Cerah',
        'Clear' => 'Cerah',
        'Partly cloudy' => 'Cerah Berawan',
        // 'Partly Cloudy' => 'Cerah Berawan', // Tambahan variasi huruf kapital
        'Cloudy' => 'Berawan',
        'Overcast' => 'Mendung',
        'Patchy rain nearby' => 'Hujan ringan di sekitar',
        'Light rain' => 'Hujan ringan',
        'Moderate rain' => 'Hujan sedang',
        'Heavy rain' => 'Hujan lebat',
        'Thunderstorm' => 'Badai petir',
        'Mist' => 'Berkabut',
        'Fog' => 'Kabut',
    ];

    return $map[$text] ?? $text;
}
?>

<section class="bg-white rounded-2xl shadow p-4 md:p-6 mb-6">
    <form method="get" class="grid gap-3 md:grid-cols-6 items-end">
        <div class="md:col-span-4">
            <label class="block text-sm font-medium mb-1 text-slate-700">Kota / Koordinat</label>
            <input type="text" name="q" placeholder="Contoh: Jakarta"
                value="<?= htmlspecialchars($q ?? 'Jakarta') ?>"
                class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500" required />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1 text-slate-700">Durasi (Hari)</label>
            <input type="number" name="days" min="1" max="10" value="<?= (int)($days ?? 3) ?>"
                class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500" />
        </div>
        <div class="md:col-span-6 flex gap-2">
            <button type="submit" class="px-6 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Cari</button>
            <a href="?" class="px-6 py-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition">Atur Ulang</a>
        </div>
    </form>
</section>

<?php if (isset($errorMsg) && $errorMsg): ?>
    <div class="mt-4 p-4 border-l-4 border-red-500 bg-red-50 rounded-r-xl text-red-800">
        <p class="font-bold">Terjadi kesalahan:</p>
        <p><?= htmlspecialchars($errorMsg) ?></p>
    </div>
<?php endif; ?>

<?php if (isset($response) && $response && !isset($response['_error'])): ?>
    <?php
    $loc = $response['location'];
    $cur = $response['current'];
    $fc = $response['forecast']['forecastday'];

    $datetime = strtotime($loc['localtime']);
    $tanggalLokal = tanggalIndo(date("Y-m-d", $datetime));
    $jamLokal = date("H:i", $datetime);
    ?>

    <section class="bg-white rounded-2xl shadow p-4 md:p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-start border-b border-slate-100 pb-4 mb-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800"><?= htmlspecialchars($loc['name']) ?></h2>
                <p class="text-slate-500"><?= htmlspecialchars($loc['region'] ?? '') ?>, <?= htmlspecialchars($loc['country']) ?></p>
                <p class="text-blue-600 text-sm font-medium mt-1">Waktu lokal: <?= $tanggalLokal ?> | <?= $jamLokal ?></p>
            </div>
            <div class="mt-4 md:mt-0 text-right">
                <div class="text-5xl font-black text-slate-800"><?= round($cur['temp_c']) ?>°C</div>
                <p class="text-slate-600 font-medium italic"><?= htmlspecialchars(cuacaIndo($cur['condition']['text'])) ?></p>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <img src="https:<?= $cur['condition']['icon'] ?>" class="w-16 h-16" alt="Ikon Cuaca">
            <div>
                <p class="text-sm text-slate-500">Terasa seperti: <strong><?= round($cur['feelslike_c']) ?>°C</strong></p>
                <p class="text-sm text-slate-500">Kelembapan: <strong><?= $cur['humidity'] ?>%</strong></p>
            </div>
        </div>
    </section>

    <section class="mt-6">
        <h3 class="text-xl font-bold text-slate-800 mb-4 ml-2">Perkiraan Cuaca</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($fc as $day): ?>
                <div class="p-5 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition">
                    <div class="text-sm font-bold text-blue-600 mb-3 text-center bg-blue-50 py-1 rounded-lg">
                        <?= tanggalIndo($day['date']) ?>
                    </div>
                    
                    <div class="flex flex-col items-center mb-4">
                        <img src="https:<?= $day['day']['condition']['icon'] ?>" class="w-14 h-14" alt="Ikon">
                        <span class="text-sm font-semibold text-slate-700 text-center">
                            <?= htmlspecialchars(cuacaIndo($day['day']['condition']['text'])) ?>
                        </span>
                    </div>

                    <div class="space-y-2 border-t border-slate-50 pt-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Maks</span>
                            <span class="font-bold text-orange-600"><?= round($day['day']['maxtemp_c']) ?>°C</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Min</span>
                            <span class="font-bold text-blue-500"><?= round($day['day']['mintemp_c']) ?>°C</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>