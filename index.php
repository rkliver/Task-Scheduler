<?php
require_once ('helpers.php');
require_once ('init.php');
require_once ('sql.php');
require_once ('functions.php');

$show_complete_tasks = rand(0, 1);

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

/* Передаем данные в шаблон главной страницы: */
$page_content = include_template('main.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
/* Передаем данные в шаблон вёрстки сайта: */
$layout = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Дела в порядке',
    'user' => $user
]);

print($layout);
?>