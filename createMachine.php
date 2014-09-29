<?php 

	define("CONF_DIR", "/app/scripts/spacewalk_conf");
	//first things first:
	header("HTTP/1.1 500 Internal Server Error", true, 500);

	$data = file_get_contents("php://input");
	
	$data = urldecode($data);
	$a = explode('&', urldecode($data));
	
	$post_data = array();
	for($i=0; $i < count($a); $i++) {
    	$b = split('=', $a[$i]);
    	$post_data[$b[0]] = $b[1];
	}

	$fullPath = join(DIRECTORY_SEPARATOR, array(CONF_DIR, $post_data['conf']));

    if( file_exists($fullPath) == FALSE){
    	throw new Exception("Specified file does not exist");
    }

    if(file_put_contents($fullPath, $post_data['machine'].PHP_EOL, FILE_APPEND) == FALSE){
    	throw new Exception("Specified file does not exist");
    }


	//last things last, only reached if code execution was not stopped by uncaught exception or some fatal error
	header("HTTP/1.1 200 OK", true, 200);
 ?>