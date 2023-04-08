<?php
require_once ('helpers.php');
require_once ('init.php');
require_once ('functions.php');

/* текущий пользователь: */
$user = 'Keks';

$show_complete_tasks = filter_input(INPUT_GET, 'show_completed', FILTER_SANITIZE_NUMBER_INT);

$project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);

/* получаем список проектов для текущего пользователя: */
$projects_for_user = "SELECT p.id, title, p.user_id, u.id as project_user
FROM projects p 
JOIN users u ON user_id = u.id 
WHERE login = '$user'
ORDER BY p.id";
/* получаем список задач для текущего пользователя: */
$tasks_for_user = "SELECT t.id AS task_ident, t.title AS task_name, p.title AS project_name , status, date, file_path, t.user_id, project_id FROM tasks t 
JOIN users u ON t.user_id = u.id 
JOIN projects p ON t.project_id = p.id
WHERE u.login = '$user'
ORDER BY date";
/* сортируем задачи пользователя по проектам */
$sort_tasks_by_project = "SELECT t.id AS task_ident, t.title AS task_name, p.title AS project_name , status, date, file_path, t.user_id, project_id FROM tasks t 
JOIN users u ON t.user_id = u.id 
JOIN projects p ON t.project_id = p.id
WHERE u.login = '$user' AND project_id = $project_id
ORDER BY date";


/* получаем список проектов для текущего пользователя: */
if (!$con) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {
    $sql = $projects_for_user;
    $result = mysqli_query($con, $sql);
    if ($result) {
        $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($con);
        $page_content = include_template('error.php', ['error' => $error]);
    }
}
/* получаем список задач для текущего пользователя: */
if (!$con){
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {
    $sql = $tasks_for_user;
    $result = mysqli_query($con, $sql);
    if ($result) {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($con);
        $page_content = include_template('error.php', ['error' => $error]);
    }
}
/* сортируем задачи пользователя по проектам */
if (isset($_GET['project_id'])) {
    $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);
    if (!$con){
        $error = mysqli_connect_error();
        $page_content = include_template('error.php', ['error' => $error]);
    } else {
        $sql = $sort_tasks_by_project;
        $result = mysqli_query($con, $sql);
        if ($result) {
            $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $error = mysqli_error($con);
            $page_content = include_template('error.php', ['error' => $error]);
        }
    }
  }

  if (isset($_GET['project_id'])) {
    $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);
    if (!$con){
        $error = mysqli_connect_error();
        $page_content = include_template('error.php', ['error' => $error]);
    } else {
        $sql = $sort_tasks_by_project;
        $result = mysqli_query($con, $sql);
        if ($result) {
            $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $error = mysqli_error($con);
            $page_content = include_template('error.php', ['error' => $error]);
        }
    }
  }

  	// Пометка проектов как выполненных
    if (isset($_GET['check'])) {
        $task_id = filter_input(INPUT_GET, 'task_id', FILTER_SANITIZE_NUMBER_INT);
		$check = filter_input(INPUT_GET, 'check', FILTER_SANITIZE_NUMBER_INT);
        if (!$con){
            $error = mysqli_connect_error();
            $page_content = include_template('error.php', ['error' => $error]);
        } else {
            $sql = "UPDATE tasks 
            SET status = $check
            WHERE id = $task_id";
            $result = mysqli_query($con, $sql);
            if (!$result) {
                $error = mysqli_error($con);
                $page_content = include_template('error.php', ['error' => $error]);
            } else{
                header("Location: index.php");
            }
        }
      }

  if (count($tasks) == 0) {
    $error = 'Ошибка "404". Проект не найден!';
    $page_content = include_template('error.php', ['error' => $error]);
  }

/* Передаем данные в шаблон главной страницы: */
if (!isset($page_content)){
    $page_content = include_template('main.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
}

/* Передаем данные в шаблон вёрстки сайта: */
$layout = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Дела в порядке',
    'user' => $user
]);

print($layout);
?>