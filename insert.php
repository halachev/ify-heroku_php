<?php
	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

	$server = $url["host"];
	$username = $url["user"];
	$password = $url["pass"];
	$db = substr($url["path"], 1);
	
	$conn = new mysqli($server, $username, $password, $db);
	
	if ($conn)
		echo "mysql connected";	
	else
		throw new Exception($conn->error);
	
	$user_id = $_GET['user_id'];
	$send_to = $_GET['send_to'];	
	$username = $conn->real_escape_string(decodeHtmlEnt($_GET['username'])); 	
	$date = date('Y/m/d H:i:s');			
	$message = $conn->real_escape_string(decodeHtmlEnt($_GET['message'])); 		
	$state = $_GET['state'];	
	$versionCode = $_GET['versionCode'];
		
	if(!$username){
		echo json_encode('Invalid username');
		exit;
	} 
	
	if(!$message){
		echo json_encode('Invalid message');
		exit;
	} 
	
	if(!$user_id){
		echo json_encode('Invalid user_id');
		exit;
	} 
	
	if(!$send_to){
		echo json_encode('Invalid send_to');
		exit;
	} 
	
	$result = $conn->query("insert into ify_push_messages (user_id, send_to, username, date, message, state, versionCode) values('$user_id', '$send_to', '$username', '$date', '$message', '$state', '$versionCode') ");		
	
	function decodeHtmlEnt($str) {
		$ret = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
		$p2 = -1;
		for(;;) {
			$p = strpos($ret, '&#', $p2+1);
			if ($p === FALSE)
				break;
			$p2 = strpos($ret, ';', $p);
			if ($p2 === FALSE)
				break;
				
			if (substr($ret, $p+2, 1) == 'x')
				$char = hexdec(substr($ret, $p+3, $p2-$p-3));
			else
				$char = intval(substr($ret, $p+2, $p2-$p-2));
				
			
			$newchar = iconv(
				'UCS-4', 'UTF-8',
				chr(($char>>24)&0xFF).chr(($char>>16)&0xFF).chr(($char>>8)&0xFF).chr($char&0xFF) 
			);
			
			$ret = substr_replace($ret, $newchar, $p, 1+$p2-$p);
			$p2 = $p + strlen($newchar);
		}
		return $ret;
	}
	
?>