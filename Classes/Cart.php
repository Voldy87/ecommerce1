<?php                 //CLASSE del carrello: OPERAZIONI SUL CARRELLo (spesso "Cart"): il Cart � una variabile di sessione, un array associativo: {[0->ProductID,1->ProductOccorrences], [..,..],....,[..,..]}
include_once 'dbconfig.php';

class Cart{   //cart: +,3 -,4 svuota,5 (obj)    
	  //il carrello � memorizzato nella variabile di sessione $_SESSION['carrello'] = {[PrID, NumPezzi], [PrID, NumPezzi],....[PrID, NumPezzi]}

	static $database;
   static $connessione;
	
	private static function Search($id_prodotto){ //se il ProductID � nell'array cart restituisce la sua chiave (intera), senn� ritorna false
		$cart="";
		if ((!(isset($_SESSION['carrello']))) || (sizeof($_SESSION['carrello'])==0))
			return false;
		else 
			$cart=$_SESSION['carrello'];   
		$len = sizeof($cart);
		$ret = false;
		for ($i=0;$i<$len;$i++){
			if ($cart[$i][0]==$id_prodotto){ //se lo trovo restituisco l'indice
				$ret=$i;
				break;
			}
		}
		return $ret;   //se il for non ha mai breakato ritorno false
	}
	
	public static function AddTo($quanti, $id_prodotto){ //aggiunge n (1� parametro) prodotti di un certo modello (via argomento-ProductID) al carrello
		$chiave=self::Search($id_prodotto);   
		if ($chiave===false){        //non ci sono prodotti di quel PrID nel cart
			   $vett[0]=$id_prodotto; // creo un vettore di 2 elementi che poi appender�: 
			   $vett[1]=$quanti;	  // vett=[PrID, NumPezzi]		 			
			   $_SESSION['carrello'][]=$vett; //append (ordine cronologico)	al vettore/carrello del vettore di cui sopra vett=[PrID, NumPezzi]
		}     			
		else  {  									//ci sono prodotti (almeno 1) di quel PrID nel cart
			   $_SESSION['carrello'][$chiave][1]+=$quanti;					//incremento la quantit� del prodotto
		}		  
	}
	
	public static function RemoveFrom($id_prodotto){//rimuove un prodotto (via argomento-ProductID) al carrello
		$chiave=self::Search($id_prodotto);
		if ($chiave!==false){    //se esiste un prodotto di quel tipo nel carrello
			unset($_SESSION['carrello'][$chiave]); //tolgo l'elemento [0->PrID,1->PrOccorrences]
			$_SESSION['carrello']=array_values($_SESSION['carrello']); //risistemo l'array associativo
		}
	}
	
	public static function Modify($newnum,$id_prodotto){ //aggiorna la quantit� (NUM) di un dato prodotto (ID) nel carrello
		if ( !is_numeric($newnum) || is_nan($newnum)  )
			return; 
		$chiave=self::Search($id_prodotto); 
		if ( ($chiave===false)||($newnum<0) ) // se il prodotto non � nel carrello o la quantit� � negativa mi fermo          
			return;							// a quel punto quando in index/carrello verr� richiamata la showCart in quantit� ci rimetto il valore rimasto memorizzato nel SESSION[carrello]		
		if ($newnum==0){  //se l'utente mette 0 significa che vuoel togliere quel prodotto dal carrello
				unset($_SESSION['carrello'][$chiave]);                          
				$_SESSION['carrello'] = array_values($_SESSION['carrello']); //risistemo l'array associativo
		} 
		else {		//modifica "normale"															
				 $v = $_SESSION['carrello'][$chiave];
			    $v[1]=$newnum;						
			    $_SESSION['carrello'][$chiave]=$v;
		} 
	}
	
	public static function Cancel(){     //svuota il carrello
		if ( (!isset($_SESSION['carrello'])) || (sizeof($_SESSION['carrello'])==0))
			return;
		else 
			unset($_SESSION['carrello']);
	}
	
	public static function Length(){            //ritorna il numero di prodotti nel carrello
		if (isset($_SESSION['carrello'])) {
			if (sizeof($_SESSION['carrello'])==0)
				return 0;
			$quanti=0;
			$vett=$_SESSION['carrello'];
			foreach ($vett as $value)
				$quanti+=$value[1];
			return $quanti;
		}
		return 0;
	}
	
	public static function ProductsIn($id_prodotto){        //ritorna il numero di prodotti di quel tipo (ID) presenti nel carrello
		if ( (!isset($_SESSION['carrello'])) || (sizeof($_SESSION['carrello'])==0) )
			return 0;
		$chiave=self::Search($id_prodotto); 
		if ($chiave===false)
			return 0;
		$cart = $_SESSION['carrello'];
		return $cart[$chiave][1];
	}

	public static function Show(){ 			//stampa i prodotti nel carrello e ritorna il prezzo totale
				if ( (!isset($_SESSION['carrello'])) || (sizeof($_SESSION['carrello'])==0) )		//carrello vuoto? esco
					return;              //ricordiamo che il vettore sessione carrello per ogni elemento ha ID e numero pezzi
				Connect($connessione, $database);
				$cart=$_SESSION['carrello'];
				$val=$cart[0][0]; 									//ID del 1� elemento nel carrello
				$query = "SELECT * FROM $database.`prodotti` WHERE (ID_prodotto=$val";
				unset($cart[0]);
				foreach ($cart as $value)   	//il ciclo parte dal (eventuale) secondo ProductID nel carrello
					$query.=" OR ID_prodotto=$value[0]";	//prelevo solo l'ID non i pezzi
				$query.=")"; 		//in questo modo la query mi seleziona tutti i prodotti che stanno nel carrello
				$ris = mysqli_query($connessione,$query);
				$prezzototale=0;
				while ($row = mysqli_fetch_array($ris)){
						$idprodotto=$row['ID_Prodotto'];
						$quanti=Cart::ProductsIn($idprodotto); //mi da quanti prodotti con quell'ID ci sono nel carrello
						$prezzototale+=(($row['Prezzo'])*$quanti); //aggiorno il costo tot del carrello
						$nomescarpa=strtr($row['Nome'],"_"," "); //prelevo il nome della scarpa dal nome del file
						$out =                      //il box di un prodotto nel carrello: nome, img, zoom, prezzo, quantit� (form)
<<<D
	  
	<div class="prodotto_cart" id="p$idprodotto"> 
		<div style="float:left; ">
			<img class="mini_img_prodotto" src="../Multimedia/ShoesImg/$row[Nome].jpg" title="$nomescarpa" alt="$nomescarpa">
			<span style="position:relative; left: -3px; bottom:7px; cursor:pointer; background-color:white; padding:0px" onClick="zoom('$row[Nome]')">
				<img title="Ingrandisci l'immagine" class="mini_zoom" src="../Multimedia/MiscImg/zoom.jpg" alt="zoom" >	
			</span>	
		 </div>	
		<div style="float:left; font-size:19pt; color: red; text-align:center; width:40%; margin-top:30px; margin-bottom:10px; height: auto; " >
			<b><i>$nomescarpa</i></b>
		</div>
		<div style="float:right;  text-align:center; font-weight:bold">
				<div>Prezzo:<br> <span style="color:red;">$row[Prezzo] &#8356;</span></div>
					<form name="form_quanti_p$idprodotto" action="index.php?p=carrello" method="post" style="width:61px; margin-top:7px">
						<div>
						<label for="in_$idprodotto">Quantit&agrave;: </label>
						<br>
						<input type="text" id="in_$idprodotto" name="numproducts" maxlength="2" size="1" style="text-align:center" value="$quanti" onChange="submit()" >
						<input type="hidden" name="PrID" value="$idprodotto" >
						</div>
					</form>
		</div>	
		<div style="float:right; cursor:pointer; text-align:center; margin-right:7%; margin-top:21px; width:15%; 
		            background-color:white; padding:1px" onClick="location.href='index.php?p=carrello&amp;obj=4&amp;id=$idprodotto'">
				 <img  class="mini_cart_img" src="../Multimedia/MiscImg/removefromcart.jpg"  title="Togli dal carrello"
					   alt="Togli dal carrello"  style="cursor:pointer" ><br>
					   <span style="font-size:8pt" >TOGLI DAL CARRELLO</span>
		</div>
	</div> 			 				
D;
						echo $out; //      se l'utente mette un valore non valido lato client ci metto -1 e faccio il submit in
				}				   //      modo che al successivo refresh la ModifyCart non faccia nulla e la ShowCart  
				mysqli_free_result($ris);// rimetta come quantit� quella ancora memorizzata nel file
				mysqli_close($connessione);
				return $prezzototale;
		}
		
}
?>