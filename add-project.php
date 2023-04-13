<?php

require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

/* текущий пользователь: */
if (!isset($_SESSION['user_id'])) {
    $layout = include_template('guest.php');
} else {
    $user_id = $_SESSION['user_id'];
    $sql= "SELECT login FROM users WHERE id = ".'"'.$user_id.'"';
    $res = mysqli_query($con, $sql);
    $user_log = mysqli_fetch_assoc($res);
    $user_name = $user_log['login'];
    $user = $user_name;

    /* получаем список проектов для текущего пользователя: */
    $projects_for_user = "SELECT p.id, title, p.user_id, u.id as project_user
FROM projects p 
JOIN users u ON user_id = u.id 
WHERE login = '$user'
ORDER BY p.id";
    /* получаем список задач для текущего пользователя: */
    $tasks_for_user = "SELECT t.title AS task_name, p.title AS project_name , status, date, file_path, t.user_id, project_id FROM tasks t 
JOIN users u ON t.user_id = u.id 
JOIN projects p ON t.project_id = p.id
WHERE u.login = '$user'
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
    if (!$con) {
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

    $page_content = include_template('form-project.php', ['projects' => $projects, 'tasks' => $tasks,]);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $project_add['title'] = htmlspecialchars($_POST['name']);
        $project_add['user_id'] = $_SESSION['user_id'];
        $errors = [];
        foreach ($projects as $project) {
            if ($project['title'] == $project_add['title']) {
                $errors['title'] = 'Такой проект уже существует';
                continue;
            }
        }
        if ($project_add['title'] == '') {
            $errors['title'] = 'Введите название';
        } elseif (iconv_strlen($project_add['title']) > 255) {
            $errors['title'] = 'Длинна поля превышает максимально допустимую (255 символов)';
        }
        $errors = array_filter($errors);
        if (count($errors)) {
            $page_content = include_template('form-project.php', ['errors' => $errors, 'projects' => $projects, 'tasks' => $tasks,]);
        } else {
            $sql = "INSERT INTO projects (title, user_id) VALUES (?, ?)";

            $stmt = db_get_prepare_stmt($con, $sql, $project_add);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                header("Location: index.php");
            } else {
                $page_content = include_template('error.php', ['error' => mysqli_error($con)]);
            }
        }
    }

    $layout = include_template('layout.php', [
        'page_content' => $page_content,
        'title' => 'Дела в порядке',
        'user' => $user
    ]);
}
print($layout);
