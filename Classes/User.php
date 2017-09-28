<?php                   //CLASSE statica con le OPERAZIONI CHE COINVOLGONO IL DATABASE E LE TABELLE IVI PRESENTI                    
include_once 'dbconfig.php';
	
	class User{
		
		public static $REGIONI = array(1=>"Abruzzo",2=>"Basilicata",3=>"Calabria",4=>"Campania",5=>"Emilia-Romagna", //regioni ordinate alfabeticamente con indice a partire da 1
		6=>"Friuli-Venezia Giulia",7=>"Lazio",8=>"Liguria",9=>"Lombardia",10=>"Marche",
		11=>"Molise",12=>"Piemonte",13=>"Puglia",14=>"Sardegna",15=>"Sicilia",16=>"Toscana",
		17=>"Trentino-Alto Adige",18=>"Umbria",19=>"Valle d&#39;Aosta",20=>"Veneto");
		
		static $database;
   		static $connessione;
		
		public static function ID($mail){ //ret -1 if the user corresponding to that mail doesn't exist, otherwise return its ID
			Connect($connessione, $database);
			$query = "SELECT ID_Utente FROM $database.`utenti` WHERE Mail='$mail'";
			$ris = mysqli_query($connessione,$query);
			$ret = mysqli_fetch_array($ris);
			if ($ret=="") 
				$ret=(-1);
			else 
				$ret=$ret['ID_Utente'];
			mysqli_free_result($ris);
			mysqli_close($connessione);
			return $ret;
		}
		
		public static function PrintMailVector() { //stampa un vettore formato JS ("[...]") con le mail degli utenti registrati: serve nella registrazione per memorizzare lato client le mail già inserite
			Connect($connessione, $database);
			$query = "SELECT Mail FROM $database.`utenti`";
			$ris = mysqli_query($connessione,$query);
			$i=0;
			echo "[";
			while ($row = mysqli_fetch_array($ris)){ 
				if ($i!=0)
					echo ", ";
				$i++;
				$r= $row['Mail'];
				echo "'$r'";
			}
			echo "]";
			mysqli_free_result($ris);
			mysqli_close($connessione);
		}

		public static function Name($uid){ //username dell'utente il cui ID è passato come argomento
			Connect($connessione, $database);
			$query = "SELECT Nome FROM $database.`utenti` WHERE ID_Utente='$uid'";
			$ris = mysqli_query($connessione,$query);
			$n = mysqli_fetch_array($ris);
			mysqli_free_result($ris);
			mysqli_close($connessione); 
			return $n['Nome'];
		}
		
		public static function Password($uid){ //password dell'utente il cui ID è passato come argomento
			Connect($connessione, $database);
			$query = "SELECT Password FROM $database.`utenti` WHERE ID_Utente='$uid'";
			$ris = mysqli_query($connessione,$query);
			$p = mysqli_fetch_array($ris);
			mysqli_free_result($ris);
			mysqli_close($connessione); 
			return $p['Password'];
		}
		
		public static function Row($uid){ //restituisce l'array con tutti i valori dell'account dell'utente il cui ID è il parametro
			Connect($connessione, $database);
			$query = "SELECT * FROM $database.`utenti` WHERE ID_utente='$uid'";
			$ris = mysqli_query($connessione,$query);
			$ret = mysqli_fetch_assoc($ris);
			mysqli_free_result($ris);
			mysqli_close($connessione);
			return $ret;
		}
		
		public static function Data(){ //stampa una tabella con alcuni dati dell'account che esegue questa funzione
			if (!isset($_SESSION['loggedUID']))
				return;
			$uid=$_SESSION['loggedUID'];
			$array=User::Row($uid);
			unset($array['ID_Utente']); //tolgo le parti dell'array relative ai campi che non devo stampare
			unset($array['Newsletter']);
			unset($array['Sesso']);
			unset($array['Password']);
			if (!isset($array['Citta']))
				unset($array['Citta']);
			if (!isset($array['Regione']))
				unset($array['Regione']);
			echo "<table border='1' cellpadding='2' style='margin-left:2%'>"; //i valori sono inseriti in una tabella
			foreach ($array as $key=>$value)
				echo "<tr><td><b>$key</b></td><td>$value</td></tr>";
			echo "</table>";
		}
		
		public static function Insert(){ //inserisco un utente, restituendone l'ID, prendendo i dati dal POST (del form) 
			if ( (!isset($_POST['E-Mail'])) || (!isset($_POST['Sesso'])) || (!isset($_POST['Newsletter'])) || 
			   (!isset($_POST['CAP'])) || (!isset($_POST['Regione']))  || (!isset($_POST['Password'])) )
				return;
			Connect($connessione, $database);
			$query = "SELECT MAX(ID_Utente) as 'M' FROM $database.`utenti`";
			$ris = mysqli_query($connessione,$query);
			$v = mysqli_fetch_array($ris);
			$pr_id=$v['M']+1;   
			$posta=$_POST['E-Mail'];
			$sex="M";
			if ($_POST['Sesso']=="Femmina") //nel campo sesso del DB.utenti memorizzo solo M o F
				$sex="F"; 					 
			$NL=1; 
			if ($_POST['Newsletter']=="No")  //il campo newsletter del DB.utenti è un booleano
				$NL=0;			
			$query = "INSERT INTO $database.`utenti` VALUES 
					  ('$pr_id', '$_POST[Nome]', '$_POST[Cognome]', 
					   '$sex', '$_POST[Telefono]',";
			if ( (!isset($_POST['Citta'])) || ($_POST['Citta']=="") ) //se il campo facoltativo CITTA è stato riempito o meno la query di inserimento cambia
				$query.=" NULL,          '$_POST[CAP]',";
			else 					 
				$query.="'$_POST[Citta]','$_POST[CAP]',";
			$ind=$_POST['Regione'];
			$regioni=self::$REGIONI;
			if ($ind==0)  //se il campo facoltativo REGIONI è stato riempito o meno (è 0 di default: no regione)la query di inserimento cambia
				$query.= "NULL,            '$posta','$_POST[Password]', '$NL')"; 
			else         
				$query.="'$regioni[$ind]', '$posta','$_POST[Password]', '$NL')";
			$ris = mysqli_query($connessione,$query);
			mysqli_close($connessione);
			return $pr_id;
		}

		public static function ShowPurchases(){ //stampa i prodotti acquistati da quell'utente
				Connect($connessione, $database);
				if (!isset($_SESSION['loggedUID'])) 
					return;
				$uid = $_SESSION['loggedUID'];
				$query = "SELECT P.ID_Prodotto, P.Nome, A.Numero_Pezzi FROM $database.`acquisti` as A,$database.`prodotti` as P WHERE (P.ID_Prodotto=A.ID_Prodotto AND A.ID_Utente=$uid)";
				$ris = mysqli_query($connessione,$query);
				$i=0;
				while ($row = mysqli_fetch_array($ris)){
						if ($i==0) 
							echo "<h3 style='margin-left:1%'>These are the products you've bought 'til now:</h3><br>"; 
						$i++;
						$idprodotto = $row['ID_Prodotto'];
						$nomescarpa = strtr($row['Nome'],"_"," ");
						$quanti = $row['Numero_Pezzi'];
						$out =   //box acquisti: nome, quantità, immagine
<<<D
	  
	<div class="prodotto_buy" id="p$idprodotto"> 
		<div style="font-size:15pt; color: blue;" >
			<b>$quanti <i>$nomescarpa</i></b><br>
			<img class="mini_img_prodotto" src="../Multimedia/ShoesImg/$row[Nome].jpg" title="$nomescarpa" alt="$nomescarpa">
		 </div>	
	</div> 			 				
D;
						echo $out;
				}
				if ($i==0) 
					echo "<h3 style='margin-left:3%'>Why haven't you bought anything yet on our site??</h3><br><br><br>"; 
				mysqli_free_result($ris);
				mysqli_close($connessione);
		}	
			
}     

?>