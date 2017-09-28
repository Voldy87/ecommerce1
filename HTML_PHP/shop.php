<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<!-- pagina che permette, durante la fase di acquisto, di scegliere il negozio dove farsi recapitare l'ordine-->				
<?php 	
	if ( (!isset($_SESSION['logged']))||(!$_SESSION['logged']) ) //se c'è un accesso non loggato ritorno a index.php
		echo '<script type="text/javascript">location.href="../index.php"</script>';
?>	

					
<html>

	<head>
		<title>Modalit&agrave; di spedizione</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<style type="text/css"><!--@import url("../CSS/general.css");--></style>
		<style type="text/css"><!--@import url("../CSS/buy.css");--></style>
		<script type="text/javascript" src="../JS/hardcode.js"> </script>
		<script type="text/javascript" src="../JS/buy.js"> </script>
		<script type="text/javascript" src="../JS/general.js"> </script>
	</head>
	
	<body style="background-color:white">
		<div>
		<br><br>
		<h1 style="text-align:center">Procedura di acquisto</h1> <!-- titolo, loghi destro e sinistro -->
		<a href="../index.php" title="Vai alla Home page">
			<img  class="logosx2" src="../Multimedia/MiscImg/Logo.jpg" alt="MagicShoe HomePage" onMouseOver="showHP(this)" onMouseOut="hideHP(this)">
		</a>
		<a href="../index.php" title="Vai alla Home page">
			<img  class="logodx2" src="../Multimedia/MiscImg/Logo.jpg" alt="MagicShoe HomePage" onMouseOver="showHP(this)" onMouseOut="hideHP(this)">
		</a>
		</div>
		<div style="text-align:center; width:100%"> <!-- sezione principale -->
<?php 
			unset($_POST);
			unset($_SESSION['indirizzo_corriere']);
			$titolo=array(0=>"Riepilogo dell'ordine", 1=>"Modalit&aacute; di spedizione", 
						  2=>"Conferma dell'acquisto");
			for($i=0;$i<3;$i++){ //generazione dinamica dello schema con le 4 fasi dell'acquisto
				$j=$i+1;
				$x=($i==1)?"style='background-color:red; font-weight:bold'":"";
				$ind=($i==1)?"buying.php?f=$i":"#";			
				echo "<a href='$ind' class='link_fasi_acquisto' $x>";
				echo "<span style='width:10px'>$titolo[$i] </span>";
				echo "<span style='font-size:x-small'>($j/3)</span>";
				echo "</a>";
			}
			date_default_timezone_set('Europe/Paris');
			$g = date('j');
			$m = date('n');
			$a = date('Y');
			$str = date ("d/m/Y", mktime (0, 0, 0, $m, $g, $a)+10*86400); //generazione della data di consegna (10 giorni da oggi)
?> 
		<br><br> 
		<div id="corpo"> <!-- inizio div centrale -->
			<div id="sceglireg">
			  <strong>Scegli la regione:</strong><br> <!-- mappa immagine dell'Italia -->
			  <img src= "../Multimedia/MiscImg/Italia.jpg" usemap = "#mappa_italia" alt="L'Italia" style="border-width:0">
			  <map name="mappa_italia" id="mappa_italia">
		<script type="text/javascript">
		  	for (var i =0; i<utilsObj.geo.length; i++) {
		  		document.writeln("<area href = '#' onClick='negozio("+i+",this)' alt = '"+utilsObj.geo[i]["regione"]+"' shape = 'circle' coords = '"+utilsObj.geo[i].coords+"'>");
		  	}
		</script>	
			  </map>
		   </div>
		   <div id="div_destro"> <!-- in questa sezione viene inserito (funzione JS "negozio") il menù di scelta negozio quando si seleziona una regione-->
					<h2>Your order will be delivered on the <span style='color:red'><?php echo " $str ";?></span> at the following shop:<br> 
					<span id="negozio_scelto" style="color:teal; font-style:italic;">-</span><br><br>
						<button class="pulsante_procedi" id="procedi" type="button" style="display:none"
						        onClick="var data =<?php echo "$str";?>; lancioFase2(data)"> <!-- str è oggi+10gg, data generata lato server e poi passata via QS dalla lanciafase2 assieme al nome del negozio-->
									Next Step <!--al clic si salta alla fase successiva ma con un QS ben preciso-->
						</button> <!-- cliccando qui si passa alla fase successiva tramite un'apposita funzione JS (lancioFase2) con QS appropriato (devo passare il nome del negozio scelto) -->
					</h2> <!-- questa sezione h2 è invisibile e viene visualizzata (funzione JS "scelta"), riempiendo il campo-->
			 </div>       <!-- "negozio_scelto", quando un utente sceglie un negozio dal menu che compare in "div_destro"-->
		 </div> <!--fine div centrale-->
		<a href="buying.php?f=1" title="Back" style="position:absolute; top: 510px; right: 60px;" >
			<img src="../Multimedia/MiscImg/back.png" alt="back" class="backarrow"> <!-- si torna alla fase precedente-->
		</a>
		</div>
	</body>
	
</html>
