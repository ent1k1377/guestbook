<?php
    include "db.php";
    connection_db();
    if (isset($_POST['send'])){
        $name = $_POST['name'];
        $review = $_POST['review'];
        $date = date('Y.m.d H:i:s', time());
        $query_insert = "INSERT INTO record (name, review, date) VALUES ('$name', '$review', '$date')";
        mysqli_query($link, $query_insert) or die(mysqli_error($link));
        mysqli_commit($link);
        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header ("Location: $url");
    }
    $query = 'SELECT COUNT(*) as c1 FROM record';
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
    $numberOfRecordsAll = $data[0]['c1'];
    $numberOfRecordsPerPage = 2;
    $num = ceil($numberOfRecordsAll / $numberOfRecordsPerPage);
    
    if (isset($_GET['page']) and ($_GET['page'] <= 0 or $_GET['page'] > $num)){
        header("Location: /");
    }
    if (isset($_GET['page']) and (!is_numeric($_GET['page']) or strpos('.', $_GET['page']) !== false)){
        header("Location: /");
    }
    
    
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Гостевая книга</title>
		<link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Гостевая книга</h1>
			<div>
				<nav>
				  <ul class="pagination">
<?php
    
    if (isset($_GET['page'])){
        $page = $_GET['page'];
    }
    else{
        $page = 1;
    }
    
    if ($_GET['page'] - 1 < 1){
        $class = "disabled";
    }
    else{
        $class = '';
    }
    $n = $_GET['page'] - 1;
    echo '<li class="'.$class.'">
                <a href="?page='.$n.'"  aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
        </li>';
					
    $query = 'SELECT COUNT(*) as c1 FROM record';
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
    $numberOfRecordsAll = $data[0]['c1'];
    $numberOfRecordsPerPage = 2;
    $num = ceil($numberOfRecordsAll / $numberOfRecordsPerPage);
    
    for ($i=1;$i<=$num;$i++){
        if ($i == $page){
            echo "<li class=\"active\"><a href=\"?page=$i\">$i</a></li>";
        }
        else{
            echo "<li><a href=\"?page=$i\">$i</a></li>";
        }
        
    }
    if ($_GET['page'] + 1 > $num){
        $class = "disabled";
    }
    else{
        $class = '';
    }
    $n = $page + 1;
    echo '<li class="'.$class.'">
            <a href="?page='.$n.'" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>';

					
?>
				  </ul>
				</nav>
			</div>
<?php
    
    $page = ($page - 1) * $numberOfRecordsPerPage;
    $query = "SELECT * FROM record ORDER BY date DESC LIMIT $page, $numberOfRecordsPerPage";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data=[]; $row = mysqli_fetch_assoc($result); $data[] = $row);
    foreach ($data as $elem){
        echo '<div class="note">
                <p>
                    <span class="date">'.str_replace('-', '.', $elem['date']).'</span>
                    <span class="name">'.$elem['name'].'</span>
                </p>
                <p>
                '.$elem['review'].'    
                </p>
            </div>';
    }

?>
            
			<div class="info alert alert-info" style="display: none;">
				Запись успешно сохранена!
			</div>
			<div id="form">
				<form action="" method="POST">
					<p><input class="form-control" name="name" placeholder="Ваше имя"></p>
					<p><textarea class="form-control" name="review" placeholder="Ваш отзыв"></textarea></p>
					<p><input type="submit" name='send' class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>
	</body>
</html>
