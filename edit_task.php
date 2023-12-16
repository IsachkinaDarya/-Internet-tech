<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Редактирование задачи</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php  
// Подключение к базе данных
$conn = new PDO("pgsql:host='localhost';dbname='postgres'", 'postgres', '12345');
if(!$conn){
    die('Ошибка подключения');
}

$id_task = $_GET['id_task'];
$sql = "SELECT * FROM public.tasks WHERE id='$id_task'";
$res = $conn->query($sql);
if($task = $res->fetch()){
}
else{
    die('Задача не найдена');
}
?>
  <div class="container my-5" style = "background:rgb(216, 191, 216);padding:20px;border-radius:5px;width:700px;">
    <h3>Редактирование задачи</h3>
    <form action = "edit.php" method="post">
        <select name="taskId" id="taskId" style="display: none;">
            <option value="<?php echo $task['id']?>" selected="selected"></option>
        </select>
      <div class="mb-3">
        <label for="taskName" class="form-label">Наименование</label>
        <input type="text" class="form-control" name="taskName" id="taskName" required value="<?php echo $task['project'] ?>">
      </div>
      <div class="mb-3">
        <label for="taskDate" class="form-label">Дата</label>
        <input type="date" class="form-control" name="taskDate" id="taskDate" required value="<?php echo $task['date'] ?>">
      </div>
      <div class="mb-3">
        <label for="taskExecutor" class="form-label">Исполнитель</label>
        <select class="form-select" id="taskExecutor" name ="taskExecutor" required>
            <?php
            $team = $task['team'];
            $sql = "SELECT * FROM public.users WHERE team='$team' order by firstname";
            $result = $conn->query($sql);
            while ($row = $result->fetch()){ 
            $value = (string) $row['name'].' '. $row['firstname'];
            if($task['tutor']==$row['name'].' '.$row['firstname']){ ?>
            <option  value = <?php echo $row['id'];?> selected> <?php echo $value.'-'.$row['post'];?> </option>
            <?php } 
            else{ ?>
            <option  value = <?php echo $row['id'];?>> <?php echo $value.'-'.$row['post'];?> </option>
            <?php } }?>
        </select>
      </div>
      <div class="mb-3">
        <label for="taskDescription" class="form-label">Описание</label>
        <textarea class="form-control" id="taskDescription" name="taskDescription" rows="3" required ><?php echo $task['description'] ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Статус выполнения</label>
        <div class="form-check">
            <?php
            if($task['status']=="Не начато"){?> 
          <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus1" value="Не начато" checked>
          <?php } else{ ?>
            <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus1" value="Не начато">
          <?php } ?>
          <label class="form-check-label" for="taskStatus1">
            Не начато
          </label>
        </div>
        <div class="form-check">
            <?php
            if($task['status']=="В разработке"){?> 
          <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus2" value="В разработке" checked>
          <?php } else{ ?>
            <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus2" value="В разработке">
            <?php } ?>
          <label class="form-check-label" for="taskStatus2">
            В разработке
          </label>
        </div>
        <div class="form-check">
            <?php
            if($task['status']=="Завершено"){?> 
          <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus3" value="Завершено" checked>
          <?php } else{ ?>
            <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus3" value="Завершено">
            <?php } ?>
          <label class="form-check-label" for="taskStatus3">
            Завершено
          </label>
        </div>
      </div>
      <button type="submit" class="btn btn-outline-light" style="width:100%;">Изменить задачу</button>
  </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>