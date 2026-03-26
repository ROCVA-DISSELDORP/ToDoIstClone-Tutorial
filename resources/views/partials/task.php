<?php
// Verwacht variabele: $task (array met keys: title, due_date)
?>

<li class="py-3 flex items-center group">
    <button class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center hover:border-gray-500 transition">
        <!-- Toon checkbox of cirkel afhankelijk van status -->
    </button>
    <div class="flex-1">
        <p class="text-gray-800"><?= htmlspecialchars($task['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        <p class="text-xs text-red-500 mt-1 flex items-center">📅 <?= htmlspecialchars($task['due_date'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</li>

