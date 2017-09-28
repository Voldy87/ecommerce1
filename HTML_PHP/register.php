<?php session_start();?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<!-- pagina statica che contiene il modulo per la registrazione di un nuovo utente (controlli lato client con JS) 
e che provvede anche all'inserimento nel db con la comunicazione dell'evento di successo creazione--> 
 
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title> Registering Form at MagicShoe.</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<style type="text/css"><!--@import url("../CSS/general.css");--></style>
		<style type="text/css"><!--@import url("../CSS/register.css");--></style>
		<script type="text/javascript" src="../JS/register.js"> </script>
		<script type="text/javascript" src="../JS/general.js"> </script>
	</head>

<body style="background-image:url('../Multimedia/MiscImg/foots.jpg')" >
<?php  function __autoload ($class_name){ //carico tutte le classi
				require_once  "../Classi/"  . $class_name . '.php';
		}
		if (!isset($_POST['Password'])) {
?>
		<div class="container" style="background-color:white; padding:14px"> <!-- INIZIO CONTAINER-->
			<div class="header"> <!-- loghi (2) e titolo-->
			    <a href="../index.php"><img class="logosx" src="../Multimedia/MiscImg/Logo.jpg" alt="MagicShoe Home Page" 
			     onMouseOver="showHP(this)" onMouseOut="hideHP(this)">
				</a>
				<span class="titolo">Modulo di iscrizione</span>
			    <a href="../index.php"> <img class="logodx" src="../Multimedia/MiscImg/Logo.jpg" alt="MagicShoe Home Page" 
			      onMouseOver="showHP(this)" onMouseOut="hideHP(this)">
			    </a>
			</div>
			<div id="corpo"> <!-- INIZIO CORPO-->
				<p style="font-size:14pt"> <!-- paragrafo introduttivo alla registrazione-->
				    Se vuoi acquistare prodotti su MagicShoe.it devi registrarti sul sito: 
					&eacute; un'operazione semplice e sicura, che garantisce reciprocamente cliente e venditore. 
					<br>Una volta iscritto potrai accedere al sito utilizzando il tuo account, 
				    e godere di tutti i privilegi che esso conferisce.
				</p>
				<p>  <!-- paragrafo introduttivo al modulo-->
					<span style="font-size:16.5pt; font-family:Ravie; color:#FF4070; margin-left:16%">
						Per registrarti compila il seguente modulo:
					</span>
					<br>
					<span style="margin-left:54.2%">
						(i campi contrassegnati con * sono facoltativi)
					</span>
				</p>
				<div id="modulo" style="margin-left:16%"> <!-- sezione contenente il form di iscrizione-->
					<form action="register.php" name="iscrizione" method="post" onSubmit="return check()"> 
						<div id="div_dati" class="bloccoform"> <!-- inizio sottoform contenente i dati personali-->
							<h4>Personal data:</h4>
							<div id="div_alert_dati" class="divform"> <!-- div di errore sulla sezione dati personali-->
								Errors on personal data     <!-- solitamente nascosto e reso via JS-->
							</div>
<!-- SEX SELECT-->			<label for="sss">Gender</label><br> <!-- sesso-->
							<select name="Sesso" id="sss" STYLE="width: AUTO">
								<option selected >Male
								<option> Female
							</select>
							<br>  
<!-- 0 INPUT 0-->			<label for="name">Name</label><br> <!-- nome-->
							<input id="name" type="text" name="Nome" maxlength="30" 
							       style="cursor: text" onChange="controllaDati(0)" >
							<br>
<!-- 1 INPUT 1-->			<label for="cognome">Surname</label><br> <!-- cognome-->
							<input id="cognome" type="text" name="Cognome" maxlength="30" style="cursor: text" 
							       onChange="controllaDati(1)"><br>
<!-- 2 INPUT 2-->			<label for="telefono">Telephone</label><br> <!-- tel-->
							<input type="text" name="Telefono" maxlength="20" id="telefono" onChange="controllaDati(2)">
							<br>
<!-- 3 INPUT 3-->			<label for="citta">City*</label> <!-- citta' -->
							<br>
							<input type="text" name="Citta" id="citta" maxlength="30" style="cursor: text" 
							       onChange="controllaDati(3)"><br>
<!-- 4 INPUT 4-->			<label for="CAP">ZIP</label><br> <!-- cap-->
							<input type="text" name="CAP" id="CAP" size="5" maxlength="5" STYLE="width: AUTO" 
							        onChange="controllaDati(4)"><br>
<!-- REGIONE SELECT-->		<label for="sel_reg">Region*</label><br> <!-- regione-->
							<select id="sel_reg" name="Regione" >
<?php 							echo("<option value=0 selected>Select your region</option>");
								$regioni=User::$REGIONI;
								foreach ($regioni as $key=>$value) //genero la lista delle regioni lato server 
									echo("<option value=$key> $value</option>");
?>
							</select>
							<br>
						</div><!-- fine sottoform contenente i dati personali--> 			
						<div id="div_mail" class="bloccoform"> <!-- inizio sottoform contenente la mail-->
						<h4>Email:</h4>
							<div id="div_alert_mail" class="divform"> <!-- div di errore sulla sezione dati della mail-->
								Errors with e-mail field
							</div>		
<!-- 5 INPUT 5 -->			<label for="e-mail">E-Mail</label>
							<br>  
							 <input type="text" id="e-mail" name="E-Mail" maxlength="30" 
							        onChange="controllaMail(0, <?php User::PrintMailVector()?> ) ">
							<br> <!-- per controllare se la mail Ã¨ tra quelle nel DB genero lato server un vettore con esse-->
<!-- 6 INPUT 6 -->			<label for="cemail">Confirm E-Mail</label><br>
							<input readonly style="background-color:grey"  type="text" name="Conferma E-Mail" 
								   id="cemail" maxlength="30" onChange="controllaMail(1,'')">
							<br>
							<div id="div_newsletter"> <!-- sezione sulla scelta della ricezione della newsletter-->
								<label for="Newsletter">Do you want to receive our newsletter?</label><br>
<!-- NEWSLETTER INPUT YES-->	<input type="radio" id="newslttrY" name="Newsletter" value="Si" STYLE="width: AUTO" checked>
								S&igrave;<br>
<!-- NEWSLETTER INPUT NO-->		<input type="radio" id="newslttrN" name="Newsletter" value="No" STYLE="width: AUTO">
								No<br>
							</div>
						</div>	 <!-- fine sottoform contenente la mail-->	
						<div id="div_pwd" class="bloccoform"> <!-- inizio sottoform contenente la password-->	
							<h4>Protected access:</h4>
							<div id="div_alert_pwd" class="divform"> <!-- div di errore sulla sezione dati della password-->
								Error while registering password
							</div>
<!-- 9 INPUT 9 -->			<label for="PWD">Password(8 letters)</label><br>
							<input type="password" id="PWD" name="Password" size="14" maxlength="8" 
							       onChange="controllaPwd(0)" STYLE="width: AUTO">
							<br>
<!-- 10 INPUT 10 -->			<label for="CPWD">Confirm Password</label><br>
							<input readonly style="background-color:grey; width: AUTO" type="password" id="CPWD"
								   name="Conferma password" size="14" maxlength="8" onChange="controllaPwd(1)">
							<br>
						</div>	 <!-- fine  sottoform contenente la password-->	
						<div id="div_conferma" class="bloccoform"> <!-- inizio ultimo sottoform-->	
							<h4>Sicurezza, accettazione termini e conferma:</h4>
							Inserisci il codice di verifica che trovi nell'immagine sottostante  
							<small>(<a href="../Multimedia/sound.wav" type="audio/x-wav">oppure ascoltalo</a>)</small>:
							<br> <!-- audio-->
							<img id="nobot" src="../Multimedia/MiscImg/Nobot.jpg" width="100" 
							     height="35" alt="679482"> <!-- visivo-->
							<br>
							<label for="codice"><strong>Security code</strong></label>:
<!-- 11 INPUT 11-->			<input type="text" name="Codice di sicurezza" maxlength="6" size="6"
								   style="width:auto" id="codice" onChange="controllaCodice()">
							<span id="span_code_err">
								Errore! <!-- div nascosto reso visibile in caso di errore sul codice-->
							</span>
							<br><br> 
							Per finire dichiara di accettare i termini del contratto:<br>
<!-- CONTRACT INPUT -->		<input type="checkbox" name="Condizioni" id="contratto" style="width: AUTO">
							<small>Accetto le condizioni del <i>contratto di utilizzo del sito</i> 
							(<a name="link_contratto" href="https://it.wikipedia.org/wiki/Contratto_(diritto_italiano)" onclick="window.open(this.href); return false">leggi</a>)</small>
							<br><br>
<!-- RESET-->				<input class="iscr_button" style="height:38pt; width:auto; margin-left:50%" 
							       id="azzera" type="reset" value="Cancella &#10;campi" onClick="svuotaAlerts()">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!-- TRYTOCONFIRM-->		<input class="iscr_button" 
							       style="height:38pt; width:auto; background-color:blue; color:white" 
								   id="confirm" type="submit" value="Crea il tuo &#10;account MagicShoe">
						</div> <!-- fine ultimo sottoform-->
					</form>
				</div> <!-- fine sezione contenente il form di iscrizione-->
			</div> <!-- FINE CORPO-->
		</div> <!-- FINE CONTAINER-->
	</body>

</html>
<?php
} else { // il post è andato a buon fine (controlli JS ok), per cui procedo a creare l'utente e comunicarlo 
				$uid=User::Insert();    //inserisco l'utente nel DB; i dati sono presi dal POST del form della pagina da cui arrivo (iscrizione.php)
				Wishlist::CreateEmpty($uid);  //creo la WL dell utente appena creato (è vuota)
				$_SESSION['logged'] = true;           //una volta registrato il sito ti logga automaticamente           
				$_SESSION['loggedUID'] = $uid;
?>
<html>
  <head>
      <title>Registrazione a MagicShoe.it in corso</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<script type="text/javascript" src="../JS/general.js"> </script>
		<style type="text/css"><!--@import url("../CSS/general.css");--></style>
    	<style type="text/css"><!--@import url("../CSS/register.css");--></style>
  </head>
  <!-- Per implementare il conto alla rovescia -->
  <body onLoad="if (flag) Countdown(5, '../index.php', 'reg_ok')" 
        style="background-image:url('../Multimedia/MiscImag/foots.jpg')"> <!-- se la registrazione è ok in 5 secondi reindirizzo--> 
	<div id="registrazione">
	<script type="text/javascript"> flag=true;</script>
	<span id="reg_ok">La registrazione ha avuto successo: sarai reindirizzato alla home page di <i>MagicShoe.it</i> tra </span> 
	<span style="color:blue"> seconds.</span>
	</div>
 </body>
</html>
<?php
}
?>
