<?php                  //Classe Wishlist con le OPERAZIONI SULLA WISHLIST (spesso "WL"): la WL viene tenuta in una variabile di sessione per gli accessi frequenti (utente loggato), e sul DB per non perderla in caso di uscita dal sito e utenti non attualmente loggati
include_once 'dbconfig.php';

class Wishlist{   //cancelWL:0 wishlist+:1 wishlist-:2 ;    se un utente ha WL vuota la VarSess � vuota e nel file non c'� traccia di lui
   // la wishlist viene memorizzata nell'omonima sessionVar (array di PrId) e nel DB associando 
   // ad ogni UID una stringa di prodotti PrID.PrID. ... .PrID.
   
   static $database;
   static $connessione;
  
	 
	 public static function CreateEmpty($id_utente){
	 	Connect($connessione, $database);
	 	$query = "INSERT INTO $database.`wishlist` VALUES ('$id_utente', '')";
		$ris = mysqli_query($connessione,$query);
	 	mysqli_close($connessione);
	 }
	 
	 public static function GetProducts($id_utente) {  //restituisce un vettore con gli ID dei prodotti nella WL di quell'utente
			Connect($connessione, $database);
			$query = "SELECT Lista_Prodotti as 'L' FROM $database.`wishlist` WHERE ID_Utente='$id_utente'";
			$wlproducts = mysqli_query($connessione,$query);
			$wlarr = mysqli_fetch_array($wlproducts);	
			$vett = explode(".", $wlarr['L']);
			$vett=array_slice($vett, 0, sizeof($vett)-1);
			mysqli_free_result($wlproducts);
			mysqli_close($connessione);	
			return $vett;
	 } 

	 public static function GetLength($id_utente) {  //number of products in the WL of the specified user
			Connect($connessione, $database);
			$query = "SELECT Lista_Prodotti as 'L' FROM $database.`wishlist` WHERE ID_Utente='$id_utente'";
			$wlproducts = mysqli_query($connessione,$query);
			$wlarr = mysqli_fetch_array($wlproducts);	
			$vett = explode(".", $wlarr['L']);
			$vett=array_slice($vett, 0, sizeof($vett)-1);
			mysqli_free_result($wlproducts);
			mysqli_close($connessione);	
			return sizeof($vett);
	 } 	 
	  	
	 public static function AddTo($id_prodotto){//aggiorno la wishlist dell'utente loggato con il prodotto dall'ID specificato; devo fare un doppio aggiornamento, SessVar e FIle		 */
		$UID=$_SESSION['loggedUID'];
		Connect($connessione, $database);
		$query = "UPDATE $database.`wishlist` SET Lista_Prodotti=concat(Lista_Prodotti, '$id_prodotto.') 
		          WHERE ID_Utente='$UID'"; //aggiornamento DB
		$ris = mysqli_query($connessione,$query);
		mysqli_close($connessione);
	}
	
	public static function RemoveFrom($id_prodotto){//elimino un prodotto (ha ID come argomento) dalla wishlist dell'utente loggato
		$UID=$_SESSION['loggedUID'];
		Connect($connessione, $database);      
		$query = "UPDATE $database.`wishlist` SET Lista_Prodotti=REPLACE(Lista_Prodotti, '$id_prodotto.', '') 
		          WHERE ID_Utente='$UID'"; 
		$ris = mysqli_query($connessione,$query);
		mysqli_close($connessione);
	}
	
	public static function Cancel(){ // svuota  la riga del DB della WL dell'utente loggato
		Connect($connessione, $database);   
		$uid=$_SESSION['loggedUID'];
		$query = "UPDATE $database.`wishlist` SET Lista_Prodotti=''	WHERE ID_Utente='$uid'"; 
		$ris = mysqli_query($connessione,$query);
		mysqli_close($connessione);
	}
	
	public static function Show(){ //stampa i prodotti nella Lista dei Desideri dell'utente loggato
				$id_utente=$_SESSION['loggedUID'];
				$vett = self::GetProducts($id_utente);
				Connect($connessione, $database); 
				$query = "SELECT * FROM $database.`prodotti` WHERE (ID_prodotto='-1'";
				foreach ($vett as $value)
					$query.=" OR ID_prodotto='$value'";	
				$query.=")";
				$ris = mysqli_query($connessione,$query);
				while ($row = mysqli_fetch_array($ris)){
						$idprodotto=$row['ID_Prodotto'];
						$nomescarpa=strtr($row['Nome'],"_"," ");
						$out =  //box prodotto in WL: img, name, prezzo, zoom, toglidaWL, addtoCart
<<<D
	<div class="prodotto" id="p$idprodotto"> 
		<img class="img_prodotto" src="../Multimedia/ShoesImg/$row[Nome].jpg" title="$nomescarpa" alt="$nomescarpa">
		<img title="Ingrandisci l'immagine" class="zoom" src="../Multimedia/MiscImg/zoom.jpg" alt="zoom" onClick="zoom('$row[Nome]')">
		<div style="position:relative; bottom:20px;">
			<img  class="cart_img" src="../Multimedia/MiscImg/addtocart.jpg"  title="Aggiungi al carrello"
				  alt="Aggiungi al carrello"  style="cursor:pointer" onClick="location.href='index.php?p=lista desideri&amp;obj=3&amp;id=$idprodotto'">
			<div style="position:relative; width:181px; left:40px; text-align:center; font-weight:bold">
				<span><i>$nomescarpa</i></span><br>
				<span>$row[Prezzo] &#8356;</span>
			</div>
			<img  class="wishlist_img" src="../Multimedia/MiscImg/removefromwishlist.png"  title="Togli dalla tua Lista Desideri"
				  alt="Togli dalla tua Lista Desideri" onClick="location.href='index.php?p=lista desideri&amp;obj=2&amp;id=$idprodotto'">
		</div>
	</div> 			 				
D;
						echo $out;
				}
				mysqli_free_result($ris);
				mysqli_close($connessione);
		}
}
?>