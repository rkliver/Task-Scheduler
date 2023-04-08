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

function validate_project($id, $allowed_list) {
    if (!in_array($id, $allowed_list)) {
        return "Указан несуществующий проект";
    }

    return null;
}

function validate_length($value, $min, $max) {
    if ($value) {
        $len = strlen($value);
        if ($len <= $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }

    return null;
}

function getPostVal($name) {
    return filter_input(INPUT_POST, $name);
}

?>