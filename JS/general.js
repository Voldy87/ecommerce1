/*							QUESTE FUNZIONI SERVONO IN CONTESTI DIVERSI, PER REALIZZARE FUNZIONI SEMPLICI, 
										E SONO UTILIZZATE PRINCIPALMENTE IN INDEX.PHP						*/
var glob={};
var testo = utilsObj.scrolltext + " - " + utilsObj.scrolltext + " - ";
	
var secondi= -1; //serve alla TimeRuns (che la decrementa) far scorrere il tempo; viene inizializzata (5) da Countdown 
var s;  //serve a CountDown (la resetta) e TimeRuns (la setta) per impostare un intervallo di esecuzione di una funz
var flag=false; //serve per sapere se devo far partire il countdown: questo accade solo quando la registrazione � andata a buon fine

function zoom (nome){
	var path = nome+".jpg";
	while(nome.indexOf("_")!=-1)
		nome=nome.replace("_"," ");
	var titolo = "<title>"+nome+"</title>";
	var pre= "<html><head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
	var post1 ="<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
	var post2 = "<style type='text/css'><!--@import url('../CSS/general.css');--></style></head>";
   var post3 = "<img src='../Multimedia/ShoesImg/"+path+"' width='75%' height='75%' alt='Immagine del prodotto ingrandita'>";
	var post4 = "<div class='nome_scarpa_zoomata'>"+nome+"</div>";
	var myWindow = window.open("","Zoom");
	myWindow.document.write(pre+titolo+post1+post2+post3+post4);
	myWindow.document.close();
}

function scambia_searchdiv() {
	var topdiv=document.getElementById("divsearch");
	if(topdiv!==null)
		topdiv.insertBefore(topdiv.lastChild, topdiv.firstChild);
}

function scorri() {   /*  implementa lo scorrimento della variabile globale "testo", eseguito ogni 185ms  */                                
    var primocar = testo.charAt(0);
    testo = testo.slice(1,testo.length) + primocar;
    document.forms[0].miotesto.value = testo;       
}

function showHP (oggetto){ /* sostituisce l'immagine del logo senza HP con quella del logo con HP*/
	oggetto.src="../Multimedia/MiscImg/LogoHP.jpg";
	(oggetto.style).border= "0";
	(oggetto.style).width="13%"; 
	(oggetto.style).height="88px";
}

function hideHP(oggetto){ /* sostituisce l'immagine del logo cpn HP con quella del logo senza HP*/
	 oggetto.src="../Multimedia/MiscImg//Logo.jpg";
	(oggetto.style).border= "0";
	(oggetto.style).width="13%"; 
	(oggetto.style).height="60px";
}

function Countdown(secs, url, id){    /*          secs=5, url=index,id=reg_ok: fa in modo che ogni secondo venga  */
	var funz = "TimeRuns('"+url+"','"+id+"')";//  invocata la TimeRuns con parametri "index.php" e "reg_ok"; inoltre
	var dad = document.getElementById(id);	  //  imposta a 5 la variabile globale secs, in modo che TimeRuns la 
	var t = document.createTextNode(secs);	  //  usi per contare il tempo che scorre
	dad.appendChild(t);
	clearInterval(s);
	secondi = secs;
	s = setInterval(funz, 1000); 
}

function TimeRuns (url, id){  // ogni volta decrementa la variabile globale secondi, che parte da 5, impostata da
	secondi--;				  // Countdown e visualizza a video modificando il campo che contiene il contatore; tale 
	if (secondi<0)   {		  // elemento col campo lo individua sapendo il suo id ("regok", passatele come 2� 
		clearInterval(s);	  // argomento. Quando arriva a 0 salta alla HP (passatele per 1� argomento).
		return;}
	if (secondi==0){
		clearInterval(s);
		location.href = url;}
	var dad = document.getElementById(id);
	(dad.lastChild).nodeValue=secondi; 
}

function newric(obj){                        // nel caso in cui lavori con carrello e wishlist su prodotti filtrati 
	var a = document.getElementById("cerca");// con ricerca devo rifare la ricerca perch� il reload di index mi 
	a.value = obj; 							 // rimostrerebbe tutti i prodotti: allora  (vedi showSelect....) questi 
	(document.form_ricerca).submit();		// link sono ..z=ricerca&c=$stringa, in modo che possa rifiltrare i  
}											// prodotti mostrandoli come erano prima dell'operazione su cart/WL.
											// Per fare ci� all'Onload del body (vedi->) se $ric � settato eseguo
											// questa funzione: essa riempie l'unico campo del form con la stringa
											// da ricercare (unico argomento, passato via GET dal link dell'operazione
											// su cart//wl) ed esegue la submit del form stesso.


