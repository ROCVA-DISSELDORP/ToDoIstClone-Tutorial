<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todoist Clone</title>
    <link rel="stylesheet" href="<?= url('/public/css/style.css') ?>">
</head>
<body>
<div class="sidebar">
    <h2>Todoist Clone</h2>
    <nav>
        <a href="<?= url('/') ?>" class="<?= $_SERVER['REQUEST_URI'] == url('/') ? 'active' : '' ?>">
            <span>📅 Vandaag</span>
        </a>

        <h3>Projecten</h3>
        <?php if(isset($projects)): foreach($projects as $p): ?>
            <?php 
                $projectUrl = url('/projects/' . $p['id']);
                $isActive = (strpos($_SERVER['REQUEST_URI'], $projectUrl) !== false);
            ?>
            <a href="<?= $projectUrl ?>" class="<?= $isActive ? 'active' : '' ?> project-link">
                <div class="project-info">
                    <span class="project-dot" style="background-color: <?= $p['color'] ?>"></span>
                    <?= htmlspecialchars($p['name']) ?>
                </div>
            </a>
        <?php endforeach; endif; ?>
    </nav>

    <div class="user-box">
        👤 <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Gast') ?> | 
        <a href="<?= url('/logout') ?>" style="color: #db4c3f; text-decoration: none;">Uitloggen</a>
    </div>
</div>
    <main class="content">
        <?= $content ?>
    </main>
</body>
</html>