<?php
/* текущий пользователь: */
$user = 'Keks';
$project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);

$projects_for_user = "SELECT p.id, title, p.user_id, u.id as project_user
FROM projects p 
JOIN users u ON user_id = u.id 
WHERE login = '$user'
ORDER BY p.id";

$tasks_for_user = "SELECT t.title AS task_name, p.title AS project_name , status, date, file_path, t.user_id, project_id FROM tasks t 
JOIN users u ON t.user_id = u.id 
JOIN projects p ON t.project_id = p.id
WHERE u.login = '$user'
ORDER BY date";

$sort_tasks_by_project = "SELECT t.title AS task_name, p.title AS project_name , status, date, file_path, t.user_id, project_id FROM tasks t 
JOIN users u ON t.user_id = u.id 
JOIN projects p ON t.project_id = p.id
WHERE u.login = '$user' AND project_id = $project_id
ORDER BY date";

?>