<?php
ob_start();


		//Файл, содержащий записи, оставленные в гостевой книге
		$file = 'records.txt';

		##Фильтрирует текст##
		function text($m)
		{   
			$m = trim($m);
			$m = htmlspecialchars($m);
			return $m;
		}

		if (isset($_REQUEST['send'])) {
		//Значения из POST
		$email = text($_POST['email']);
		$name = text($_POST['name']);
		$message = text($_POST['text']);


		if (empty($name)) {  
		$err = 'Введите ваше имя';
		}
		elseif (empty($message)) { 
		$err = 'Введите текст сообщения';
		}
		elseif (strlen($message) < 3 or strlen($message) > 5000) {
		$err = 'Длина сообщения должна быть в пределах 3 - 5000 символов';
		}
		

		##Если поля заполнены верно##
		if (!$err) {

		## Записываем данные в файл ##
		$data = array("name"=>$name,"message"=>$message,"time"=>time());
		$stroke = implode("|||", $data);
		file_put_contents($file, $stroke."\r\n", FILE_APPEND);
		
		header('location:index.php');
		
		}
		
		else {
		echo '<p align="center" style="color:#ff0000; font-weight:bold; background:#ffffff;">'.$err.'</p>';
		}
		
	
		
	}
	
	

		
		##Получаем общее количество строк файла##
        if(!file_exists($file))exit("Файл не найден"); 
		$row_array = file($file); //читаем содержимое файла и помещаем его в массив строк
		$record_count = count($row_array);
		
		
		//Количество записей выводимых на страницу
		$records_on_page = 10;
		
		//Общее количество страниц
		$pages_quantity = ceil($record_count/$records_on_page);
		
		// Если параметр не определен, то текущая страница равна 1
		$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

		// Если текущая страница меньше единицы, то страница равна 1
		if ($current_page < 1)
		{
			$current_page = 1;
		}
		// Если текущая страница больше общего количества страница, то текущая страница равна количеству страниц
		elseif ($current_page > $pages_quantity)
		{
		$current_page = $pages_quantity;
		}
		
		// Начать получение данных от числа (текущая страница - 1) * количество записей на странице
		$start_from = ($current_page - 1) * $records_on_page;
		
		
		/*Если номер выбранной страницы*количество записей на одной странице < общего количества записей в файле
		тогда - ограничитель = номер выбранной страницы*количество записей на одной странице-1
		иначе - ограничитель = общее количество записей в файле - 1*/
		
		if(($current_page*$records_on_page)<$record_count){
		$limit = $current_page*$records_on_page-1;
		}
		else {
		$limit = $record_count-1;
		}
		
				
			
?> 

		
				
<!DOCTYPE html>
<html>
  <head>
    <title>Гостевая книга</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  </head>
  <body>
      <div class="container">
        <header class="row">
            <h2><p class="text-center">Гостевая книга</p></h2>
        </header>
          <div class="row"><p class="text-center">Приветствуем вас в гостевой книге нашего сайта. Оставить свой комментарий Вы можете с помощью соответствующей формы ниже.</p></div>
        
		<div clas="row">
         <form action="" method="post">
                    <div class="form-group">
                    <label for="exampleInputName">Имя</label>
                    <input type="text" name="name" class="form-control" id="exampleInputName" placeholder="Имя">
                </div>
                <div class="form-group">
                    <label for="exampleInputText">Сообщение</label>
                    <textarea name="text" class="form-control" rows="5" id="exampleInputText" placeholder="Введите текст вашего сообщения"></textarea>
                </div>
                <button type="submit" name="send" class="btn btn-default">Отправить</button>
                </form>
		</div>
		<div clas="row"><p class="text-center"> </p></div>
		<div clas="row">
	<?php 
	echo "<table class=\"table\"><tbody><tr><th></th></tr></tbody></table>";
		for($i=$start_from; $i<=$limit; $i++) { 
		$record_array = explode("|||", $row_array[$i]);
		if (isset($record_array[2])){
		echo "<table class=\"table\"><tbody><tr><td><b>".$record_array[0]."</b> (".date('Y-m-d H:i:s', $record_array[2]).")</td></tr>";
        echo "<tr><td>".$record_array[1]."</td></tr></tbody></table>";
			}
		}
	echo "<table class=\"table\"><tbody><tr><th></th></tr></tbody></table>";
	?>
		</div>  
		
		
		
		<?php 
		echo "<div class=\"row\"><p class=\"text-center\">";
			for ($page = 1; $page <= $pages_quantity; $page++)
			{
				if ($page == $current_page)
			{
			echo '<strong>'.$page.'</strong> &nbsp;';
			}
			else
			{
			echo '<a href="?page='.$page.'">'.$page.'</a> &nbsp;';
			}
		}
		echo "</p></div>";
		?>
		
		<div clas="row"><p class="text-center">Copyright 2017</p></div>
		
		
      </div> 
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>