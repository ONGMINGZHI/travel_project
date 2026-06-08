a<?php
session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 1,
        'username' => 'John Doe',
        'role' => 'User'
    ];
}

$user = $_SESSION['user'] ?? null;
$userId = $user['id'] ?? 0;


// Fallback high-quality image mappings for local items using their database primary keys
$hotelImages = [
    1  => "https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=400&q=80",
    2  => "https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80",
    3  => "https://images.unsplash.com/photo-1502784444187-359ac186c5bb?auto=format&fit=crop&w=400&q=80",
    4  => "https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=400&q=80",
    5  => "https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=400&q=80",
    6  => "https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=400&q=80",
    7  => "https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=400&q=80",
    8  => "https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=400&q=80",
    9  => "https://images.unsplash.com/photo-1439066615861-d1af74d74000?auto=format&fit=crop&w=400&q=80",
    10 => "https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=400&q=80",
    11 => "https://images.unsplash.com/photo-1568495248636-6432b97bd949?auto=format&fit=crop&w=400&q=80",
];

$cityImages = [
    1 => "https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=300&q=80", // Tokyo
    2 => "https://images.unsplash.com/photo-1542051841857-5f90071e7989?auto=format&fit=crop&w=300&q=80", // Kyoto
    3 => "https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=300&q=80", // Paris
    4 => "https://images.unsplash.com/photo-1549144511-f099e773c147?auto=format&fit=crop&w=300&q=80", // Nice
    5 => "https://images.unsplash.com/photo-1596422846543-75c6fc18a52b?auto=format&fit=crop&w=300&q=80", // Kuala Lumpur
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #060B13; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0B1220; }
        ::-webkit-scrollbar-thumb { background: #1E293B; border-radius: 3px; }
    </style>
</head>
<body class="text-slate-200 font-sans antialiased h-screen flex overflow-hidden">

    <aside class="w-64 bg-[#0B1220] border-r border-slate-800 flex flex-col justify-between p-6 shrink-0">
        <div>
            <div class="flex items-center gap-2 text-xl font-bold text-amber-500 mb-8">
                <i class="bi bi-send-fill transform rotate-45"></i>
                <span>Travel</span>
            </div>

            <nav class="space-y-2">
                <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-amber-500 text-slate-950 font-semibold transition-colors">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                <a href="countries.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-slate-100 transition-colors">
                    <i class="bi bi-globe"></i> Countries
                </a>
                <a href="cities.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-slate-100 transition-colors">
                    <i class="bi bi-building"></i> Cities
                </a>
                <a href="hotels.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-slate-100 transition-colors">
                    <i class="bi bi-buildings"></i> Hotels
                </a>
                <a href="bookmarks.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-slate-100 transition-colors">
                    <i class="bi bi-heart"></i> Bookmarks
                </a>
            </nav>
        </div>

        <div>
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-rose-500 hover:bg-rose-500/10 transition-colors font-medium">
                <i class="bi bi-box-arrow-left"></i> Logout
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <header class="h-20 bg-[#060B13] border-b border-slate-900 flex items-center justify-between px-8 shrink-0">
            <div class="relative w-96">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                <input type="text" placeholder="Search destinations, hotels..." class="w-full bg-[#0B1220] border border-slate-800 rounded-xl pl-12 pr-4 py-2.5 text-sm text-slate-300 placeholder-slate-500 focus:outline-none focus:border-amber-500 transition-colors">
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 border-l border-slate-800 pl-6">
                    <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-slate-300 font-bold uppercase">
                        <a href="$user['user_id']><?= substr(htmlspecialchars($user['username'] ?? 'U'), 0, 2) ?></a>
                    </div>
                    <div class="text-left text-sm hidden md:block">
                        <p class="font-medium text-slate-200"><?= htmlspecialchars($user['username'] ?? 'Guest') ?></p>
                        <p class="text-xs text-slate-500"><?= htmlspecialchars($user['role'] ?? 'Visitor') ?></p>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 flex overflow-hidden">

            <div class="flex-1 overflow-y-auto p-8 space-y-8">

                <div class="relative rounded-3xl h-64 overflow-hidden bg-cover bg-center flex items-center p-12" style="background-image: linear-gradient(to right, rgba(6,11,19,0.85), rgba(6,11,19,0.1)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80');">
                    <div class="max-w-md space-y-3">
                        <h1 class="text-4xl font-bold tracking-tight text-white">Find your next <span class="text-amber-500">adventure</span></h1>
                        <p class="text-slate-300 text-sm leading-relaxed">Discover amazing places and the best hotels around the world.</p>
                    </div>
                </div>

                <div class="bg-[#0B1220] border border-slate-800 p-4 rounded-2xl grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <div class="relative">
                        <label class="block text-xs font-semibold text-slate-400 mb-1 ml-1">Where are you going?</label>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                            <input type="text" placeholder="Search city, country..." class="w-full bg-[#121B2D] text-sm text-slate-200 pl-9 pr-3 py-2 rounded-xl border border-slate-700 focus:outline-none focus:border-amber-500">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold flex items-center gap-2">Recommended Hotels</h2>
                        <a href="hotels.php" class="text-xs font-semibold text-amber-500 hover:underline">View all</a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php foreach ($recommendedHotels as $hotel): 
                            $coverUrl = $hotelImages[$hotel['hotel_id']] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80';
                        ?>
                        <div class="bg-[#0B1220] border border-slate-800/80 rounded-2xl overflow-hidden group relative">
                            <button class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-slate-900/60 backdrop-blur-md flex items-center justify-center text-slate-200 hover:text-rose-500 transition-colors">
                                <i class="bi bi-heart"></i>
                            </button>
                            <div class="h-36 overflow-hidden">
                                <img src="<?= $coverUrl ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="Hotel Photo">
                            </div>
                            <div class="p-4 space-y-2">
                                <h3 class="font-bold text-sm text-slate-100 truncate"><?= htmlspecialchars($hotel['hotel_name']) ?></h3>
                                <p class="text-xs text-slate-500 flex items-center gap-1">
                                    <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($hotel['city_name']) ?>, <?= htmlspecialchars($hotel['country_name']) ?>
                                </p>
                                <div class="flex items-center justify-between pt-1">
                                    <div class="flex items-center gap-1 text-xs">
                                        <i class="bi bi-star-fill text-amber-400"></i>
                                        <span class="text-slate-300 font-medium"><?= number_format($hotel['star_ranking'], 1) ?></span>
                                    </div>
                                    <p class="text-xs text-slate-400">
                                        <span class="text-emerald-400 font-bold text-sm">$<?= number_format($hotel['price_per_night'], 0) ?></span> / night
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold">Popular Destinations</h2>
                        <a href="countries.php" class="text-xs font-semibold text-amber-500 hover:underline">View all</a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <?php foreach ($popularDestinations as $destination): 
                            $cityImgUrl = $cityImages[$destination['city_id']] ?? 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?auto=format&fit=crop&w=300&q=80';
                        ?>
                        <div class="relative h-40 rounded-2xl overflow-hidden group cursor-pointer" onclick="location.href='hotels.php?city_id=<?= $destination['city_id'] ?>'">
                            <img src="<?= $cityImgUrl ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="<?= htmlspecialchars($destination['city_name']) ?>">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent p-4 flex flex-col justify-end">
                                <p class="font-bold text-sm text-white"><?= htmlspecialchars($destination['city_name']) ?></p>
                                <p class="text-[10px] text-slate-400"><?= htmlspecialchars($destination['country_name']) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <aside class="w-80 bg-[#0B1220] border-l border-slate-800 p-6 flex flex-col gap-6 overflow-y-auto hidden lg:flex shrink-0">

                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-slate-200">Quick Stats</h4>
                    <div class="bg-[#121B2D]/50 rounded-2xl border border-slate-800/80 divide-y divide-slate-800">
                        <div class="flex items-center justify-between p-3.5 text-sm">
                            <span class="flex items-center gap-3 text-slate-400"><i class="bi bi-globe text-amber-500"></i> Countries</span>
                            <span class="font-bold text-slate-200"><?= $countCountries ?></span>
                        </div>
                        <div class="flex items-center justify-between p-3.5 text-sm">
                            <span class="flex items-center gap-3 text-slate-400"><i class="bi bi-building text-amber-500"></i> Cities</span>
                            <span class="font-bold text-slate-200"><?= $countCities ?></span>
                        </div>
                        <div class="flex items-center justify-between p-3.5 text-sm">
                            <span class="flex items-center gap-3 text-slate-400"><i class="bi bi-buildings text-amber-500"></i> Hotels</span>
                            <span class="font-bold text-slate-200"><?= $countHotels ?></span>
                        </div>
                        <div class="flex items-center justify-between p-3.5 text-sm">
                            <span class="flex items-center gap-3 text-slate-400"><i class="bi bi-heart text-amber-500"></i> Bookmarks</span>
                            <span class="font-bold text-slate-200"><?= $countBookmarks ?></span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 flex-1 flex flex-col">
                    <div class="flex justify-between items-center">
                        <h4 class="text-sm font-bold text-slate-200">Your Bookmarks</h4>
                        <a href="bookmarks.php" class="text-[11px] font-semibold text-amber-500 hover:underline">View all</a>
                    </div>

                    <div class="space-y-3 overflow-y-auto pr-1 flex-1">
                        <?php if (empty($userBookmarks)): ?>
                            <p class="text-xs text-slate-500 italic text-center pt-4">No bookmarks added.</p>
                        <?php else: ?>
                            <?php foreach ($userBookmarks as $bookmark): 
                                $bookmarkThumb = $hotelImages[$bookmark['hotel_id']] ?? 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=150&q=80';
                            ?>
                            <div class="flex gap-3 bg-[#121B2D]/40 border border-slate-800/60 p-2.5 rounded-xl items-center relative group">
                                <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0">
                                    <img src="<?= $bookmarkThumb ?>" class="w-full h-full object-cover" alt="Hotel Thumbnail">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-xs font-bold text-slate-200 truncate"><?= htmlspecialchars($bookmark['hotel_name']) ?></h5>
                                    <p class="text-[10px] text-slate-500"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($bookmark['city_name']) ?>, <?= htmlspecialchars($bookmark['country_name']) ?></p>
                                    <p class="text-xs text-emerald-400 font-bold mt-0.5">$<?= number_format($bookmark['price_per_night'], 0) ?> <span class="text-[10px] text-slate-500 font-normal">/ night</span></p>
                                </div>
                                <i class="bi bi-heart-fill text-rose-500 text-xs absolute top-3 right-3"></i>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </aside>

        </main>
    </div>

</body>
</html>
