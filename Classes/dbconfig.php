<?php
	function Connect(&$connection, &$database){ //connection to db
			$ff = @fopen("./Files/createDB_freesql.sql","rb");
			if (!$ff)
			    $ff=fopen("../Files/createDB_freesql.sql","rb");
			if (!$ff) {			
			    $ff = @fopen("./Files/createDB_localhost.sql","rb");
			    if (!$ff)
			        $ff =fopen("../Files/createDB_localhost.sql","rb");
			} // github repo doesn't have *_freesql file (remote db), so use local db ( the abovementioned sql script file needs to be executed and the first line updated with the credentials of that database)
			$arr = fgetcsv($ff);
			$host = $arr[1]; 
			$user = $arr[2];
			if ( strlen($arr[3])==0 )
				$password = "";
			else 
				$password = $arr[3];
			$database = $arr[4];
			fclose($ff);
			$connection = new mysqli($host, $user,$password, $database);
			if ($connection->connect_errno) 
				{echo "failed connection";}
	}
?>