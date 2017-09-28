<?php session_start();  ?> 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

 <!-- pagina, accessibile dalla HP, in cui l'utente può accedere al sito (LOGIN); i controlli sono fatti lato server, dovendo accedere al DB -->	

 <?php
  if ( isset($_POST['Email']) && isset($_POST['Password']) ) {      //se la pagina viene caricata a seguito di un tentativo di accesso
      function __autoload ($class_name){ //carico tutte le classi
			require_once  "../Classes/"  . $class_name . '.php';
		}   
		
		$mail_inserita="";
		$pwd_inserita="";
		if ( isset($_POST['Email']) && isset($_POST['Password']) ) { // ricezione dei 2 valori forniti dal post in access.php
			$mail_inserita = $_POST['Email'];     
			$pwd_inserita = $_POST['Password']; 
		}
		$uid= User::ID($mail_inserita); //  mi procuro l'ID dell'utente con quella mail (-1 se non esiste)
		if ($uid >=0){ 		//nel DB esiste un utente registrato con quella mail
			$pwd_corretta=User::Password($uid);  //la password inserita è quella dell'utente con la mail inserita?
			if ($pwd_inserita==$pwd_corretta){//SI: (pwd e mail coincidono) accedo e salto alla home page
				$_SESSION['logged'] = true;     //loggo l'utente
				$_SESSION['loggedUID'] = $uid;
				echo ("<script type='text/javascript'>location.href='../index.php'</script>"); //salto alla homepage
			}
			else  //NO (pwd e mail non coincidono), salto ad access.php con pwd_error vero e la mail inserita nell'omonima variabile da GET
				echo ("<script type='text/javascript'>location.href='access.php?pwd_error=true&mail_inserita=$mail_inserita'</script>");
		}
		else  //non c'è alcun utente registrato con la mail fornita nel modulo in access.php, quindi vi rimando l'utente
			echo ("<script type='text/javascript'>location.href='access.php?mail_error=true&mail_inserita=$mail_inserita'</script>");	
  } //fin qui le operazioni fatte ai tentativi di accesso alla pagina col login
 $m="";
 if (isset($_GET['mail_inserita']))
	$m = $_GET['mail_inserita']; //quando, in caso di errore (su mail o pwd), access.php mi rimanda qui mi passa via QS la mail che avevo inserito
 $mail_err =  		//messaggio di errore sulla mail: non esistono utenti registrati con essa
<<<MAILERR
 <div class="access_alert">
 <b>Error while logging.</b><br>
 No registred user exist with mail &quot;<i>$m</i>&quot;.<br>
 Please verify the e-mail address typed.
 </div>
MAILERR;
$pwd_err = 		//messaggio di errore sulla password: non corrisponde alla mail inserita
<<<PWDERR
 <div class="access_alert">
 <b>Error while logging.</b><br>
 The inserted password for this email address does not match. Please type the correct password.
 </div>
PWDERR;
?>
 
<html>

	<head>
		<title>Login su MagicShoe.it</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<style type="text/css"><!--@import url("../CSS/general.css");--></style>
		<style type="text/css"><!--@import url("../CSS/register.css");--></style>
		<script type="text/javascript" src="../JS/general.js"> </script>
	</head>
	
	<body style="background-image:url('../Multimedia/MiscImg/foots.jpg');"> 
		<div class="container"> 
			<div class="header" style="padding-top:14px;"> <!--sezione di testa (2 loghi, titolo)-->
				<a href="../index.php">
					<img class="logosx" src="../Multimedia/MiscImg/Logo.jpg" alt="MagicShoe Home Page" onMouseOver="showHP(this)" onMouseOut="hideHP(this)">
				</a>
				<span class="titolo">Login su MagicShoe.it</span>
				<a href="../index.php">
					<img class="logodx" src="../Multimedia/MiscImg/Logo.jpg" alt="MagicShoe Home Page" onMouseOver="showHP(this)" onMouseOut="hideHP(this)">
				</a>
			</div>
			<div id="corpo"> 
				<div id="boxaccesso"> <!-- contiene il box dove si inseriscono i dati per autenticare lo accesso -->  
				 	<span style="font-size:24pt; color:#100060">LOG WITH YOUR ACCOUNT:</span><br><br> 
<?php                 // si presenta uno errore su mail e pwd se non sono stati passati i QS relativi o se sono stati passati come falsi 
				   $errorOnMail=(isset($_GET['mail_error']))&&($_GET['mail_error']); //quando, in caso di errore, access.php mi rimanda qui mi passa via QS xxx_error=true
					$errorOnPwd=(isset($_GET['pwd_error']))&&($_GET['pwd_error']);
					if ($errorOnMail) //a seconda del tipo di errore lancio uno dei due messaggi previsti
						echo $mail_err; 
					if ($errorOnPwd) 
						echo $pwd_err;
?>                    <!-- form per lo inserimento di mail e password; esso rimanda a se stessa per fa i controlli--> 
					<form action="access.php" name="form_login" id="form_login" method="post" class="form">
					<div>
						<label>Mail<br>
							<input type="text" name="Email" style="width:140pt; height:15pt"
							<?php if ($errorOnMail||$errorOnPwd) // se si presenta un errore, qualsiasi sia, lascio  
									echo("value='$m'");            // comunque scritta la vecchia mail inserita
							?>>
						</label>
						<?php if ($errorOnMail) //se l'errore è sulla mail affianco una freccia al campo di inserimento suddetto
								echo ("<img src='../Multimedia/MiscImg/ArrowRed.png' alt='Error' class='redarrow'>");
						?>
						<br>
						<br> 
						<label>Password<br> 
							<input type="password" name="Password" maxLength="8" size="10" style="height:15pt"> 
						</label>
						<?php if ($errorOnPwd) //se l'errore è sulla pwd affianco una freccia al campo di inserimento suddetto
								echo ("<img src='../Multimedia/MiscImg/ArrowRed.png' alt='Error' class='redarrow'>");
						?>
						<br><br>
						<input type="submit" value="Login" class="gold_button" 
							   onMouseOver="this.style.border='outset medium yellow'" 
							   onMouseOut="this.style.border='none'"
						> <!-- effetti sul buttone di sottomissione del form-->
					</div>
					</form>
					<br><br>
					Not having an account on MagicShoe yet? <!-- link al modulo di iscrizione -->
					<a href="iscrizione.php" style="color:red">Create it now!</a>.
				</div>	<!-- fine div col box col form -->
			</div> <!-- fine div corpo -->
		</div> <!-- fine div conteiner -->
	</body>
	
</html>