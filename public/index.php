<?php
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/ProjectController.php';
require_once __DIR__ . '/../app/Controllers/TaskController.php';

checkLoggedIn();
$userId = $_SESSION['user_id'];
$user = getUser($userId);
$userName = $user['name'] ?? ($_SESSION['user_name'] ?? 'Gebruiker');
$projects = getAllProjects($userId);

// Filtering vanuit query parameters:
// - `project_id` kan een numeric id zijn of de waarde `inbox`
// - `today=1` toont alleen taken voor vandaag
$onlyToday = !empty($_GET['today']) && $_GET['today'] === '1';
$projectIdParam = $_GET['project_id'] ?? null;

$filterProjectId = null;
$activeLabel = 'Alle taken';
$activeProjectDbId = null;

if ($onlyToday) {
    $activeLabel = 'Vandaag';
} elseif ($projectIdParam === 'inbox') {
    $filterProjectId = 'inbox';
    $activeLabel = 'Inbox';
} elseif (!empty($projectIdParam)) {
    $filterProjectId = (int) $projectIdParam;
    $activeProjectDbId = $filterProjectId;
    $activeLabel = 'Project';
    foreach ($projects as $p) {
        if ((int) $p['id'] === $activeProjectDbId) {
            $activeLabel = $p['name'] ?? $activeLabel;
            break;
        }
    }
}

// Taak toevoegen (POST afhandeling)
$addError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim((string)($_POST['title'] ?? ''));
    $dueDateRaw = trim((string)($_POST['due_date'] ?? ''));
    if ($dueDateRaw !== '') {
        $dueDate = $dueDateRaw;
    } else {
        // Op de "Vandaag"-filter zetten we automatisch de due date op vandaag.
        $dueDate = $onlyToday ? date('Y-m-d') : null;
    }

    // Als je in een project-view zit, postten we het project-id mee.
    // In inbox/alle taken-vizes posten we leeg => project_id NULL.
    $projectIdRaw = trim((string)($_POST['project_id'] ?? ''));
    $projectId = null;
    if ($projectIdRaw !== '') {
        // Basis: alleen numerieke project_id's opslaan.
        $projectId = (int)$projectIdRaw;
    }

    if ($title === '') {
        $addError = 'Vul een taaknaam in.';
    } else {
        $description = '';
        $created = createTask($userId, $title, $description, $dueDate, $projectId);
        if ($created) {
            $query = [];
            if ($onlyToday) {
                $query['today'] = '1';
            }
            if ($filterProjectId === 'inbox') {
                $query['project_id'] = 'inbox';
            } elseif (!empty($filterProjectId)) {
                $query['project_id'] = (string)(int)$filterProjectId;
            }

            $qs = !empty($query) ? '?' . http_build_query($query) : '';
            header('Location: ./index.php' . $qs);
            exit;
        }
        $addError = 'Kon taak niet opslaan. Probeer opnieuw.';
    }
}

$tasks = getTasks($userId, $filterProjectId, $onlyToday);

// Bepaal waar de taak standaard aan gekoppeld wordt.
$addTargetLabel = 'Inbox';
if ($filterProjectId === 'inbox') {
    $addTargetLabel = 'Inbox';
} elseif (!empty($filterProjectId)) {
    // Toon de projectnaam i.p.v. de technische id.
    foreach ($projects as $p) {
        if ((int)($p['id'] ?? 0) === (int)$filterProjectId) {
            $addTargetLabel = $p['name'] ?? 'Project';
            break;
        }
    }
}
?>

<?php include __DIR__ . '/../resources/views/partials/header.php'; ?>
<body class="bg-white flex h-screen overflow-hidden font-sans text-gray-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-50 h-full border-r border-gray-200 flex flex-col">
        <div class="p-6 text-xl font-bold text-red-600">Todoist Clone</div>
        
        <nav class="flex-1 px-4">
            <?php $isAllTasksActive = !$onlyToday && $filterProjectId === null; ?>
            <a href="./index.php" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded-lg mb-4 <?= $isAllTasksActive ? 'bg-gray-200' : '' ?>">
                <span class="mr-3">📌</span> Alle taken
            </a>

            <a href="./index.php?today=1" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded-lg mb-4 <?= $onlyToday ? 'bg-gray-200' : '' ?>">
                <span class="mr-3">📅</span> Vandaag
            </a>

            <h3 class="px-2 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Projecten</h3>
            <div class="space-y-1">
                <?php foreach ($projects as $project) : ?>
                <?php $isActive = (!$onlyToday && $activeProjectDbId !== null && (int)$project['id'] === (int)$activeProjectDbId); ?>
                <a href="./index.php?project_id=<?= (int)$project['id'] ?>&project_name=<?= rawurlencode((string)$project['name']) ?>" class="flex items-center justify-between p-2 text-gray-700 hover:bg-gray-200 rounded-lg group <?= $isActive ? 'bg-gray-200' : '' ?>">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full mr-3" style="background-color: #db4c3f"></span>
                        <?= $project['name'] ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </nav>

        <div class="p-4 border-t border-gray-200 bg-gray-100 flex justify-between items-center text-sm">
            <span>👤 <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></span>
            <a href="logout.php" class="text-red-500 hover:underline">Uitloggen</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-white p-12">
        <header class="max-w-3xl mx-auto mb-8">
            <h1 class="text-2xl font-bold flex items-center">
                <span class="w-4 h-4 rounded-full mr-4 bg-blue-500"></span>
                🏠 <?= htmlspecialchars($activeLabel, ENT_QUOTES, 'UTF-8') ?>
            </h1>
        </header>

        <!-- Taak Toevoegen -->
        <section class="max-w-3xl mx-auto mb-8">
            <?php if ($addError !== null): ?>
                <div class="max-w-3xl mx-auto mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm" role="alert">
                    <?= htmlspecialchars($addError, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="border border-gray-200 rounded-xl p-4 shadow-sm focus-within:border-gray-400 transition-all">
                <input type="hidden"
                    name="project_id"
                    value="<?= ($filterProjectId === 'inbox' || $filterProjectId === null) ? '' : (string)(int)$filterProjectId ?>">

                <input
                    type="text"
                    name="title"
                    placeholder="Taak toevoegen aan <?= htmlspecialchars($addTargetLabel, ENT_QUOTES, 'UTF-8') ?>..."
                    class="w-full outline-none text-lg mb-2"
                >
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <input type="date" name="due_date" class="border rounded px-2 py-1">
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-600 transition">
                        Toevoegen
                    </button>
                </div>
            </form>
        </section>

        <!-- Taken Lijst -->
        <section class="max-w-3xl mx-auto">
            <ul class="divide-y divide-gray-100">
                <?php foreach ($tasks as $task) : ?>
                <?php include __DIR__ . '/../resources/views/partials/task.php'; ?>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

</body>

<?php include __DIR__ . '/../resources/views/partials/footer.php'; ?>