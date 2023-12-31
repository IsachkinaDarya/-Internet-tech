<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Добавление задачи</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container my-5" style = "background:rgb(216, 191, 216);padding:20px;border-radius:5px;width:700px;">
    <h2>Добавление задачи</h2>
    <form action = "add.php" method="post">
      <div class="mb-3">
        <label for="taskName" class="form-label">Наименование</label>
        <input type="text" class="form-control" name="taskName" id="taskName" required>
      </div>
      <div class="mb-3">
        <label for="taskDate" class="form-label">Дата</label>
        <input type="date" class="form-control" name="taskDate" id="taskDate" required>
      </div>
      <div class="mb-3">
        <label for="taskExecutor" class="form-label">Исполнитель</label>
        <select class="form-select" id="taskExecutor" name ="taskExecutor" required>
            <option selected>Выберите исполнителя</option>
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

            $user_id = $_COOKIE['user_id'];
            $sql = "SELECT * FROM public.users WHERE id='$user_id'";
            $result = $conn->query($sql);
            $user =  $result->fetch();
            $team = $user['team'];

            $sql = "SELECT * FROM public.users WHERE team = '$team' order by firstname";
            $result = $conn->query($sql);
            while ($row = $result->fetch()){ 
            $value = (string) $row['name'].' '. $row['firstname'];?>
            <option  value = <?php echo $row['id'];?>><?php echo $value.'-'.$row['post']; ?></option>>
            <?php }?>
        </select>
      </div>
      <div class="mb-3">
        <label for="taskDescription" class="form-label">Описание</label>
        <textarea class="form-control" id="taskDescription" name="taskDescription" rows="3" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Статус выполнения</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus1" value="Не начато" checked>
          <label class="form-check-label" for="taskStatus1">
            Не начато
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus2" value="В разработке">
          <label class="form-check-label" for="taskStatus2">
            В разработке
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus3" value="Завершено">
          <label class="form-check-label" for="taskStatus3">
            Завершено
          </label>
        </div>
      </div>
      <select name="taskTeam" id="taskTeam" style="display: none;">
        <option value="<?php echo $team ?>" selected="selected"></option>
      </select>
      <button type="submit" class="btn btn-outline-light" style="width:100%;">Добавить задачу</button>
  </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>