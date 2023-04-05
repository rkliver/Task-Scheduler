<?php
require_once ('helpers.php');
require_once ('init.php');
require_once ('sql.php');
require_once ('functions.php');

$show_complete_tasks = rand(0, 1);

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