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
$task_id= $_GET["id_task"];

// Поиск пользователя в базе данных
$sql = "delete FROM public.tasks where id='$task_id'";
if ($conn->query($sql)){
    header('Location: dashboard.php');
    exit();
} else{
    header('Location: delete.php?error=Ошибка удаления');
    exit();
}
?>