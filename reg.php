<?php
// Подключение к базе данных PostgreSQL
$conn = new PDO("pgsql:host='localhost';dbname='postgres'", 'postgres', '12345');

if(!$conn){
    die('Ошибка подключения');
}

// Получение данных из POST запроса
$username = $_POST['username'];
$password = $_POST['password'];
$name = $_POST['name'];
$firstName = $_POST['firstName'];
$post = $_POST['post'];
$team = $_POST['team'];

// Запрос на вставку данных в таблицу
$sql = ("INSERT INTO public.users (username, password, name, firstname, post, team) VALUES ('$username', '$password', '$name', '$firstName', '$post', '$team')");
$insert = $conn->query($sql);
$conn = NULL;
$conn = new PDO("pgsql:host='localhost';dbname='postgres'", 'postgres', '12345');
if($insert){
    $sql = "SELECT * FROM public.users WHERE username='$username' and password='$password'";
    $res = $conn->query($sql);
    $user = $res->fetch();
    setcookie('user_id', $user['id'], time() + (86400 * 30), '/');
    header('Location: dashboard.php');
    exit(); 
}
else{
    // Неудачная регистрация
    header('Location: index.php?error=Ошибка регистрации');
    exit();
}

?>