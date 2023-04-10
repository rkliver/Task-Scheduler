<?php
session_start();
$con = mysqli_connect("task-scheduler", "root", "","task_shelder");
mysqli_set_charset($con, "utf8");
?>