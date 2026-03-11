<h1>Vandaag</h1>

<form action="<?= url('/tasks/create') ?>" method="POST" class="add-task">
    <input type="text" name="title" placeholder="Taak toevoegen..." required>
    <select name="project_id">
        <option value="">Geen project</option>
        <?php foreach($projects as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="date" name="due_date" value="<?= date('Y-m-d') ?>">
    <button type="submit">Toevoegen</button>
</form>

<ul class="task-list">
    <?php foreach($tasks as $task): ?>
        <li>
            <form action="<?= url('/tasks/toggle') ?>" method="POST">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                <button type="submit" class="<?= $task['is_completed'] ? 'done' : '' ?>">
                    <?= $task['is_completed'] ? '✅' : '⭕' ?>
                </button>
                <span><?= htmlspecialchars($task['title']) ?></span>
            </form>
        </li>
    <?php endforeach; ?>
</ul>