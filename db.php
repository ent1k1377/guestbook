<?php
	function connection_db($host='localhost', $user='root', $password='', $db_name='guestbook'){
		global $link;
		$link = mysqli_connect($host, $user, $password, $db_name);
		mysqli_query($link, "SET NAMES 'utf8'");
	} 
	/*include "second.php";
		connection_db();
		$query = "SELECT * FROM workers";
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
		for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
	foreach ($data as $row){
		echo "<tr>
				<th>{$row['id']}</th>
				<th>{$row['name']}</th>
				<th>{$row['age']}</th>
				<th>{$row['salary']}</th>
			</tr>";
		
	} */
?>