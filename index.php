<?php
require_once ('helpers.php');
$show_complete_tasks = rand(0, 1);
$categories = ['Входящие','Учеба','Работа','Домашние дела','Авто'];
$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'date' => '01.12.2019',
        'category' => 'Работа',
        'completed' => false
    ],
    [
        'task' => 'Выполнить тестовое задание',
        'date' => '25.12.2019',
        'category' => 'Работа',
        'completed' => false
    ],
    [
        'task' => 'Сделать задание первого раздела',
        'date' => '21.12.2019',
        'category' => 'Учеба',
        'completed' => true
    ],
    [
        'task' => 'Встреча с другом',
        'date' => '22.12.2019',
        'category' => 'Входящие',
        'completed' => false    
    ],
    [
        'task' => 'Купить корм для кота',
        'date' => null,
        'category' => 'Домашние дела',
        'completed' => false    
    ],
    [
        'task' => 'Заказать пиццу',
        'date' => null,
        'category' => 'Домашние дела',
        'completed' => false      
    ]
];
function task_counter(array $tasks, string $project): int{
    $count = 0;
    foreach($tasks as $task) 
    {
        if ($project === $task['category']) 
        {
            $count ++;
        };
    };
    return $count;
};
$page_content = include_template('main.php', [
    'categories' => $categories,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$layout = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Дела в порядке',
    'username' => 'Константин'
]);
htmlspecialchars(print($layout));
?>