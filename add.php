<?php
require_once('init.php');
require_once ('helpers.php');
require_once ('functions.php');

/* текущий пользователь: */
$user = 'Keks';

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

$page_content = include_template('form-task.php', ['projects' => $projects, 'tasks' => $tasks,]);
/*Получаем данные из формы добавления задачи*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $task_add['title'] = htmlspecialchars($_POST['name']);
    $task_add['project_id'] = filter_input(INPUT_POST, 'project', FILTER_SANITIZE_NUMBER_INT);
    $task_add['date'] = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_NUMBER_INT);
	$task_add['file_path'] = NULL;
    /*Проверяем наличие прикрепленного файла*/
    if (isset($_FILES['file']) && $_FILES['file']['size'] != 0) {
        $file_name = $_FILES['file']['name'];
	    $file_ext = pathinfo($file_name);
	    $current_date =date("Y-m-d-G-i-s");
	    $new_file_name = $current_date.'.'.$file_ext['extension'];
	    $file_path = __DIR__ . '/uploads/';
	    $task_add['file_path'] = '/uploads/' . $new_file_name;
	    
	    move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $new_file_name);
    }

    $errors = [];
    /*Валидируем название задачи*/
    if ($task_add['title'] == '') {
		$errors['title'] = 'Введите название';
	} else if (iconv_strlen($task_add['title']) > 255) {
		$errors['title'] = 'Длинна поля превышает максимально допустимую (255 символов)';
	}
    /*Валидируем проект, к которому относится  задача*/
    $sql = "SELECT id FROM projects WHERE id = ".$task_add['project_id'];
	$res = mysqli_query($con, $sql);
	$pro_id = mysqli_fetch_assoc($res);
	if ($pro_id == NULL) {
		$errors['project_id'] = 'Укажите существующий проект';
	}
    /*Валидируем указанную пользователем дату выполнения задачи*/
    if (!is_date_valid($task_add['date'])){
        $errors['date'] = 'Введите дату в формате ГГГГ-ММ-ДД';
    } else if ($task_add['date'] != '' and !define_correct_date($task_add['date'])) {
		$errors['date'] = 'Дата должна быть больше или равна текущей';
    }
    $errors = array_filter($errors);

    if (count($errors)) {
		$page_content = include_template('form-task.php', ['errors' => $errors, 'projects' => $projects, 'tasks' => $tasks,]);
	} else{
    $sql = "INSERT INTO tasks (title, project_id, date, file_path, user_id) VALUES (?, ?, ?, ?, 1)";
    
    $stmt = db_get_prepare_stmt($con, $sql, $task_add);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        header("Location: index.php");
    }else {
        $page_content = include_template('error.php', ['error' => mysqli_error($con)]);
    }
  }
}

$layout = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Дела в порядке',
    'user' => $user
]);

print($layout);
/* <?php print_r($_POST) ?> */
?>