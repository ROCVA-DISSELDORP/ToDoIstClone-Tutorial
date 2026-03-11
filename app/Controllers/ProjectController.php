<?php
namespace App\Controllers;
use App\Models\Project;
use App\Core\View;
use App\Helpers\Auth;

class ProjectController {
    public function __construct() { 
        Auth::init(); 
        Auth::check(); 
    }

    // De $id komt nu direct uit de router!
    public function show($id) {
        $userId = $_SESSION['user']['id'];
        $project = Project::find($id, $userId);
        
        if (!$project) {
            die("Project niet gevonden of geen toegang.");
        }

        $tasks = Project::getTasks($id, $userId);
        $projects = Project::all($userId); 

        return View::render('projects/show', [
            'project' => $project,
            'tasks' => $tasks,
            'projects' => $projects
        ]);
    }
}