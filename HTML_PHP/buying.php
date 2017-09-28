<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<!-- pagina che riassume le fasi di acquisto di scelta taglie, scelta corriere/negozio (con rimando alla pagina), conferma finale, comunicazione 
al cliente che e' andato tutto bene-->						
<?php 
	function __autoload ($class_name){         //carico le classi 
		require_once  "../Classes/"  . $class_name . '.php';
	} 	
	$ALERT=
<<<G
		<p style="font-size:13pt">
			<b>We're sory, but finalizing an order is possibile only for registred users</b>
			<br><br>  
			If you haven't an account yet 
			<a href="register.php" style="color:#000090" onMouseOver="(this.style).color='#30FF80'"
				 onMouseOut="(this.style).color='#000090'">
				 you can create one now
			</a>
			; se invece possiedi gi&aacute; un account
			<a href="access.php" style="color:#000090" onMouseOver="(this.style).color='#30FF80'"
				onMouseOut="(this.style).color='#000090'">
				login
			</a>
			.
			<br><br><br>
			If you want to come back to the homepage <a href="../index.php">click here</a>.
		</p>
G;
	$ORDERED=
<<<H
	<div id='messaggiofinale'>
		<h1>Well done, you're fake order was issued correctly!</h1>
		<a href='../index.php' style='font-size:16pt'>Back to homepage</a>.
	</div>
H;
?>		

			
<html>
	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>
<?php  		
		$fase=-1;
		if (isset($_POST)){     //a seconda dei post ricevuti sono in una fase diversa
			if (isset($_POST['posting'])) //  posting � l'input nascosto del form_taglie, se � stato riempito vuol dire 
				$fase=1;				 //   che devo caricare la fase 1, perch� provengo dalla scelta delle taglie (0)
			if (isset($_POST['Via_Piazza'])) //chiaramente provengo da corriere.php (1) quindi devo andare alla fase 2
				$fase=2; 
		}
		if ( ($fase==(-1)) && isset($_GET['f']) )  //  f=2 � passato via QS dalla funzione lanciaFase2 al termine di negozio.php; inoltre � usato 
			$fase=$_GET['f']; // (as "buying.php?f=") da link <- (f=..) e carrello.php(f=0) per inoltrare l'ordine
		if( isset($_GET['ok']) && ($_GET['ok']==1) )
			$fase=3; 
		$titolo=array(0=>"Order resume", 1=>"Delivery mode", 2=>"Confirmation", 3=>"Order completed!");    /*fasi*/       
		if ((!isset($_SESSION['logged']))||(!$_SESSION['logged']))
			echo "Log in in order to buy!"; //se non sono loggato non posso procedere!
		else
			echo "$titolo[$fase]"; //generazione nome (titolo) pagina (contenuto del tag title)
?>
		</title>
		<style type="text/css"><!--@import url("../CSS/general.css");--></style>
		<style type="text/css"><!--@import url("../CSS/buy.css");--></style>
		<script type="text/javascript" src="../JS/hardcode.js"> </script>
		<script type="text/javascript" src="../JS/buy.js"> </script>
		<script type="text/javascript" src="../JS/general.js"> </script>
	</head>
	
	<body style="background-color:white">
			<div style="height:156px"> <!--sezione di testa: 2 loghi, titolo, riassunto fasi-->
				<a href="../index.php" title="Back to Home page"><img  class="logosx2" src="../Multimedia/MiscImg/Logo.jpg" alt="MagicShoe HomePage" onMouseOver="showHP(this)" onMouseOut="hideHP(this)"></a>
				<a href="../index.php" title="Back to Home page"><img  class="logodx2" src="../Multimedia/MiscImg/Logo.jpg" alt="MagicShoe HomePage" onMouseOver="showHP(this)" onMouseOut="hideHP(this)"></a>
				<br><br>
				<div style="text-align:center; width:100%">
<?php 			//generazione dinamica della barra con le fasi della procedura di acquisto (solo se sono loggato!)
				if ((isset($_SESSION['logged']))&&($_SESSION['logged']))	{
					$i=($fase==3)?3:0;
					for($i;$i<3;$i++){					
						$j=$i+1;
						$x=($i==$fase)?"style='background-color:red; font-weight:bold'":"";		
						echo "<a $x class='link_fasi_acquisto' >";
						echo "<span style='width:10px'>$titolo[$i] </span>";
						echo "<span style='font-size:x-small'>($j/3)</span>";
						echo "</a>";
					}
				}
?>	
				</div>
			</div>
			<div class="content" style="margin-left:2%; margin-bottom:2%;">
<?php  		//div, tutto generato lato server, con l'effettivo contenuto della pagina (se sono loggato)
			if ((isset($_SESSION['logged']))&&($_SESSION['logged']))	 {
				switch ($fase){  //a seconda di questa variabile mi trovo in fasi diverse (0-4):
		/*fase 0*/case 0: { //scelta delle taglie: bottoni avanti e indietro, box prodotti con taglie (generati con funzione apposita= 
						echo "<p style='font-size:15pt'>For every pair of shoes choose the wished size.<br> 
								When you are done please go to the next step (<i>Delivery mode</i>) 
								using the proper button link.</p>";
			/*form*/    echo "<form style='float:left; width: 88%;' action='buying.php' name='form_taglie' method='post'>"; 
						echo "<div> <input type='hidden' name='posting' value='1'></div>"; //unico input del form non generato da showOrder()
						$price=Products::ShowOrder(); //funzione che stampa i box prodotti con i form per le taglie
						echo "<div id='divtaglie'>"; //div contenente prezzo, link -> e <-, reset taglie
						echo "<span style='font-family:Arial;'>
						      Your order sums up to <b>$price &#8356;</b></span><br><br>"; //prezzo totale
						echo "<button type='submit' class='pulsante_procedi' >
								Next step
								</button>"; //pulsante per proseguire
						echo "<br><br><br>
						      <input type='reset' value='Reset sizes'>"; //reset taglie (diventano tutte il 34)
						echo '<a href="../index.php?p=carrello" title="Go back" style="position:absolute; top: 280px">
								<img src="../Multimedia/MiscImg.png" alt="Torna indietro" class="backarrow"></a>'; //link indietro
						echo "</div>";
	/*fine form*/		echo "</form>"; //  qui si chiude il form: esso contiene anche tutti i box prodotti creati dalla showOrder() 
									    //  con le taglie da scegliere tramite la select
						break;
					}
	/*fase 1*/	case 1: { //l'utente sceglie il tipo di spedizione (in negozio o via corriere), inoltre pu� scegliere di tornare alla fase precedente
						if (!isset($_SESSION['taglie']))
							$_SESSION['taglie']=$_POST; //memorizzo in una variabile di sessione le taglie scelte nella fase prima (0)
						unset($_POST);
						echo "<div id='fase2intro' style='font-size:15pt; text-align:justify;'>";
						echo "<p>"; //paragrafo con le informazioni su corriere e negozi
						echo" Scegli se desideri ricevere il tuo ordine direttamente a casa tua con la spedizione via corriere postale ";
						echo "oppure presso uno dei tanti negozi consociati con <i>Magicshoe.it</i> presenti su tutto il territorio italiano.</p>";
						echo"La spedizione via corriere ti assicura la consegna del tuo ordine entro 2-3 giorni 
							lavorativi da oggi, ma  prevede un sovrapprezzo di 5&#8356;;";
						echo " invece la consegna presso un negozio &egrave; gratuita ma i tempi di attesa variano da 5 a 10 giorni.<br><br>";			 				
						$indcor='location.href="courier.php"';
						$indneg='location.href="shop.php"';
						echo "<button style='cursor:pointer; background-color:cyan; font-size:16pt'  type='button' onClick='$indcor'> 
								Corriere</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; //SCELTA CORRIERE
						echo "<button style='cursor:pointer; background-color:cyan; font-size:16pt'  type='button' onClick='$indneg'>Negozio</button>"; //SCELTA NEGOZIO
						echo "</div>";
						echo '<a href="buying.php?f=0" title="Torna indietro"  
								style="position:absolute; top: 380px; right: 50px">
							    <img src="../Multimedia/MiscImg/back.png" alt="Torna indietro" class="backarrow"></a>'; //fase precedente (1)
						break;
					}
	/*fase 2*/	case 2: { //prima che l'utente guardi il riepilogo dell'ordine e per confermarlo memorizzo i dati ricevuti in fase 1			
						if (isset($_GET['z'])) {//scelto ricezione c/o negozio: lo memorizzo in una var di session
							$_SESSION['negozio']=$_GET['z'];
							unset($_GET['z']);
							unset($_SESSION['indirizzo_corriere']);
						}
						elseif (isset($_POST)){ //scelto il corriere: : memorizzo dati spedizione e la data in una var di sessione 
							$_SESSION['indirizzo_corriere']=$_POST;
							unset($_POST);
							unset($_GET['z']);
						} //memorizzando i dati se l'utente torna indietro mi rimangono in memoria
						echo "<br><h4>Il tuo ordine &egrave; completo: controlla la correttezza dei dati da te inseriti e conferma</h4>";	
						Products::ShowCompleteOrder(); //mostra l'ordine nella sua interezza
						echo "<a href='buying.php?ok=1' id='confermafinale'>
						Confirm your purchase</a>"; //link alla pagina che aggiorna il DB con l'acquisto e lo comunica completo all'utente
						echo '<a href="buying.php?f=1" title="Go back" style="cursor:pointer; margin-left:60%; float:right">
						<img src="../Multimedia/MiscImg/back.png" alt="Go back" class="backarrow"></a>'; //link alla fase 2
						break;
					}
				 case 3: { //comunico che l'ordine � stato spedito
				 		Products::FinishOrder(); // esegue tutte le operazioni di chiusura della transazione sul database
						Cart::Cancel();		 //chiaramente avendo completato l'acquisto devo svuotare il carrello
						Wishlist::Cancel(); //svuoto pure la lista dei desideri
				 		echo $ORDERED;
				 		unset($_SESSION['taglie']);  //tolgo le variabili di sessione che mi ero "portato dietro" per evitare confusione
						unset($_SESSION['negozio']); 
						unset($_SESSION['indirizzo_corriere']);
				 }	
				}  //fine switch
			}
			else // nel caso non sia loggato metto l'avviso di errore
				echo $ALERT;
?>
			</div>  <!-- fine del div con l'effettivo contenuto della pagina-->
	</body>
</html>
