<?php

require_once('helpers.php');
require_once('init.php');
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


    $show_complete_tasks = filter_input(INPUT_GET, 'show_completed', FILTER_SANITIZE_NUMBER_INT);

    $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);

    $task_search_null = '';

    /* получаем список проектов для текущего пользователя: */
    $projects_for_user = "SELECT p.id, title, p.user_id, u.id as project_user
FROM projects p 
JOIN users u ON user_id = u.id 
WHERE login = '$user'
ORDER BY p.id";
    /* получаем список задач для текущего пользователя и сортируем их в зависимости от запроса*/
    if (!isset($_GET['sort']) || $_GET['sort'] == 'all') {
        $tasks_for_user = "SELECT t.id AS task_ident, t.title AS task_name, p.title AS project_name , status, date, file_path, t.user_id, project_id FROM tasks t 
    JOIN users u ON t.user_id = u.id 
    JOIN projects p ON t.project_id = p.id
    WHERE u.login = '$user'
    ORDER BY date";
    }
    if (isset($_GET['sort']) && $_GET['sort'] == 'today') {
        $tasks_for_user = "SELECT t.id AS task_ident, t.title AS task_name, p.title AS project_name , status, date, file_path, t.user_id, project_id FROM tasks t 
    JOIN users u ON t.user_id = u.id 
    JOIN projects p ON t.project_id = p.id
    WHERE u.login = '$user' AND DAY(date) = DAY(NOW()) AND MONTH(date) = MONTH(NOW()) AND YEAR(date) = YEAR(NOW())
    ORDER BY date";
    }
    if (isset($_GET['sort']) && $_GET['sort'] == 'tomorrow') {
        $tasks_for_user = "SELECT t.id AS task_ident, t.title AS task_name, p.title AS project_name , status, date, file_path, t.user_id, project_id FROM tasks t 
    JOIN users u ON t.user_id = u.id 
    JOIN projects p ON t.project_id = p.id
    WHERE u.login = '$user' AND DAY(date) = DAY(NOW()) + 1 AND MONTH(date) = MONTH(NOW()) AND YEAR(date) = YEAR(NOW())
    ORDER BY date";
    }
    if (isset($_GET['sort']) && $_GET['sort'] == 'past') {
        $tasks_for_user = "SELECT t.id AS task_ident, t.title AS task_name, p.title AS project_name , status, date, file_path, t.user_id, project_id FROM tasks t 
    JOIN users u ON t.user_id = u.id 
    JOIN projects p ON t.project_id = p.id
    WHERE u.login = '$user' AND DAY(date) < DAY(NOW())  AND MONTH(date) <= MONTH(NOW()) AND YEAR(date) <= YEAR(NOW())
    ORDER BY date";
    }
    if (isset($_GET['task_search']) && $_GET['task_search'] != '') {
        $task_search = htmlspecialchars($_GET['task_search']);
        $tasks_for_user = "SELECT t.id AS task_ident, t.title AS task_name, p.title AS project_name , status, date, file_path, t.user_id, project_id FROM tasks t 
    JOIN users u ON t.user_id = u.id 
    JOIN projects p ON t.project_id = p.id
    WHERE u.login = '$user' AND MATCH(t.title) AGAINST('$task_search')
    ORDER BY date";
    }
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
    if (!$con) {
        $error = mysqli_connect_error();
        $page_content = include_template('error.php', ['error' => $error]);
    } else {
        $sql = $tasks_for_user;
        $result = mysqli_query($con, $sql);
        if ($result) {
            $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if (isset($_GET['task_search']) && $_GET['task_search'] != '' && count($tasks) == 0) {
                $task_search_null = 'Ничего не найдено по вашему запросу';
            }
        } else {
            $error = mysqli_error($con);
            $page_content = include_template('error.php', ['error' => $error]);
        }
    }
    /* сортируем задачи пользователя по проектам */
    if (isset($_GET['project_id'])) {
        $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);
        if (!$con) {
            $error = mysqli_connect_error();
            $page_content = include_template('error.php', ['error' => $error]);
        } else {
            $sql = $sort_tasks_by_project;
            $result = mysqli_query($con, $sql);
            if ($result) {
                $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
                if (count($tasks) == 0) {
                    $error = 'Задач не найдено';
                    $page_content = include_template('error.php', ['error' => $error]);
                }

            } else {
                $error = mysqli_error($con);
                $page_content = include_template('error.php', ['error' => $error]);
            }
        }
    }

    if (isset($_GET['project_id'])) {
        $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);
        if (!$con) {
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
        if (!$con) {
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
            } else {
                header("Location: index.php");
            }
        }
    }

    /* Передаем данные в шаблон главной страницы: */
    if (!isset($page_content)) {
        $page_content = include_template('main.php', [
        'projects' => $projects,
        'tasks' => $tasks,
        'show_complete_tasks' => $show_complete_tasks,
        'task_search_null' => $task_search_null
]);
    }

    /* Передаем данные в шаблон вёрстки сайта: */
    $layout = include_template('layout.php', [
        'page_content' => $page_content,
        'title' => 'Дела в порядке',
        'user' => $user
    ]);
}

print($layout);
