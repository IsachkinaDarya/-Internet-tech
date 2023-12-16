<?php
// Подключение к базе данных
$conn = new PDO("pgsql:host='localhost';dbname='postgres'", 'postgres', '12345');

if(!$conn){
    die('Ошибка подключения');
}

// Получение данных из формы
$taskName = $_POST["taskName"];
$Date =  $_POST["taskDate"];
$taskDate = new DateTime($Date);
$taskExecutor = $_POST["taskExecutor"];
$taskDescription = $_POST["taskDescription"];
$taskStatus = $_POST["taskStatus"];
$dateres = $taskDate->format('Y-m-d H:i:s');
$taskTeam = $_POST['taskTeam'];

// Поиск пользователя в базе данных
$sql = "SELECT * FROM public.users WHERE id='$taskExecutor'";
$result = $conn->query($sql);
if($row = $result->fetch()){
    $taskExecutor = $row['name'].' '.$row['firstname'];
}
$sql = "INSERT INTO public.tasks (
    project, tutor, date, status, description, team) VALUES (
        '$taskName', '$taskExecutor', 
        '$dateres', '$taskStatus', '$taskDescription', '$taskTeam');";
if ($conn->query($sql)){
        header('Location: dashboard.php');
        exit();
    }else{
        header('Location: add_task.php?error=Ошибка');
        exit();
    }
?>