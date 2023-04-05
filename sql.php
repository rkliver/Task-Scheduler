<?php
/* текущий пользователь: */
$user = 'Keks';
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
?>