<?php
// Подключение к базе данных
$conn = new PDO("pgsql:host='localhost';dbname='postgres'", 'postgres', '12345');

if(!$conn){
    die('Ошибка подключения');
}
// Получение данных из формы
$username = $_POST["username"];
$password = $_POST["password"];

// Поиск пользователя в базе данных
$sql = "SELECT * FROM public.users";
$result = $conn->query($sql);
while ($row = $result->fetch()){
    if($username==$row["username"] && $password==$row["password"]){
        // Успешная авторизация
        $user = $row;
        setcookie('user_id', $user['id'], time() + (86400 * 30), '/');
        header('Location: dashboard.php');
        exit();
    }else{
        // Неудачная авторизация
        header('Location: index.php?error=Неверные данные');
        exit();
    }
}
?>