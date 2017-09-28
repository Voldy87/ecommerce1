<?php session_start();  //apro la sessione ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

					<!-- pagina che permette, durante la fase di acquisto, di scegliere l'indirizzo a cui il corriere spedirà l'ordine-->
<?php
	if ((!isset($_SESSION['logged']))||(!$_SESSION['logged']))  //se è scaduto il login rimando all'apposita pagina di errore
		echo '<script type="text/javascript">location.href="buyadvice.html"</script>';
?>	

					
<html>
	<head>
	<title> Modalit&aacute; di spedizione </title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
				<style type="text/css"><!--@import url("../CSS/general.css");--></style>
				<style type="text/css"><!--@import url("../CSS/buy.css");--></style>
				<script type="text/javascript" src="../JS/buy.js"> </script>
				<script type="text/javascript" src="../JS/general.js"> </script>
	</head>

	<body style="background-color:white">
		<div>
		<br><br>
		<h1 style="text-align:center">Procedura di acquisto</h1> <!-- titolo, loghi destro e sinistro -->
		<a href="../index.php" title="to Home page"><img  class="logosx2" src="../Multimedia/MiscImg/Logo.jpg" alt="MagicShoe HomePage" onMouseOver="showHP(this)" onMouseOut="hideHP(this)"></a>
		<a href="../index.php" title="to Home page"><img  class="logodx2" src="../Multimedia/MiscImg/Logo.jpg" alt="MagicShoe HomePage" onMouseOver="showHP(this)" onMouseOut="hideHP(this)"></a>
		</div>
		<div style="text-align:center; width:100%">  <!-- sezione elenco fasi acquisto generata lato server-->
		  <?php  
					$titolo = array(0=>"Riepilogo dell'ordine", 1=>"Modalit&aacute; di spedizione", 
								  2=>"Conferma dell'acquisto");
					for($i=0;$i<3;$i++) {
						$j=$i+1;
						$x=($i==1)?"style='background-color:red; font-weight:bold'":"";
						$ind=($i==1)?"buying.php?f=$i":"#";			
						echo "<a href='$ind' class='link_fasi_acquisto' $x>";
						echo "<span style='width:10px'>$titolo[$i] </span>";
						echo "<span style='font-size:x-small'>($j/3)</span>";
						echo "</a>";
					}
					unset($_SESSION['negozio']); 
					unset($_GET); 
		?>
		</div>
		<div style="margin-left:14%; margin-top:7%;">  <!--informazioni sulla data di spedizione e form--> 
			<h3>
				Il tuo pacco arriver&agrave; il 
<?php            //3 giorni da oggi 
					date_default_timezone_set('Europe/Rome');
					$g = date('j');
					$m = date('n');
					$a = date('Y');
					$str = date ("d/m/Y", mktime (0, 0, 0, $m, $g, $a)+3*86400);
					echo "$str, ";  //contiene la data di arrivo nascosta, nel form post 
?> 
				per mezzo del corriere TNT.
			</h3>
			<br><br>
			<h5 style='color:green; text-decoration:underline'>
				Per favore, inserisci l'indirizzo al quale vuoi ricevere l'ordine
			</h5> <!--div col form e il link alla fase precedente-->
			<div style="line-height:3pt; padding:5px; font-size:12pt; border: solid 2px green; width:50%" > 
				<form action="buying.php" name="form_corriere" method="post"> <!--form dati per il corriere-->
					<div>
						<label id="ww">Indirizzo
							<input type="text" name="Via_Piazza">
						</label>
						<br><br>  
						<label>Civico&nbsp;&nbsp;&nbsp;
							<input type="text" name="Civico" maxLength="4" size="3"> 
						</label>
						<br><br>  
						<label>Recapito 
							<input type="text" name="Recapito"> 
						</label>
						<br><br>  
						<label>Citt&aacute;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="Citta"> 
						</label>
						<br><br>  
						<label>CAP &nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" name="CAP" size=3 maxlength=5> 
						</label>
						<br><br><br><br>
						<input type="submit" value="Fase successiva" style="background-color:yellow;  font-size:15pt; font-weight:bold; cursor:pointer;" 
							   onClick="return checkIndCorr(this)"> <!-- il controllo dei dati inseriti è fatto al submit, non real-time alla OnChange-->
<?php //input nascosto per il post della data (non viene controllata dalla checkperchè l'ho generata io prima!)
				  echo "<input name='date' type='hidden' value='$str'>"; 
?>   
					</div>
				</form> <!-- fine form dati per il corriere -->
				<a href="buying.php?f=1" title="Torna indietro" 
				   style="position:absolute; top: 460px; right: 80px; cursor:pointer;">
						<img src="../Multimedia/MiscImg/back.png" alt="Torna indietro" class="backarrow" > 
				</a> <!-- link alla fase precedente (scelta taglie)-->
			</div> <!--fine div col form e il link alla fase precedente-->
		</div>    <!--fine div con informazioni sulla data di spedizione e form-->
	</body>
</html>
