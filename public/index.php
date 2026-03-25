<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todoist Clone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white flex h-screen overflow-hidden font-sans text-gray-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-50 h-full border-r border-gray-200 flex flex-col">
        <div class="p-6 text-xl font-bold text-red-600">Todoist Clone</div>
        
        <nav class="flex-1 px-4">
            <a href="#" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded-lg mb-4">
                <span class="mr-3">📅</span> Vandaag
            </a>

            <h3 class="px-2 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Projecten</h3>
            <div class="space-y-1">
                <!-- PHP LOOP: Gebruik getProjects() om deze links te genereren -->
                <a href="#" class="flex items-center justify-between p-2 text-gray-700 hover:bg-gray-200 rounded-lg group">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full mr-3" style="background-color: #db4c3f"></span>
                        🚀 Werk
                    </div>
                </a>
                <!-- Einde Loop -->
            </div>
        </nav>

        <div class="p-4 border-t border-gray-200 bg-gray-100 flex justify-between items-center text-sm">
            <span>👤 Jan de Tester</span>
            <a href="logout.php" class="text-red-500 hover:underline">Uitloggen</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-white p-12">
        <header class="max-w-3xl mx-auto mb-8">
            <h1 class="text-2xl font-bold flex items-center">
                <span class="w-4 h-4 rounded-full mr-4 bg-blue-500"></span>
                🏠 Privé
            </h1>
        </header>

        <!-- Taak Toevoegen -->
        <section class="max-w-3xl mx-auto mb-8">
            <form class="border border-gray-200 rounded-xl p-4 shadow-sm focus-within:border-gray-400 transition-all">
                <input type="text" placeholder="Taak toevoegen aan 🏠 Privé..." class="w-full outline-none text-lg mb-2">
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <input type="date" class="border rounded px-2 py-1">
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-600 transition">Toevoegen</button>
                </div>
            </form>
        </section>

        <!-- Taken Lijst -->
        <section class="max-w-3xl mx-auto">
            <ul class="divide-y divide-gray-100">
                <!-- PHP LOOP: Gebruik getTasks() voor elke taak -->
                <li class="py-3 flex items-center group">
                    <button class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center hover:border-gray-500 transition">
                        <!-- Toon checkbox of cirkel afhankelijk van status -->
                    </button>
                    <div class="flex-1">
                        <p class="text-gray-800">Email beantwoorden naar klant</p>
                        <p class="text-xs text-red-500 mt-1 flex items-center">📅 11 Mar</p>
                    </div>
                </li>
                <!-- Einde Loop -->
            </ul>
        </section>
    </main>

</body>
</html>