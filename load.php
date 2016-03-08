<?php
	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

	$server = $url["host"];
	$username = $url["user"];
	$password = $url["pass"];
	$db = substr($url["path"], 1);
	
	$conn = new mysqli($server, $username, $password, $db);
		
	$user_id = $_GET['user_id'];	
	
	$result = $conn->query("select * from ify_push_messages where send_to = '$user_id' order by id desc limit 1");				
	$row_cnt = $result->num_rows;	
	
	while ($row = $result->fetch_assoc()) {
	   
	   $username =  $row['username'];
	   $message = $row['message'];	 
	}	
	
	if ($row_cnt > 0)
	{
		echo $username . " - " . $message;
		$remove = $conn->query("delete from ify_push_messages where send_to = '$user_id'");	
	}
	else 
		echo "";

?>