<?php
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
?>