<?php
/* функция- счетчик задач для текущего пользователя: */
function task_counter($tasks, $task_project): int{
    $count = 0;
    foreach($tasks as $task){
        if ($task['project_name'] == $task_project && $task['status'] == false){
            $count ++;
        }
    }
    return $count;
}

function getPostVal($name) {
    return filter_input(INPUT_POST, $name);
}

function define_correct_date ($date) {
    $analyzed_date = strtotime($date);
    $current_date = strtotime(date("Y-m-d"));
    $flag = false;

    if (0 <= $analyzed_date -  $current_date) {
        $flag = true;
    }

    return $flag;
};
?>