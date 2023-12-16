<?php
if(!isset($_COOKIE['user_id'])){
    header('Location: index.php?error=Доступ запрещен');
    exit();
}
// Подключение к базе данных
$conn = new PDO("pgsql:host='localhost';dbname='postgres'", 'postgres', '12345');

if(!$conn){
    die('Ошибка подключения');
}

// Получение данных из формы
$taskId = $_POST["taskId"];
$taskName = $_POST["taskName"];
$Date =  $_POST["taskDate"];
$taskDate = new DateTime($Date);
$taskExecutor = $_POST["taskExecutor"];
$taskDescription = $_POST["taskDescription"];
$taskStatus = $_POST["taskStatus"];
$dateres = $taskDate->format('Y-m-d H:i:s');

$sql = "SELECT * FROM public.users WHERE id='$taskExecutor'";
$res = $conn->query($sql);
if($tutor= $res->fetch()){
    $taskExecutor = $tutor['name'].' '.$tutor['firstname'];
}else{
    die('Пользователь не найден');
}

// Поиск пользователя в базе данных
$sql = "UPDATE public.tasks SET 
project='$taskName', date='$dateres', tutor='$taskExecutor', description = '$taskDescription', 
status='$taskStatus' WHERE id=$taskId";
if ($conn->query($sql)){
        header('Location: dashboard.php');
        exit();
    }else{
        header('Location: edit_task.php?error=Ошибка');
        exit();
    }
?>