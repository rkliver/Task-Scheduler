<?php

/* функция- счетчик задач для текущего пользователя: */
function task_counter($tasks, $task_project): int
{
    $count = 0;
    foreach($tasks as $task) {
        if ($task['project_name'] == $task_project && $task['status'] == false) {
            $count ++;
        }
    }
    return $count;
}

function getPostVal($name)
{
    return filter_input(INPUT_POST, $name);
}

function define_correct_date($date)
{
    $analyzed_date = strtotime($date);
    $current_date = strtotime(date("Y-m-d"));
    $flag = false;

    if (0 <= $analyzed_date -  $current_date) {
        $flag = true;
    }

    return $flag;
};

function email_exists($email)
{
    $con = mysqli_connect("task-scheduler", "root", "", "task_shelder");
    $sql= "SELECT email FROM users WHERE email = ".'"'.$email.'"';
    $res = mysqli_query($con, $sql);
    $mail = mysqli_fetch_assoc($res);
    $check_email = $mail['email'];
    if ($check_email) {
        return true;
    }
};
