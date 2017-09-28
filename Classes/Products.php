<?php  
include_once 'dbconfig.php';

class Products {
	
	static $database;
   static $connessione;
	
	public static function Show(){ //mostra tutti i prodotti nel database
			$loggato=(isset($_SESSION['logged']))&&($_SESSION['logged']);
			if ($loggato)
				$vett =Wishlist::GetProducts($_SESSION['loggedUID']);
			Connect($connessione, $database);
			$query = "SELECT * FROM $database.`prodotti`";
			$ris = mysqli_query($connessione,$query);
			while ($row = mysqli_fetch_array($ris)){ //ogni ciclo � riferito ad un prodotto
				$idprodotto=$row['ID_Prodotto'];
				$display="style='display:none'";
				$wishlist_icon="";
				$wishlist_add_icon=
<<<E
			<img  class="wishlist_img" src="../Multimedia/MiscImg/addtowishlist.jpg"  title="Aggiungi alla tua Lista Desideri"
		          alt="Aggiungi alla tua Lista Desideri" onClick="location.href='index.php?obj=1&amp;id=$idprodotto#p$idprodotto'">
E;
				$wishlist_rem_icon=
<<<F
			<img  class="wishlist_img" src="../Multimedia/MiscImg/removefromwishlist.png"  title="Togli dalla tua Lista Desideri"
		          alt="Togli dalla tua Lista Desideri" onClick="location.href='index.php?obj=2&amp;id=$idprodotto#p$idprodotto'">
F;
				if($loggato)  {
					$inWL=in_array($idprodotto, $vett);
					$display=($inWL)?(""):("style='display:none'");  //c'� la stella"?
					$wishlist_icon=($inWL)?$wishlist_rem_icon:$wishlist_add_icon;
				}              //per la "stella"-WL
				$nomescarpa=strtr($row['Nome'],"_"," ");
				$out =  //box prodotto: eventuali bottini WL, img, name, price, bottone Cart, zoom
<<<D
	<div class="prodotto" id="p$idprodotto"> 
	<img class="img_prodotto" src="../Multimedia/ShoesImg/$row[Nome].jpg" title="$nomescarpa" alt="$nomescarpa">
	<img title="Ingrandisci l'immagine" class="zoom" src="../Multimedia/MiscImg/zoom.jpg" alt="zoom" onClick="zoom('$row[Nome]')">
	<img title="Prodotto presente nella tua Lista Desideri" class="wishlist_star" src="../Multimedia/MiscImg/star.jpg" 
		 alt="Prodotto presente nella tua Lista Desideri" onClick="location.href='index.php?p=lista desideri'" 
		 $display>
	<div style="position:relative; bottom:8px;">
		<img  class="cart_img" src="../Multimedia/MiscImg/addtocart.jpg"  title="Aggiungi al carrello"
			  alt="Aggiungi al carrello"  style="cursor:pointer" onClick="location.href='index.php?&amp;obj=3&amp;id=$idprodotto'">
		<div class="etichetta_prodotto">
			<span><i>$nomescarpa</i></span><br>
			<span>$row[Prezzo] &#8356;</span>
		</div>
		$wishlist_icon
	</div>
	</div> 				 				
D;
				echo $out;
			}
			mysqli_free_result($ris);
			mysqli_close($connessione);
		}	
		
	public static function ShowSearched($stringa){ //mostra i prodotti (ne ritorna il numero) che contengono nel nome la stringa passata come argomento
			Connect($connessione, $database);            //connessione
			$vett=explode(" ", $stringa);   //array che contiene tutte le parole (spazi come separatori 
			$query = "SELECT * FROM $database.`prodotti` WHERE Nome LIKE '%$vett[0]%' "; 
			foreach ($vett as $key=>$value) { //gestisco la stringa di ricerca con gli spazi
				if ($key==0) 
					continue;
				else 
					$query.=" AND Nome LIKE '%$value%'";
			} 
			$ris = mysqli_query($connessione, $query);
			mysqli_close($connessione);
			$num=0;    //numero di prodotti nella ricerca
			echo "<br>";
			while ($row = mysqli_fetch_array($ris)){ 
				$idprodotto = $row['ID_Prodotto'];
				$num++;
				$wishlist_icon;
				$display;
				$wishlist_add_icon= //nel QS obj serve per dire di fare addW, id l'id dle prodotto da aggiungere alla WL, c per ricaricare il filtro
<<<E
			<img  class="wishlist_img" src="../Multimedia/MiscImg/addtowishlist.jpg"  title="Aggiungi alla tua Lista Desideri"
		          alt="Aggiungi alla tua Lista Desideri" onClick="location.href='index.php?obj=1&amp;id=$idprodotto&amp;c=$stringa'">
E;
				$wishlist_rem_icon= //nel QS obj serve per dire di fare remWL, id l'id dle prodotto da togliere dalla WL, c per ricaricare il filtro
<<<F
			<img  class="wishlist_img" src="../Multimedia/MiscImg/removefromwishlist.png"  title="Togli dalla tua Lista Desideri"
		          alt="Togli dalla tua Lista Desideri" onClick="location.href='index.php?obj=2&amp;id=$idprodotto&amp;c=$stringa'">
F;
				$loggato=(isset($_SESSION['logged']))&&($_SESSION['logged']);
				if ($loggato) {
					$arr =Wishlist::GetProducts($_SESSION['loggedUID']);
					$inWL=in_array($idprodotto, $arr);
					$display=($inWL)?(""):("style='display:none'");
					$wishlist_icon=($inWL)?$wishlist_rem_icon:$wishlist_add_icon;
				}
				else {
					$wishlist_icon='';
					$display="style='display:none'";				
				}
				$nomescarpa=strtr($row['Nome'],"_"," "); //siccome i nomi scarpa hanno _ invece di " " li scambio
				$out =  //il box del prodotto: immagine, zoom, bottoneWL(eventuale), bottoneCart, nome, prezzo
<<<D
	<div class="prodotto" id="p$idprodotto"> 
	<img class="img_prodotto" src="../Multimedia/ShoesImg/$row[Nome].jpg" title="$nomescarpa" alt="$nomescarpa">
	<img title="Ingrandisci l'immagine" class="zoom" src="../Multimedia/MiscImg/zoom.jpg" alt="zoom" onClick="zoom('$row[Nome]')">
	<img title="Prodotto presente nella tua Lista Desideri" class="wishlist_star" src="../Multimedia/MiscImg/star.jpg" 
		 alt="Prodotto presente nella tua Lista Desideri" onClick="location.href='index.php?p=lista desideri'" 
		 $display>
	<div style="position:relative; bottom:20px;">
		<img  class="cart_img" src="../Multimedia/MiscImg/addtocart.jpg"  title="Aggiungi al carrello"
			  alt="Aggiungi al carrello"  style="cursor:pointer" onClick="location.href='index.php?&amp;obj=3&amp;id=$idprodotto&amp;c=$stringa'">
		<div class="etichetta_prodotto">
			<span><i>$nomescarpa</i></span><br>
			<span>$row[Prezzo] &#8356;</span>
		</div>
		$wishlist_icon
	</div>
	</div> 				 				
D;
				echo $out;
			}
			mysqli_free_result($ris);
			return $num;
		}
		
	public static function ShowBestseller(){ //mostra i(l) prodotti(o) pi� venduti(o)
			Connect($connessione, $database); //Query innestate per sapere quale �/sono i/il prodotto/i pi� venduto/i
			$query = "SELECT ID_Prodotto FROM $database.`acquisti` WHERE Numero_Pezzi= (SELECT MAX(Numero_Pezzi) FROM $database.`acquisti`)";
			$ris_max = mysqli_query($connessione,$query) or die ("Query fallita: " . mysqli_error()); //query (su acquisti) per sapere il PrId del prodotto pi� venduto
			$first=true; 
			while ($row = mysqli_fetch_array($ris_max)){ 
				$val=$row ['ID_Prodotto'];
				if ($first){
					$query = "SELECT * FROM $database.`prodotti` WHERE (ID_Prodotto=$val";
					$first=false;
				}
				else {
					$query.=" OR ID_Prodotto=$val";
				}
			}
			$query.=")"; //query (sui prodotti) per sapere i dati dei prodotti pi� acquistati (via i loro PrId ottenuto con la query di prima)
			mysqli_free_result($ris_max);
			$ris_prods = mysqli_query($connessione,$query);	
			$loggato=(isset($_SESSION['logged']))&&($_SESSION['logged']);
			if ($loggato) {
				$vett =Wishlist::GetProducts($_SESSION['loggedUID']);
				Connect($connessione, $database); //poiche' la funzione prima chiude la connessione
			}
			while ($row = mysqli_fetch_array($ris_prods)){  //il box prodotto � uguale a quello di "tutti i prodotti"/homepage	 			
				$idprodotto=$row['ID_Prodotto'];
				$wishlist_add_icon=
<<<E
			<img  class="wishlist_img" src="../Multimedia/MiscImg/addtowishlist.jpg"  title="Aggiungi alla tua Lista Desideri"
		          alt="Aggiungi alla tua Lista Desideri" onClick="location.href='index.php?p=bestseller&amp;obj=1&amp;id=$idprodotto#p$idprodotto'">
E;
				$wishlist_rem_icon=
<<<F
			<img  class="wishlist_img" src="../Multimedia/MiscImg/removefromwishlist.png"  title="Togli dalla tua Lista Desideri"
		          alt="Togli dalla tua Lista Desideri" onClick="location.href='index.php?p=bestseller&amp;obj=2&amp;id=$idprodotto#p$idprodotto'">
F;
				if ($loggato)	{			
					$inWL=in_array($idprodotto, $vett);
					$display=($inWL)?(''):("style='display:none'");
					$wishlist_icon=($inWL)?$wishlist_rem_icon:$wishlist_add_icon;
				}
				else {
					$display="style='display:none'";
					$wishlist_icon='';
				}
				$nomescarpa=strtr($row['Nome'],"_"," ");
				$out =   //box dei prodotti bestseller: nome, immagine, zoom, addtoCart, pulsanteWL
<<<D
	<div class="prodotto" id="p$idprodotto"> 
	<img class="img_prodotto" src="../Multimedia/ShoesImg/$row[Nome].jpg" title="$nomescarpa" alt="$nomescarpa">
	<img title="Ingrandisci l'immagine" class="zoom" src="../Multimedia/MiscImg/zoom.jpg" alt="zoom" onClick="zoom('$row[Nome]')">
	<img title="Prodotto presente nella tua Lista Desideri" class="wishlist_star" src="../Multimedia/MiscImg/star.jpg" 
		 alt="Prodotto presente nella tua Lista Desideri" onClick="location.href='index.php?p=lista desideri'" 
		 $display>
	<div style="position:relative; bottom:8px;">
		<img  class="cart_img" src="../Multimedia/MiscImg/addtocart.jpg"  title="Aggiungi al carrello"
			  alt="Aggiungi al carrello"  style="cursor:pointer" onClick="location.href='index.php?p=bestseller&amp;obj=3&amp;id=$idprodotto'">
		<div class="etichetta_prodotto">
			<span><i>$nomescarpa</i></span><br>
			<span>$row[Prezzo] &#8356;</span>
		</div>
		$wishlist_icon
	</div>
	</div> 				 				
D;
				echo $out;
			}
			mysqli_free_result($ris_prods);
			mysqli_close($connessione);
	}
		
	public static function ShowNew(){ //mostra i nuovi prodotti (a mia discrezione)
			$loggato=(isset($_SESSION['logged']))&&($_SESSION['logged']);
			if ($loggato) 
				$vett =Wishlist::GetProducts($_SESSION['loggedUID']);
			Connect($connessione, $database); //ho scelto io quali sono i prodotti "nuovi", arbitrariamente quelli con id 2,9,5
			$query = "SELECT * FROM $database.`prodotti` WHERE (ID_Prodotto=2 OR ID_Prodotto=9 OR ID_Prodotto=5)";
			$ris = mysqli_query($connessione,$query);
			while ($row = mysqli_fetch_array($ris)){ //il box prodotto � uguale a quello di "tutti i prodotti"/homepage
				$idprodotto = $row['ID_Prodotto'];
				$wishlist_add_icon=
<<<E
			<img  class="wishlist_img" src="../Multimedia/MiscImg/addtowishlist.jpg"  title="Aggiungi alla tua Lista Desideri"
		          alt="Aggiungi alla tua Lista Desideri" onClick="location.href='index.php?p=novita&amp;obj=1&amp;id=$idprodotto#p$idprodotto'">
E;
				$wishlist_rem_icon=
<<<F
			<img  class="wishlist_img" src="../Multimedia/MiscImg/removefromwishlist.png"  title="Togli dalla tua Lista Desideri"
		          alt="Togli dalla tua Lista Desideri" onClick="location.href='index.php?p=novita&amp;obj=2&amp;id=$idprodotto#p$idprodotto'">
F;
				if ($loggato)	{			
					$display=(in_array($idprodotto, $vett))?(""):("style='display:none'");
					$wishlist_icon=(in_array($idprodotto, $vett))?$wishlist_rem_icon:$wishlist_add_icon;
				}
				else {
					$display="style='display:none'";
					$wishlist_icon='';
				}
				
				$nomescarpa=strtr($row['Nome'],"_"," ");
				$out =
<<<D
	<div class="prodotto" id="p$idprodotto"> 
	<img class="img_prodotto" src="../Multimedia/ShoesImg/$row[Nome].jpg" title="$nomescarpa" alt="$nomescarpa">
	<img title="Ingrandisci l'immagine" class="zoom" src="../Multimedia/MiscImg/zoom.jpg" alt="zoom" onClick="zoom('$row[Nome]')">
	<img title="Prodotto presente nella tua Lista Desideri" class="wishlist_star" src="../Multimedia/MiscImg/star.jpg" 
		 alt="Prodotto presente nella tua Lista Desideri" onClick="location.href='index.php?p=lista desideri'" 
		 $display>
	<div style="position:relative; bottom:8px;">
		<img  class="cart_img" src="../Multimedia/MiscImg/addtocart.jpg"  title="Aggiungi al carrello"
			  alt="Aggiungi al carrello"  style="cursor:pointer" onClick="location.href='index.php?p=novita&amp;obj=3&amp;id=$idprodotto'">
		<div class="etichetta_prodotto">
			<span><i>$nomescarpa</i></span><br>
			<span>$row[Prezzo] &#8356;</span>
		</div>
		$wishlist_icon
	</div>
	</div> 				 				
D;
				echo $out;
			}
			mysqli_free_result($ris);
			mysqli_close($connessione);
	}	
		
	public static function ShowOrder(){ //stampa i prodotti che costituiscono l'ordine, con la scelta delle taglie, ne ritorna il prezzo 
				if ( (!isset($_SESSION['carrello'])) || (sizeof($_SESSION['carrello'])==0) )
					return;
				Connect($connessione, $database);
				$cart=$_SESSION['carrello'];
				$val=$cart[0][0];
				$query = "SELECT * FROM $database.`prodotti` WHERE (ID_prodotto=$val"; 
				unset($cart[0]);
				foreach ($cart as $value)  
					$query.=" OR ID_prodotto=$value[0]";	
				$query.=")"; //seleziono tutti i campi di tutti i prodotti nel carrello
				$ris = mysqli_query($connessione,$query);
				$prezzototale=0;
				while ($row = mysqli_fetch_array($ris)){ //genero un box prodotto pero ognuno dei suddetti
						$idprodotto=$row['ID_Prodotto'];
						$quanti=Cart::ProductsIn($idprodotto);
						$prezzototale+=(($row['Prezzo'])*$quanti);
						$prezzoparziale=$row['Prezzo']*$quanti;
						$nomescarpa=strtr($row['Nome'],"_"," ");
						$out =    //box dei prodotti durante la prima fase d'acquisto: immagine, numero pezzi, prezzi(2), taglie
<<<D
	<div class="prodotto_order" id="acquistop$idprodotto"> 
		<div style="float:left; font-size:1p6; color:red; text-align:center; width:28%; margin-top:20px; margin-bottom:10px; height: auto; padding: 0px 1px" >
			<b>$quanti<br><i>$nomescarpa</i></b>
		</div>
		<div style="float:left">
			<img class="img_prodotto_order" src="../Multimedia/MiscImg/$row[Nome].jpg" title="$nomescarpa" alt="$nomescarpa">
		 </div>	
		<div style="float:left;  text-align:center; font-weight:bold">
	         Prezzo unitario:<span style="color:red">$row[Prezzo] &#8356;</span>&nbsp;&nbsp;&nbsp;
			 Prezzo totale:<span style="color:red">$prezzoparziale &#8356;</span>
		</div>	
D;
						echo $out; //tutto ci� che genero � di fatto dentro al form "form_taglie" in proceduraacquisto.php
						echo "<br><br>";  //preparo il div con le taglie
						echo "<div style='float:left;width:56%'>";
						for ($i=1;$i<=$quanti;$i++){  //genero i campi select delle taglie (fanno parte del form grande
							$idscarpa="S$idprodotto";
							$idscarpa.="_$i";               //S"idprodotto"_"numscarpa"
							echo "<select name='$idscarpa'>";
							echo "<option selected>34</option>"; //taglia scelta di default
							for ($j=35;$j<=49;$j++) //generazione dinamica lato client delle varie taglie
								echo "<option>$j</option>";
							echo "</select>";
							}
						echo "</div></div>";	//qui finisce il box prodotto
				}
				mysqli_free_result($ris);
				mysqli_close($connessione);
				return $prezzototale; 
		}
		
	public static function ShowCompleteOrder(){ //mostra tutti i dettagli dell'ordine
				if (!isset($_SESSION['loggedUID'])) 
					return;
				$uid = $_SESSION['loggedUID'];
				Connect($connessione, $database);
				$query = "SELECT Nome, Cognome FROM $database.`utenti` WHERE ID_Utente='$uid'"; //dati dell'utente che sta effettuando l'acquisto
				$ris = mysqli_query($connessione,$query);
				$n = mysqli_fetch_array($ris);
				mysqli_free_result($ris);
				$user = "$n[Nome]";
				$user .= " $n[Cognome]"; 
				echo "<div style='font-size:12.5pt; font-family:Arial'>";
				echo "<h6>Ordinante:</h6><p><b>$user</b></p>"; 			//INFORMAZIONI SU ORDINANTE E ORDINE
				echo "<h6>Ordine:</h6><ul>"; 			//inizio lista con modelli (nome) e taglie dei pezzi scelti
				$taglie="";
				$cart="";
				if ( (!isset($_SESSION['taglie'])) || (!isset($_SESSION['carrello'])) )
					return;
				$taglie=$_SESSION['taglie'];   
				$cart=$_SESSION['carrello'];
				$val=$cart[0][0];
				$query = "SELECT * FROM $database.`prodotti` WHERE (ID_prodotto=$val";
				unset($cart[0]);
				foreach ($cart as $value)  
					$query .= " OR ID_prodotto=$value[0]";	
				$query .= ")"; 						//� la query che d� tutti i dati sui prodotti nel carrello
				$ris = mysqli_query($connessione,$query);
				$prezzototale=0;
				while ($row = mysqli_fetch_array($ris)){ //per ogni modello stampo nome e taglie scelte (una per ciascun paio acquistato)
						$idprodotto=$row['ID_Prodotto'];
						$quanti=Cart::ProductsIn($idprodotto); //numero pezzi di quel Prodotto (ID argomento)
						$prezzototale+=(($row['Prezzo'])*$quanti);
						$nomescarpa=strtr($row['Nome'],"_"," "); 		//solita conversione nome immagine -> nome scarpa
						$out =	"<li>$quanti <i>$nomescarpa</i>; "; 
						$a=($quanti==1)?"taglia":"taglie"; 					// singolare/plurale
						$out.=$a;
						echo $out;
						for ($i=1;$i<=$quanti;$i++){  // siccome il vettore delle taglie ha indici del tipo S12_3 (il terzo paio di scarpe del modello con PRID=12) 
									$t="S$idprodotto";// devo generare gli indici in modo acconcio 
									$t.="_$i"; 
									echo " $taglie[$t]";
						}
				}
				echo "</ul>"; 						//fine lista con modelli (nome) e taglie dei pezzi scelti
				$price;  			//ci sar� eventualmente "+5euro" se ho scelto (nelle fasi prima) il corriere
				$tot=$prezzototale;
				date_default_timezone_set('Europe/Paris');
				$g = date('j');
				$m = date('n');
				$a = date('Y');
				$data;              					//INFORMAZIONI DI CONSEGNA		
				if (isset($_SESSION['negozio'])) { 							//ho scelto di ricevere in negozio 
					$shop=$_SESSION['negozio'];
					$data=date ("d/m/Y", mktime (0, 0, 0, $m, $g, $a)+10*86400); //data di consegna (10gg da oggi)
					$price= ""; //dico quando e dove (quale negozio) arriver� il pacco pi� altre informazioni...
					echo "<p>L'ordine verr&agrave; consegnato, in data <b style='color:red'>$data</b>, al seguente negozio: <b>$shop</b>.<br>";
					echo "Il pacco rester&agrave; in giacenza al suddetto indirizzo per 30 giorni, 
					       in attesa che il cliente lo ritiri.</p>";
				}
				elseif (isset($_SESSION['indirizzo_corriere'])) { 					//se ho scelto la spedizione a casa via corriere
					$cor=$_SESSION['indirizzo_corriere'];
					$data=date ("d/m/Y", mktime (0, 0, 0, $m, $g, $a)+3*86400); //data di consegna (3gg da oggi)
					$price= "+ <b>5</b>&#8356;<small>(costo spedizione)</small>";
					$tot+=5; 						//il corriere ha un sovrapprezzo di 5 euro sul totale
					echo "<h6>Spedizione:</h6><p>"; //stampo l'indirizzo di spedizione cos� come fornitomi nelle fasi precedenti
					echo "L'ordine verr&agrave; spedito, entro il <b style='color:red'>$data</b>, al seguente indirizzo:";
					echo "<b>$cor[Via_Piazza] $cor[Civico],  $cor[CAP]($cor[Citta])</b>.<br>";
					echo "Se possibile il corriere recapiter&agrave; l'ordine presso <b>$cor[Recapito]</b>.</p>";
				}   									//INFORMAZIONI SUL PREZZO DELL'ORDINE
				echo "<h6>Costo totale</h6><p><b>$prezzototale</b>&#8356; <small>(costo prodotti)</small> $price = <b style='font-size:19pt; color:red'>$tot</b>&#8356;</p>";
				echo "</div>";                           //chiudo il div con le informazioni sull'ordine
				unset($_POST);
				unset($_GET);
				mysqli_close($connessione);
		}
		
	public static function FinishOrder(){ //completo l'ordine, cio� aggiorno il database ACQUISTI
			Connect($connessione, $database);
			if ( (!isset($_SESSION['loggedUID'])) || (!isset($_SESSION['carrello'])) )
				return;
			$uid=$_SESSION['loggedUID'];
			foreach ($_SESSION['carrello'] as $value){ //scorro il carrello ({[PrId, numpezzi], [PrId, numpezzi],....[PrId, numpezzi]})
				$query = "SELECT Numero_Pezzi FROM $database.`acquisti` WHERE (ID_Utente=$uid AND ID_Prodotto=$value[0])";
				$ris = mysqli_query($connessione,$query);
				$row = mysqli_fetch_array($ris); //query per sapere se quell'utente ha gi� acquistato una scapra di questo modello
				if (!$row) { //� la prima volta che questo utente compra questo genere di scarpe
					$query = "INSERT INTO $database.`acquisti` VALUES ($uid,$value[0],$value[1])"; //devo inserire una nuova riga
					$ris = mysqli_query($connessione,$query);
				}
				else { //l'utente ha gi� acquisto almeno 1 paio di queste scarpe
					$newamount=$row['Numero_Pezzi']+$value[1];
					$query = "UPDATE $database.`acquisti` SET Numero_Pezzi=$newamount WHERE (ID_Utente=$uid AND ID_Prodotto=$value[0])";
						$ris = mysqli_query($connessione,$query); //aggiorno la riga esistente
				}
			}   
			mysqli_close($connessione);
		}

}
?>