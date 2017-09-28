<?php //se ci sono problemi nella lettura dei parametri come CSV dalla prima riga di creaDB.sql usare host="localhost",user="root",pass="",db="tia"
	function Connect(&$connessione, &$database){ //connessione al database
			$ff= @fopen("./Files/createDB.sql","rb");
			if (!$ff)
			    $ff=fopen("../Files/createDB.sql","rb");
			$arr=fgetcsv($ff);
			$host = $arr[1]; 
			$user = $arr[2];
			if ( strlen($arr[3])==0 )
				$password = "";
			else 
				$password = $arr[3];
			$database = $arr[4];
			fclose($ff);
			//$connessione = mysql_connect($host, $user,$password) or die ("Connessione al database non riuscita: " . mysql_error());
			//mysql_select_db($database) or die ("Selezione  del database non riuscita: " . mysql_error());
			$connessione= new mysqli($host, $user,$password, $database);
			if ($connessione->connect_errno) {echo "failed connection";}
	}
?>