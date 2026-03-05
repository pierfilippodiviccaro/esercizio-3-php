<?php
$ristoranti = [
    [
        'nome' => 'Trattoria da Mario',
        'cucina' => 'italiana',
        'voto' => 5,
        'consegna_a_domicilio' => true,
        'prezzo_medio' => 25.00
    ],
    [
        'nome' => 'Sushi Zen',
        'cucina' => 'giapponese',
        'voto' => 4,
        'consegna_a_domicilio' => true,
        'prezzo_medio' => 35.00
    ],
    [
        'nome' => 'El Taco Loco',
        'cucina' => 'messicana',
        'voto' => 3,
        'consegna_a_domicilio' => false,
        'prezzo_medio' => 20.00
    ],
    [
        'nome' => 'Pizzeria Napoli',
        'cucina' => 'italiana',
        'voto' => 4,
        'consegna_a_domicilio' => false,
        'prezzo_medio' => 15.00
    ],
    [
        'nome' => 'Tokyo Ramen',
        'cucina' => 'giapponese',
        'voto' => 2,
        'consegna_a_domicilio' => true,
        'prezzo_medio' => 18.00
    ],
];

$filteredRistoranti = [];
$filteredcuisine = isset($_GET['cucina']) ? $_GET['cucina'] : '';
$filteredvote = isset($_GET['voto']) ? $_GET['voto'] : '';
$filteredtakeout = isset($_GET["consegna_a_domicilio"]) ? $_GET["consegna_a_domicilio"] : "";

foreach ($ristoranti as $risto) {
    if ($filteredcuisine !== "" && $filteredcuisine !== $risto["cucina"]) continue;
    if ($filteredvote !== "" && $risto["voto"] < $filteredvote) continue;
    if ($filteredtakeout !== "" && $risto["consegna_a_domicilio"] !== ($filteredtakeout === "1")) continue;
    $filteredRistoranti[] = $risto;
}

$cucineInfo = [
    'italiana'   => ['emoji' => '🍕', 'bg' => '#fff1e6', 'accent' => '#e8500a', 'tag_bg' => '#fde0cc', 'tag_text' => '#c44008'],
    'giapponese' => ['emoji' => '🍣', 'bg' => '#f0f4ff', 'accent' => '#4361ee', 'tag_bg' => '#dce4ff', 'tag_text' => '#2541c4'],
    'messicana'  => ['emoji' => '🌮', 'bg' => '#f0fff4', 'accent' => '#2d9c5a', 'tag_bg' => '#c8f5dc', 'tag_text' => '#1a7a42'],
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ristoranti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,800;1,400&family=DM+Sans:wght@300;400;500;600&display=swap');

        * { box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; }
        .fraunces { font-family: 'Fraunces', serif; }

        /* Sfondo a pattern geometrico */
        body {
            background-color: #faf7f2;
            background-image:
                radial-gradient(circle at 20% 20%, #ffe8d6 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, #e8f0ff 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, #e8fff0 0%, transparent 60%);
        }

        /* Pattern puntini */
        .dot-pattern {
            background-image: radial-gradient(circle, #d4c9b8 1px, transparent 1px);
            background-size: 24px 24px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-8px); }
        }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        .card { animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .card:nth-child(1) { animation-delay: 0.05s; }
        .card:nth-child(2) { animation-delay: 0.12s; }
        .card:nth-child(3) { animation-delay: 0.19s; }
        .card:nth-child(4) { animation-delay: 0.26s; }
        .card:nth-child(5) { animation-delay: 0.33s; }

        .emoji-float { animation: float 3s ease-in-out infinite; }

        .spin-badge { animation: spin-slow 12s linear infinite; }

        /* Card hover 3D */
        .card {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-6px) rotate(-0.5deg);
            box-shadow: 0 24px 48px rgba(0,0,0,0.12), 0 8px 16px rgba(0,0,0,0.08);
        }

        /* Input e select stilizzati */
        .filter-input {
            background: white;
            border: 2px solid #e8e0d5;
            border-radius: 16px;
            padding: 12px 16px;
            font-size: 14px;
            color: #2c2416;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            appearance: none;
        }

        .filter-input:focus {
            border-color: #e8500a;
            box-shadow: 0 0 0 4px rgba(232, 80, 10, 0.1);
        }

        /* Stelle */
        .star { font-size: 16px; }
        .star-on  { color: #f59e0b; }
        .star-off { color: #e8e0d5; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #faf7f2; }
        ::-webkit-scrollbar-thumb { background: #d4c9b8; border-radius: 3px; }
    </style>
</head>
<body class="min-h-screen">

    <!-- Dot pattern overlay -->
    <div class="fixed inset-0 dot-pattern opacity-40 pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-6 py-16">

        <!-- Header -->
        <div class="flex items-end justify-between mb-16 flex-wrap gap-8">
            <div>
                <div class="inline-flex items-center gap-2 bg-white border-2 border-amber-200 rounded-full px-4 py-2 mb-6 shadow-sm">
                    <span class="text-amber-500 text-xs">✦</span>
                    <span class="text-xs font-medium text-amber-700 tracking-wider uppercase">Guida gastronomica 2026</span>
                    <span class="text-amber-500 text-xs">✦</span>
                </div>
                <h1 class="fraunces text-8xl font-bold text-stone-900 leading-none mb-2">
                    Risto<br>
                    <span class="italic font-normal text-orange-500">ranti</span>
                </h1>
                <p class="text-stone-400 text-lg font-light mt-4 max-w-sm">
                    Scopri i migliori tavoli della città, filtrati per te.
                </p>
            </div>

            <!-- Badge rotante -->
            <div class="relative w-32 h-32 hidden md:block">
                <svg class="spin-badge absolute inset-0 w-full h-full" viewBox="0 0 120 120">
                    <path id="circle" d="M 60,60 m -45,0 a 45,45 0 1,1 90,0 a 45,45 0 1,1 -90,0" fill="none"/>
                    <text font-size="11" font-family="DM Sans" fill="#9a8c7a" font-weight="500" letter-spacing="3">
                        <textPath href="#circle">BUON APPETITO • BUON APPETITO • </textPath>
                    </text>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-4xl emoji-float">🍽️</span>
                </div>
            </div>
        </div>

        <!-- Filtri -->
        <div class="bg-white border-2 border-stone-200 rounded-3xl p-8 mb-12 shadow-lg shadow-stone-100/50 relative overflow-hidden">

            <!-- Decorazione angolo -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-orange-50 to-transparent rounded-bl-full"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-50 to-transparent rounded-tr-full"></div>

            <div class="relative">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                        <span class="text-white text-sm">⚡</span>
                    </div>
                    <h2 class="fraunces text-xl font-bold text-stone-800">Filtra i risultati</h2>
                </div>

                <form method="GET" class="flex flex-wrap gap-5 items-end">

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-semibold text-stone-400 uppercase tracking-widest">🍴 Cucina</label>
                        <select name="cucina" class="filter-input min-w-[160px]">
                            <option value="">Tutte le cucine</option>
                            <option value="italiana"   <?= $filteredcuisine === "italiana"   ? "selected" : "" ?>>🍕 Italiana</option>
                            <option value="giapponese" <?= $filteredcuisine === "giapponese" ? "selected" : "" ?>>🍣 Giapponese</option>
                            <option value="messicana"  <?= $filteredcuisine === "messicana"  ? "selected" : "" ?>>🌮 Messicana</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-semibold text-stone-400 uppercase tracking-widest">⭐ Voto minimo</label>
                        <input type="number" name="voto" min="1" max="5"
                            placeholder="es. 3"
                            value="<?= $filteredvote ?>"
                            class="filter-input w-32">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-semibold text-stone-400 uppercase tracking-widest">🛵 Consegna</label>
                        <select name="consegna_a_domicilio" class="filter-input min-w-[180px]">
                            <option value="">Tutti</option>
                            <option value="1" <?= $filteredtakeout === "1" ? "selected" : "" ?>>Con consegna a domicilio</option>
                        </select>
                    </div>

                    <div class="flex gap-3 ml-auto">
                        <button type="submit"
                            class="bg-orange-500 hover:bg-orange-600 active:scale-95 text-white font-semibold px-8 py-3 rounded-2xl text-sm transition-all shadow-lg shadow-orange-200">
                            Cerca →
                        </button>
                        <a href="?"
                            class="bg-stone-100 hover:bg-stone-200 active:scale-95 text-stone-500 font-medium px-6 py-3 rounded-2xl text-sm transition-all">
                            Reset
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <!-- Contatore risultati -->
        <div class="flex items-center gap-4 mb-8">
            <div class="bg-orange-500 text-white text-sm font-bold px-4 py-2 rounded-full">
                <?= count($filteredRistoranti) ?>
            </div>
            <span class="text-stone-400 text-sm">
                ristoranti trovati
                <?php if ($filteredcuisine || $filteredvote || $filteredtakeout): ?>
                    con i filtri selezionati
                <?php endif; ?>
            </span>
            <div class="h-px flex-1 bg-stone-200"></div>
        </div>

        <!-- Cards -->
        <?php if (count($filteredRistoranti) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($filteredRistoranti as $ri):
                    $info = $cucineInfo[$ri['cucina']];
                ?>
                <div class="card bg-white rounded-3xl overflow-hidden border-2 border-stone-100 cursor-pointer"
                     style="background: <?= $info['bg'] ?>;">

                    <!-- Top card -->
                    <div class="p-6 pb-4">
                        <div class="flex items-start justify-between mb-4">
                            <span class="text-5xl emoji-float" style="animation-delay: <?= rand(0, 10) * 0.1 ?>s">
                                <?= $info['emoji'] ?>
                            </span>
                            <span class="text-xs font-semibold px-3 py-1.5 rounded-full border"
                                  style="background:<?= $info['tag_bg'] ?>; color:<?= $info['tag_text'] ?>; border-color:<?= $info['tag_bg'] ?>">
                                <?= ucfirst($ri['cucina']) ?>
                            </span>
                        </div>

                        <h2 class="fraunces text-2xl font-bold text-stone-900 leading-tight mb-1">
                            <?= $ri['nome'] ?>
                        </h2>

                        <!-- Stelle -->
                        <div class="flex items-center gap-0.5 mt-3">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <span class="star <?= $s <= $ri['voto'] ? 'star-on' : 'star-off' ?>">★</span>
                            <?php endfor; ?>
                            <span class="text-xs text-stone-400 ml-2 font-medium"><?= $ri['voto'] ?>.0</span>
                        </div>
                    </div>

                    <!-- Divider decorativo -->
                    <div class="mx-6 border-t-2 border-dashed" style="border-color: <?= $info['accent'] ?>30"></div>

                    <!-- Bottom card -->
                    <div class="p-6 pt-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-stone-400 font-medium uppercase tracking-wider mb-0.5">Prezzo medio</p>
                                <p class="text-2xl font-bold" style="color: <?= $info['accent'] ?>">
                                    <?= number_format($ri['prezzo_medio'], 2) ?>€
                                </p>
                            </div>

                            <?php if ($ri['consegna_a_domicilio']): ?>
                                <div class="flex flex-col items-center gap-1 bg-white rounded-2xl px-4 py-2 shadow-sm border border-green-100">
                                    <span class="text-xl">🛵</span>
                                    <span class="text-xs font-semibold text-green-600">Consegna</span>
                                </div>
                            <?php else: ?>
                                <div class="flex flex-col items-center gap-1 bg-white/60 rounded-2xl px-4 py-2 border border-stone-200">
                                    <span class="text-xl grayscale opacity-40">🛵</span>
                                    <span class="text-xs font-medium text-stone-300">Solo locale</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="text-center py-32 bg-white rounded-3xl border-2 border-stone-100">
                <span class="text-7xl block mb-6">😔</span>
                <p class="fraunces text-3xl text-stone-800 mb-2">Nessun risultato</p>
                <p class="text-stone-400 mb-6">Prova a modificare i filtri di ricerca</p>
                <a href="?" class="inline-block bg-orange-500 text-white font-semibold px-8 py-3 rounded-2xl text-sm hover:bg-orange-600 transition">
                    Rimuovi filtri
                </a>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
