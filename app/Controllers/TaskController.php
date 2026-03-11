<?php
namespace App\Controllers;
use App\Models\Task;
use App\Models\Project;
use App\Core\View;
use App\Helpers\Auth;

class TaskController {
    public function __construct() { Auth::init(); Auth::check(); }

    public function index() {
        $tasks = Task::getToday($_SESSION['user']['id']);
        $projects = Project::all($_SESSION['user']['id']);
        return View::render('dashboard', ['tasks' => $tasks, 'projects' => $projects]);
    }

    public function store() {
        Task::create([
            'user_id' => $_SESSION['user']['id'],
            'title' => $_POST['title'],
            'project_id' => $_POST['project_id'] ?: null,
            'due_date' => $_POST['due_date'] ?: null
        ]);
        header('Location: ' . url('/'));
    }

    public function toggle() {
        Task::toggle($_POST['id']);
        header('Location: ' . url('/'));
    }
}