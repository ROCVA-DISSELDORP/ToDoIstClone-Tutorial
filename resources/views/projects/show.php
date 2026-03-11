<header style="margin-bottom: 30px;">
    <h1 style="display: flex; align-items: center; gap: 15px;">
        <span class="project-dot" style="width: 20px; height: 20px; background-color: <?= $project['color'] ?>"></span>
        <?= htmlspecialchars($project['name']) ?>
    </h1>
</header>

<section class="add-task-container">
    <form action="<?= url('/tasks/create') ?>" method="POST" class="add-task">
        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
        <input type="text" name="title" placeholder="Taak toevoegen aan <?= htmlspecialchars($project['name']) ?>..." required>
        <input type="date" name="due_date" value="<?= date('Y-m-d') ?>">
        <button type="submit">+ Taak toevoegen</button>
    </form>
</section>

<div class="task-container">
    <ul class="task-list">
        <?php if(empty($tasks)): ?>
            <p style="color: gray; font-style: italic;">Geen openstaande taken voor dit project.</p>
        <?php endif; ?>
        
        <?php foreach($tasks as $task): ?>
            <li>
                <form action="<?= url('/tasks/toggle') ?>" method="POST" style="display: flex; align-items: center; width: 100%;">
                    <input type="hidden" name="id" value="<?= $task['id'] ?>">
                    <button type="submit" style="font-size: 1.4rem; border: none; background: none; cursor: pointer;">
                        <?= $task['is_completed'] ? '✅' : '⭕' ?>
                    </button>
                    <span style="<?= $task['is_completed'] ? 'text-decoration: line-through; color: #aaa;' : '' ?> font-size: 1rem;">
                        <?= htmlspecialchars($task['title']) ?>
                        <?php if($task['due_date']): ?>
                            <small style="display: block; color: #db4c3f; font-size: 0.75rem;">
                                📅 <?= date('j M', strtotime($task['due_date'])) ?>
                            </small>
                        <?php endif; ?>
                    </span>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>