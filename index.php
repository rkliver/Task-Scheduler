<?php
require_once ('helpers.php');
require_once ('init.php');

$user = 'Keks';
$show_complete_tasks = rand(0, 1);
$tasks = [];

if (!$con) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {
    $sql = "SELECT * FROM projects JOIN users ON user_id = users.id WHERE login = '$user'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($con);
        $page_content = include_template('error.php', ['error' => $error]);
    }
}
if (!$con){
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {
    $sql = "SELECT * FROM tasks JOIN users ON user_id = users.id WHERE login = '$user'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($con);
        $page_content = include_template('error.php', ['error' => $error]);
    }
}

function task_counter(array $tasks, $project): int{
    $count = 0;
    foreach($tasks as $task) 
    {
        if ($project == $task['project_id']) 
        {
            $count ++;
        };
    };
    return $count;
}

$page_content = include_template('main.php', [
    'categories' => $categories,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);

$layout = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Дела в порядке',
    'user' => $user
]);

print($layout);

?>