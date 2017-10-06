<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
 <!-- è la homepage del sito: vi vengono svolte la maggior parte delle operazioni, spesso caricandovi interamente altre pagine  -->
        <!--  PHP INIZIALE  -->
<?php                         //apro la sessione                                         
	if ((isset($_GET['logout'])&&($_GET['logout']==1))){
		$_SESSION['logged']=false;     
		unset($_SESSION['loggedUID']);
	}
	$username="";
	$ricerca="";
	$par="";
	function __autoload ($class_name){         //carico le classi dall'apposita cartella
		require_once  "./Classes/" . $class_name . '.php'; 
	} 	
	unset($_SESSION['taglie']);
	if (isset($_GET['obj']))  
		$object = $_GET['obj'];    //decide sull'eventuale operazione sulla  wishlist(0,1,2) / carrello (3,4,5)
	if (isset($_GET['id']))
		$pr_id = $_GET['id'];      //contiene l'eventuale PrID dell'articolo da aggiungere/rimuovere su carrello/WL
	if (isset($object)) {
		switch($object){								//operazioni sul carrello
			case 3: {Cart::AddTo(1,$pr_id); break;}     //aggiungo un solo prodotto con quell'ID (0->1 o ++)
			case 4: {Cart::RemoveFrom($pr_id); break;} //rimuovo tutti i pezzi dell'articolo con quell'ID
			case 5: {Cart::Cancel(); break;}            //svuoto il carrello
			default: break;
		}
	}
	$loggato = (isset($_SESSION['logged'])) && ($_SESSION['logged']); //variabile che mi dice se è una sessione loggata
	if ($loggato){                                   //   solo se l'utente è loggato ha una Lista Desideri:
		$loggedUID = $_SESSION['loggedUID'];          //       1)individuo e memorizzo il suo nome 
		$username=User::Name($loggedUID); 			//       2)individuo e memorizzo il suo ID
		if 	(isset($object)) {                      //        3) azioni nel caso di operazioni sulla wishlist
			switch($object){
				case 0: {Wishlist::Cancel(); break;}     //svuoto la Lista Desideri
				case 1: {Wishlist::AddTo($pr_id); break;} //aggiungo il prodotto con quell'ID (0->1 o ++)
				case 2: {Wishlist::RemoveFrom($pr_id); break;} //tolgo il prodotto con quell'ID
				default: break;
			}
		}	
	}
	$welcome =   //messaggio di benvenuto ad un visitatore (non ancora loggato)
<<<WELCOME
	  <span>You don't have an account here at MagicShoe?</span>
	  <a href="./HTML_PHP/register.php" style="color:#000090" onMouseOver="(this.style).color='#30FF80'"
  	     onMouseOut="(this.style).color='#000090'"><b>Create one now!</b>
	  </a><br>
	  &nbsp;You already have an account with us? 
	  <a href="./HTML_PHP/access.php" style="color:#000090" onMouseOver="(this.style).color='#30FF80'"
     	onMouseOut="(this.style).color='#000090'"><b>Log In!</b>
	  </a>.				 				
WELCOME;
$bentornato = //messaggio di benvenuto ad un utente loggato
<<<BENTORNATO
		You are logged as <a href="index.php?p=myaccount"><b style="color:coral; cursor:help">$username</b></a>&nbsp;(
		<a href="index.php?logout=1" style="color:#00090" onMouseOver="(this.style).color='#30FF80'"
		   onMouseOut="(this.style).color='#000090'"><b>Logout</b>
		</a>)
BENTORNATO;
	if (isset($_GET['c'])) 
		$s = $_GET['c'];// nel caso in cui io lavori con carrello e wishlist su prodotti filtrati con ricerca devo 
					 //    rifare la ricerca perchè il reload di index mi rimostrerebbe tutti i prodotti: allora
			         //   (vedi showSelect....) questi link sono ..&c=$stringa (stringa cercata), in modo che possa rifiltrare
					//    i prodotti mostrandoli come erano prima dell'operazione su cart/WL. Per fare ciò all'Onload
					//    del body (vedi->) se $s è settato eseguo la funzione JS newric, che riempie il form di ricerca e fa la submit  
	if (isset($_POST['ricerca']))
		$ricerca= $_POST['ricerca'];   // la ricerca è un POST del form omonimo
	if (isset($_GET['p']) && $_GET['p']) { //la nuova pagina da aprire in index.php è eventualmente indicata via GET tramite "p"
		$par = $_GET['p']; // il nome della nuova pagina da caricare è passato tramite querystring del link alla pagina index stessa		   
	}
	if (isset($ricerca)&& $ricerca){ //se è stata lanciata una ricerca (via post) devo caricare la pagina modificata
		$par="ricerca";
		unset($_GET);
		unset($ric);
		unset($s);
	}	
?>
<html>                   <!--                  HTML + PHP + CSS + JS                    -->
    <head>                  
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="description" content="In this fake site you can find fake shoes">
        <meta name="keywords" content="purchases, e-commerce, shop, shoe, price, 
		                               clothing, fashion, shopping, magic shoe, magicshoe">
        <title>Magic Shoe: a fake online shoe shop</title>
  		<style type="text/css"><!--@import url("./CSS/general.css");--></style>
	    <script type="text/javascript" src="./JS/hardcode.js"></script>
	    <script type="text/javascript" src="./JS/general.js"> </script>
    </head>
	<body  id="toppage" style="background-color:white"
	       onLoad=" setInterval('scorri()',185); <?php if (isset($s)) echo "newric('$s');"; ?> scambia_searchdiv();"> 
	<!--l'azione periodica fissa è lo scorrimento, per la newric vedi il codice php sopra quando definisco $s -->	
			<div class="cornerlogos">
					<a href="index.php" title="Vai alla Home page"> <!-- logo/link alla HP sinistro --> 
						<img  class="logosx" src="./Multimedia/MiscImg/Logo.jpg" alt="MagicShoe HomePage" 
						      onMouseOver="showHP(this)" onMouseOut="hideHP(this)">
					</a>
					<a href="index.php" >  <!-- logo/link alla HP destro --> 
						<img class="logodx" src="./Multimedia/MiscImg/Logo.jpg" alt="MagicShoe Home Page" 
						     title="Back to Home page" onMouseOver="showHP(this)" onMouseOut="hideHP(this)">
					</a>
			</div>
			<div class="header"> 
					<form action=#> <!-- il testo da inserire per la scorri è nel file JS "generale") -->
						<div><input type="text" name="miotesto" class="testoscorrevole"></div>
					</form>				
					<div id="welcome" > <!-- contiene la parte che accoglie l'utente-->
<?php  						if ($loggato)  //saluto l'utente loggato col suo nome
									echo $bentornato; 
							   else              //saluto il visitatore e gli chiedo se vuole accedere o registrarsi  
									echo $welcome;     
?>
					</div>
					 <div id="header_bottom">  <!-- contiene le scelte sulle pagine coi prodotti (evidenzio in giallo se la pagina in questione è aperta) e la barra di ricerca -->
							<a href="index.php?p=novita" class="link1">New products</a>
							<a href="index.php?p=bestseller" class="link1">Bestsellers</a>
							<form method="post" id="form_ricerca" name="form_ricerca" class="spaceunder" action="index.php"> 
									<div>
										<input type="text" name="ricerca" size="50" id="cerca">
										<input type="submit" value="Search shoe by name">
									</div>
							</form> 
							<a href="index.php" id="allink">
								ALL PRODUCTS
							</a>
					</div>
			</div>
			<div class="content" id="pro">
<?php                                      
			switch ($par){
								case "carrello": {
										$NUM=0;
										$pricecart=0;
										if (isset($_POST['numproducts'])) //    serve quanto l'utente modifica la quantità di un tipo di scarpe da acquistare dalla visuale del carrello
											$NUM=$_POST['numproducts'];   
										$PRID;
										if (isset($_POST['PrID']))
											$PRID=$_POST['PrID'];         //    in particolare numproducts è la nuova quantità, PRID l'Id della scarpa coinvolta 
										if ( isset($PRID) && isset($NUM) )//    quello che accade è che la pagina carrello.php viene ricaricata ed eventualmente  
											Cart::Modify($NUM,$PRID);     //    modificata lato server con i dati passati precedentemente via QS
										echo("<div class='spaceunder menuprods' style='background-color:#C0F0FF; padding-top:0px; padding-bottom:48px;'>");
										echo("<div style='float:left;  width:76%'>");
										$cartlen=Cart::Length();
		         						if ($cartlen==0) //  se il carrello è vuoto devo esplicitarlo
				       						echo("<div class='spaceunder lmarg' ><h3 class='spaceunder'>Your cart is empty.</h3></div>"); 
				 							else {			 // altrimenti invoco la funzione che stampa tutti i box prodotti contenuti nel Carrello
				       						echo ("<div style='background-color:#C0F0FF; position: relative; left:8px; margin-bottom:10px;'><h3>Articles:</h3></div>");
					   						$pricecart=Cart::Show();
											}
										echo("</div>");
									   $art=($cartlen==1)?"articolo":"articoli";
										$BUY =        // sezione contenente il prezzo totale del carrello e il pulsante per effettuare l'ordine
<<<D
											<div style="float:left; clear:left; text-align:center"> 
											<span>Totale provvisorio<br>(<b>$cartlen</b> $art):<br><b style='color:red'>  $pricecart &#8356;</b><br><br>
		      							<a id='buy' href='./HTML_PHP/buying.php?f=0'>BUY!</a>
											</span>
											</div> 			 				
D;
		  								if ($cartlen>0) {  		  // sezione (div) contenente il pulsante per svuotare il carrello
											echo  ("<div id='svuotacart' >"); 
			    							echo  ("<a href='index.php?p=carrello&amp;obj=5'>Svuota il tuo carrello!</a>");
											echo ("<img src= '../Multimedia/MiscImg/CartFull.jpg' title='Cart full' alt='Cart Full' width='60%' height='66%' style='text-align:center'><br><br>");
											echo  $BUY;
											echo ("</div>");
		  								}
		  								else {					  // sezione (div) contenente il messaggio di carrello vuoto
											echo ("<div id='svuotacart' style='text-align:center'>Nessun prodotto presente"); 
			   						   echo ("<img src='../Multimedia/MiscImg/CartEmpty.jpg' title='Cart empty' alt='Cart empty' width='60%' height='66%'>");
											echo ("</div>");
		 								 }
		  								unset($_POST); 
		  								echo ("</div>");			  
										break;}
								case "lista desideri": {
										if (!$loggato) /* se non è una sessione di un utente registrato e loggato reindirizzo alla HP */
											echo ("<script type='text/javascript'>location.href='../index.php'</script>");
										echo("<div class='spaceunder menuprods' style='background-color:#D0B0E0; padding-top:8px; padding-bottom:48px;'>");
										$userid=$_SESSION['loggedUID'];
										$wlen = Wishlist::GetLength($userid);
										if ($wlen==0) 		
											echo("<h3 class='spaceunder lmarg' >Your wish list is empty.</h3>"); 
										else {
											echo ("<h3 style='background-color:#D0B0E0; width:83%; padding-left:1.2%'>"); 
											echo ("Articles (<b style='color:olive'>$wlen</b>):<br>"); 
											echo ("<a href='../index.php?p=lista+desideri&amp;obj=0'><small>[Svuota]</small></a>");
											echo ("</h3><br>"); 
											Wishlist::Show(); 
										}		
										echo("</div>");
										break;}
								case "acquisti": {
										if (!$loggato) /* se non è una sessione di un utente loggato reindirizzo alla HP */
											echo ("<script type='text/javascript'>location.href='index.php'</script>");
										echo("<div class='spaceunder menuprods' style='background-color:#C0FFB0;'> ");
										User::ShowPurchases();
										echo("</div>");
										break;}
								case "myaccount": {
										if (!$loggato) /* se non è una sessione di un utente loggato registrato reindirizzo alla HP */
											echo ("<script type='text/javascript'>location.href='index.php'</script>");
										echo ("<div class='spaceunder'  style='background-color:#FFD070; padding:0px; font-size:15pt'>");
										echo("<h3 id='hdati' class='spaceunder lmarg'>Your personal data:</h3>");
		  								User::Data();									
										echo(" </div>");
										break;}
								case "bestseller": {
										echo ("<h3 class='lmarg' >These are the shoes we sell more:</h3><br>");
										echo("<div class='spaceunder'>");
										Products::ShowBestseller();
										echo(" </div>");
										break;}
								case "novita": {
										echo ("<h3 class='lmarg' >The latest shoes on the market:</h3><br>");
										echo("<div class='spaceunder'>");
										Products::ShowNew();
										echo(" </div>");
										break;}
								case "ricerca": { 
										echo("<div id='divsearch'><div>");
										$quanti=Products::ShowSearched($ricerca);  //mostra tutti i prodotti contenenti la stringa immessa nel campo del form di ricerca										
										echo("</div><div id='searchres'>");
										$tmp=($quanti==0)?("The search '$ricerca' outputs nothing"):("Search results '$ricerca' ({$quanti}):");
										echo $tmp;
										echo("</div></div>");
										break;}
								default: {
									Products::Show();
									break;} 
		} // fine switch
?>
			</div> <!-- sottomenù a sinistra: carrello [,lista desideri, acquisti, infoaccount SOLO SE LOGGATO ("(ev.)"]-->
			<div class="navigation" id="menu">     <!-- se una voce è selezionata la formatto diversamente, non metto immagine, cambio sfondo...--> 
<!-- carrello-->  	 <a href="index.php?p=carrello" 
	               <?php if (strcmp($par, "carrello")==0) 
								echo("style='background-color:#C0F0FF;' class='leftmenu norightborder' ");?> 
						>
						<?php if (strcmp($par, "carrello")!=0) 
								echo("<img src='./Multimedia/MiscImg/cart%20black.jpg' class='myicons' alt='cart'>");?>
						Cart (<?php $l=Cart::Length(); echo ("$l");?>)
					  </a>
<!-- wishlist(ev.) --><a href="index.php?p=lista+desideri" 
						<?php if (strcmp($par, "lista desideri")==0) 
								echo("style='background-color:#D0B0E0;' class='leftmenu norightborder' ");
					          if (!$loggato)
								echo ("style='display:none'");?>
						>
						<?php if (strcmp($par, "lista desideri")!=0) 
								echo("<img src='./Multimedia/MiscImg/wishing.jpg'  class='myicons' alt='wishlist'>");
						?>
					  Wishlist
					   </a>
<!--acquisti (ev.)--><a href="index.php?p=acquisti" 
						<?php if (strcmp($par, "acquisti")==0) 
								echo("style='background-color:#C0FFB0;' class='leftmenu norightborder' "); 
							  if (!$loggato) 
								echo ("style='display:none'");?> 
						> 
						<?php if (strcmp($par, "acquisti")!=0) 
								echo("<img src='./Multimedia/MiscImg/euro.jpg'  class='myicons2' alt='euro'>");
						?> 
					 Purchases
					</a>
<!--account (ev.)--><a href="index.php?p=myaccount"  
						<?php if (strcmp($par, "myaccount")==0) 
								echo("style='background-color:#FFD070;' class='leftmenu norightborder' "); 
							  if (!$loggato) 
								echo ("style='display:none'");?>
						>
					    <?php if (strcmp($par, "myaccount")!=0) 
								echo("<img src='./Multimedia/MiscImg/omino.jpg'  class='myicons2' alt='account personale'> ");?>
					    My Account
					</a>
			</div>
			<div class="footer"> <!-- piè di pagina (altre informazioni) -->
				  <a href="./HTML_PHP/user%20manual.html">User Manual</a> |<!-- information about this site and how to use it -->
				  <a href="./Files/Contract.pdf" onclick="window.open(this.href); return false" >Contract</a>| <!-- apre il pdf in un'altra pagina/scheda -->
				  <a href="./HTML_PHP/privacy.html">Privacy</a>|
				  <a href="./HTML_PHP/customers.html">Customer assistance</a>|
				  <a href="./HTML_PHP/about%20us.html">About Us</a>|
				  <a href="./HTML_PHP/associates.html">Shops in Italy</a>|
				  <a href="./HTML_PHP/couriers.html">Delivery outside Italy</a>
				  <br>
				 <span>&nbsp;&copy; 2001-<?php date_default_timezone_set("Europe/Rome"); echo date("o");?>, Magic Shoe. </span>
			</div>
	  </body>
</html>
