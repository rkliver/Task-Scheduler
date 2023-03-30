<?php
require_once ('helpers.php');
require_once ('init.php');
/* текущий пользователь: */
$user = 'Keks';
$show_complete_tasks = rand(0, 1);
/* получаем список проектов для текущего пользователя: */
if (!$con) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {
    $sql = "SELECT p.id, title 
            FROM projects p 
            JOIN users u ON user_id = u.id 
            WHERE login = '$user'
            ORDER BY p.id";
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
    $sql = "SELECT t.title AS task_name, p.title AS project_name , status, date, file_path, t.user_id, project_id FROM tasks t 
            JOIN users u ON t.user_id = u.id 
            JOIN projects p ON t.project_id = p.id
            WHERE u.login = '$user'
            ORDER BY date";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($con);
        $page_content = include_template('error.php', ['error' => $error]);
    }
}
/* функция- счетчик задач для текущего пользователя: */
function task_counter($tasks, $task_project): int{
    $count = 0;
    foreach($tasks as $task){
        if ($task['project_name'] == $task_project){
            $count ++;
        }
    }
    return $count;
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