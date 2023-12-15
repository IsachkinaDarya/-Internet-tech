<?php
// Проверка, авторизован ли пользователь
if(!isset($_COOKIE['user_id'])){
    header('Location: index.php?error=Доступ запрещен');
    exit();
}

// Подключение к базе данных
$conn = new PDO("pgsql:host='localhost';dbname='postgres'", 'postgres', '12345');

if(!$conn){
    die('Ошибка подключения: ');
}

// Получение данных пользователя из базы данных
$user_id = $_COOKIE['user_id'];
$sql = "SELECT * FROM public.users WHERE id='$user_id'";
$result = $conn->query($sql);

$user =  $result->fetch();
$count=0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления</title>
    <link rel="stylesheet"  href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="margin: 30px;">
        <div class="row" style="width:450px;">
            <div class="col">
            <h6 style="width:150px;">Добро пожаловать, <?php echo $user['username']; ?></h6>
            </div>
            <div class="col">
            <a href="logout.php" class="btn btn-outline-secondary">Выход</a>
            </div>
            <div class="col">
            <a href="add_task.php" class="btn btn-info" style="width:150px;">Добавить задачу</a>
            </div>
        </div>  
    </div>
        <div class="card-group" style="padding-top:10px;background:rgb(255, 240, 245);border-top:dashed rgb(75, 0, 130);">
            <row> </row>
            <?php $x = new PDO("pgsql:host='localhost';dbname='postgres'", 'postgres', '12345'); 
            $result = $x->query("SELECT * FROM public.tasks order by date");
            while ($row = $result->fetch()){
                if($count%4==0){ ?>
                <div class="row">
                <?php } ?>
            <div class="card" style="height:250px;width: 300px;background-color:rgb(162,107,243);border:solid;border-color:rgb(162,107,243);max-width:300px;max-height:250px;margin:10px 50px 5px 50px;border-radius:10px;">
                <div class="card-body bg-light" style="border-radius:10px 10px 0px 0px;width:270px;">
                    <h5 class="card-title" style="color:rgb(162,107,243);font-weight:800;"><?php echo $row['project']?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['date']?></h6>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['tutor']?></h6>
                    <p class="card-text"><?php echo $row['description']?></p>
                </div>
                <div class="card-footer" style="color:rgb(255,255,255);font-weight:500;">
                <div class="row"> 
                    <div class="col"><?php echo $row['status']?></div>
                    <div class="col">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['id']?>">
                        Удалить
                        </button>
                        <div class="modal fade" id="exampleModal<?php echo $row['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $row['id']?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:red;">Удаление</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="color:black;">
                                    Вы действительно хотите удалить задачу?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Назад</button>
                                    <form action="delete.php" method="get">
                                        <select name="id_task" style="display: none;">
                                            <option value="<?php echo $row['id']?>" selected="selected"></option>
                                        </select>
                                        <button type="submit" class="btn btn-outline-danger">Удалить</button>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <?php if($count%4==0){ ?>
            </div>
            <?php } ?>
            <?php }  ?>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>