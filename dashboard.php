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


//запрос к API
$curl = curl_init();
$url = "https://dog.ceo/api/breeds/image/random";
//curl_setopt($curl, CURLOPT_PUT, 1);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curl);
curl_close($curl);

$img = explode('"', $result);
//$img = explode(",", $mas[1]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задачник</title>
    <link rel="stylesheet"  href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="margin: 0 0 0 20px;">
        <div class="row" style="width:650px;">
            <div class="col">
                <img src ="<?php echo $img[3]; ?>" style="max-width:120px; max-height:100px;padding:3px 0 3px 0;border-radius:5px;"> </img> 
            </div>
            <div class="col">
            <h6 style="width:150px;margin: 20px 0 20px;">Добро пожаловать, <?php echo $user['username']; ?></h6>
            </div>
            <div class="col">
            <a href="logout.php" class="btn btn-outline-secondary" style="margin: 20px 0 20px;">Выход</a>
            </div>
            <div class="col">
            <a href="add_task.php" class="btn btn-danger" style="width:150px;margin: 20px 0 20px;">Добавить задачу</a>
            </div>
        </div>  
    </div>
        <div class="row" style="background:rgb(218, 112, 214);paddinr-bottom:100px;">
            <div class="col" style="margin:5px 0px 5px 0px;text-align:center;font-size:130%;font-weight:bolder;color:rgb(255, 240, 245);">
            Не начато
            </div>
            <div class="col" style="margin:5px 0px 5px 0px;text-align:center;font-size:130%;font-weight:bolder;color:rgb(255, 240, 245);">
            В разработке
            </div>
            <div class="col" style="margin:5px 0px 5px 0px;text-align:center;font-size:130%;font-weight:bolder;color:rgb(255, 240, 245);">
            Завершено
            </div>
        </div>
        <div class="row" style="background:rgb(255, 240, 245);border-top:dashed rgb(75, 0, 130);padding-top:10px;">
            <div class="col">
                <?php 
                $x = new PDO("pgsql:host='localhost';dbname='postgres'", 'postgres', '12345'); 
                $result = $x->query("SELECT * FROM public.tasks order by date");
                while ($row = $result->fetch()){ 
                if($row['status']=="Не начато"){ ?>
                
                <div class="card" style="height:200px;width:350px;background-color:rgb(162,107,243);border:solid;border-color:rgb(162,107,243);max-width:350px;max-height:250px;margin:1% 0% 1% 15%;border-radius:10px;">
                    <div class="card-body bg-light" style="border-radius:10px 10px 0px 0px;">
                        <h5 class="card-title" style="color:rgb(162,107,243);font-weight:800;"><?php echo $row['project']?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['date']?></h6>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['tutor']?></h6>
                        <p class="card-text" style="max-height:40px;white-space: nowrap;overflow: hidden; text-overflow: ellipsis;"><?php echo $row['description']?></p>
                    </div>
                    <div class="card-footer" style="color:rgb(255,255,255);font-weight:500;max-height:40px;padding:0;">
                    <div class="row"> 
                        <div class="col" style="max-width:40%;padding:5px 0 0 20px;"><?php echo $row['status']?></div>
                        <div class="col">
                            <form action="edit_task.php" method="get">
                                <select name="id_task" style="display: none;">
                                    <option value="<?php echo $row['id']?>" selected="selected"></option>
                                </select>
                                <button type="submit" class="btn btn-sm" style="padding-left:80%;">
                                <img src="img/free-icon-pencil-4439061.png" alt="Кнопка Редактировать" style="width:28px;height:28px;">
                                </button>
                            </form>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['id']?>">
                            <img src="img/free-icon-eraser-732430.png" alt="Кнопка Удалить" style="width:28px;height:28px;" >
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
                <?php }} ?>
            </div>
            <div class="col"> 
                <?php  
                    $result = $x->query("SELECT * FROM public.tasks order by date");
                    while ($row = $result->fetch()){ 
                    if($row['status']=="В разработке"){ ?>

                <div class="card" style="height:200px;width:350px;background-color:rgb(162,107,243);border:solid;border-color:rgb(162,107,243);max-width:350px;max-height:250px;margin: 1% 0% 1% 15%;border-radius:10px;">
                    <div class="card-body bg-light" style="border-radius:10px 10px 0px 0px;">
                        <h5 class="card-title" style="color:rgb(162,107,243);font-weight:800;"><?php echo $row['project']?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['date']?></h6>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['tutor']?></h6>
                        <p class="card-text" style="max-height:40px;white-space: nowrap;overflow: hidden; text-overflow: ellipsis;"><?php echo $row['description']?></p>
                    </div>
                    <div class="card-footer" style="color:rgb(255,255,255);font-weight:500;max-height:40px;padding:0;">
                    <div class="row"> 
                        <div class="col" style="max-width:40%;padding:5px 0 0 20px;"><?php echo $row['status']?></div>
                        <div class="col">
                            <form action="edit_task.php" method="get">
                                <select name="id_task" style="display: none;">
                                    <option value="<?php echo $row['id']?>" selected="selected"></option>
                                </select>
                                <button type="submit" class="btn btn-sm" style="padding-left:80%;">
                                <img src="img/free-icon-pencil-4439061.png" alt="Кнопка Редактировать" style="width:28px;height:28px;">
                                </button>
                            </form>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['id']?>">
                            <img src="img/free-icon-eraser-732430.png" alt="Кнопка Удалить" style="width:28px;height:28px;">
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
                <?php }} ?>
            </div>

            <div class="col"> 
                <?php  
                    $result = $x->query("SELECT * FROM public.tasks order by date");
                    while ($row = $result->fetch()){ 
                    if($row['status']=="Завершено"){ ?>

                <div class="card" style="height:200px;width:350px;background-color:rgb(162,107,243);border:solid;border-color:rgb(162,107,243);max-width:350px;max-height:250px;margin: 1% 0% 1% 15%;border-radius:10px;">
                    <div class="card-body bg-light" style="border-radius:10px 10px 0px 0px;">
                        <h5 class="card-title" style="color:rgb(162,107,243);font-weight:800;"><?php echo $row['project']?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['date']?></h6>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['tutor']?></h6>
                        <p class="card-text" style="max-height:40px;white-space: nowrap;overflow: hidden; text-overflow: ellipsis;"><?php echo $row['description']?></p>
                    </div>
                    <div class="card-footer" style="color:rgb(255,255,255);font-weight:500;max-height:40px;padding:0;">
                    <div class="row"> 
                        <div class="col" style="max-width:40%;padding:5px 0 0 20px;"><?php echo $row['status']?></div>
                        <div class="col">
                            <form action="edit_task.php" method="get">
                                <select name="id_task" style="display: none;">
                                    <option value="<?php echo $row['id']?>" selected="selected"></option>
                                </select>
                                <button type="submit" class="btn btn-sm" style="padding-left:80%;">
                                <img src="img/free-icon-pencil-4439061.png" alt="Кнопка Редактировать" style="width:28px;height:28px;">
                                </button>
                            </form>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['id']?>">
                            <img src="img/free-icon-eraser-732430.png" alt="Кнопка Удалить" style="width:28px;height:28px;">
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
                <?php }} ?>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>