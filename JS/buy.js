/* FUNZIONI CHE SERVONO DURANTE L'ACQUISTO; files: PROCEDURA_ACQUISTO.PHP, NEGOZIO.PHP, CORRIERE.PHP, PAGAMENTO.HTML     */


/*      VARIABILI GLOBALI                  */
var show = true;			  // serve alla show_hide per alternare ad ogni invocazione lo stato del 2° figlio del suo nodo argomento
var NegozioScelto;            // memorizza il negozio (elemento del vettore "negozi" che è stato selezionato per ultimo
var schermata_negozi = false; // dice se c'è gia una regione scelte e quindi l'utente sta visualizzando un menù coi negozi ivi presenti
	
/*      NEGOZIO.PHP ovvero quando si sceglie il negozio a cui farsi spedire il pacco                  */
function negozio(i, oggetto) { // invocata quando un utente clicca su una regione, essa inserisce una sezione (div "scegli_negozio" ) 
    var h, s, m, l, j, testo;  // nel "div_destro" di negozio.php nella quale si può scegliere i negozi della regione cliccata                       
	h = document.getElementById("procedi");
	h.style.display = "none"; 				   // elimino l'eventuale segnale di "procedi" 				  
	s = document.getElementById("div_destro"); // (l'utente deve scegliere un nuovo negozio nella regione selezionata)		
	if (schermata_negozi){                  
		 s.removeChild(s.lastChild); // se era stato scelta una regione in precedenza tolgo il suo menu coi negozi,
	}								 // per poi dopo rimetterci quello della regione cliccata (può anche coincidere con la vecchia)
	m = document.createElement("div");
	m.setAttribute("id", "scegli_negozio");
	s.appendChild(m);				//inizio la creazione del nuov menù coi negozi della regione scelta
	testo = document.createTextNode("Choose one of the following fake shops in " + oggetto.alt + ": "); //sfrutto il fatto che il nome della regione è nel campo "alt"
	m.appendChild(testo);       //appendo al div "scegli_negozio" il testo con la regione creato al passo precedente
	m.appendChild(document.createElement("br"));
	l = utilsObj.geo[i]["elenco_negozi"].length; //l = negozi[i].length;
	for (j=0; j<l; j++){  //generazione della lista dei negozi disponibili (sono ancore)
			var coll, nome_negozio;
			coll = document.createElement("a");                      //  creo l'ancora
			nome_negozio = utilsObj.geo[i]["elenco_negozi"][j][0]+", "+utilsObj.geo[i]["elenco_negozi"][j][1];
			nodo_testo = document.createTextNode(nome_negozio); //  creo un nodo testo col nome del negozio
			coll.setAttribute("href", "#");							
			coll.setAttribute("class", "classe_negozio"); 
			coll.setAttribute("id", nome_negozio);//salvo il nome del negozio nell'id (per problemi di comptabilità DOM con IE e Mozilla)
			coll.setAttribute("onClick", "scelta(this)");           //  se clicco sul nome del negozio parte la "scelta"
			coll.appendChild(nodo_testo);							// innesto nel DOM
			m.appendChild(coll);	                                  
			m.appendChild(document.createElement("br"));
	}
	schermata_negozi = true; 			//  è stata scelta una regione (la variabile poteva già essere a true) e
}										//  quindi è stata inserito il suo menù (schermata) coi suoi negozi

function scelta(oggetto) {     // invocata quando si clicca su uno dei negozi del menù generato dalla "negozio": 
    NegozioScelto = oggetto.id;// rende visibile il menu di conferma "procedi" della scelta e vi inserisce il nome del negozio scelto                               
	var old_node = document.getElementById("negozio_scelto");  //lo span che contiene come testo "-" o il vecchio negozio scelto
	var new_node = document.createTextNode(NegozioScelto);     //nodo testo contenente il negozio cliccato
	old_node.replaceChild(new_node,old_node.firstChild);  // rimpiazzo il negozio scelto in precedenza (anche nessuno:"-")
	var nodo = document.getElementById("scegli_negozio"); 
	nodo.parentNode.removeChild(nodo);					// elimino il menù di scelta dei negozi di una regione
	var h = document.getElementById("procedi");
	h.style.display = "inline";       // rendo visibile la sezione "procedi" (vedi negozio.php)
	schermata_negozi = false;		  // quando scelgo un negozio il menù dei negozi della regione sparisce
}

function lancioFase2(data) { //serve per saltare alla fase successiva nel caso di ordine ricevuto presso un negozio (conferma del negozio)
	var t="buying.php?f=2&z="; 				 // inizio a preparare l'URL (con la QS)
	t+=NegozioScelto;						 // passo il nome del negozio scelto
	t+="&d=";  								 // passo la data di consegna (oggi + 10gg)
	t+=data;								 // completo la QS dell'URL con la data passatami come argomento
	location.href=t; 						 // salto alla fase successiva ("procedura acquisto.php" con f=2) 
}


/*      CORRIERE.PHP ovvero quando si sceglie dove il corriere deve arrivare con il pacco          */
function show_hide(nodo) {//ad ogni invocazione cambia la visibilità (visibile/nascosto) del secondo figlio del nodo argomento cliccato),
	var x = (nodo.nextSibling).nextSibling; //in particolare mostra/nasconde il menù delle carte di credito disponibili           
	x.style.visibility=(show)?("visible"):("hidden");  
	show=!show;  									 // show, variabile globale, è alternativamente vera o falsa
 
}

function checkIndCorr(oggetto) {   //invocata quando si sottomette il form del corriere per controllare i vari campi
	var ok_alfabetico = /^[A-Za-z]+[A-Za-zèùàòé\s\']*$/;
	var ok_civico = /^[0-9]{1,3}([a-z]?)$/; 
	var ok_cap = /\d{5}/;
	var vett = document.getElementsByTagName("input");   //vettore contenente i vari input del form
	var okay = true;      //c'è stato almeno un errore nell'inserimento dati nei campi (input) del form?
	if (!ok_alfabetico.test(vett[0].value)){  //non correttezza dell'indirizzo
		  alertIndCorr(vett[0], "Is not an address"); //metto l'alert al campo indirizzo (funzione apposita)     
		  okay=false;           //un controllo è andato male (comunque proseguo a controllare gli altri campi)
	}
	else {  //serve per togliere un eventuale alert rimanente (i controlli sono fatti al submit non real-time)
		deleteAlert(vett[0]); //funzione apposita, non cancella se è il caso (cioè non ci sono alert preesistenti)
	}       //---------I CONTROLLI SUGLI ALTRI CAMPI SEGUONO LO SCHEMA APPENA VISTO [if (){} else{}]------------
	if (!ok_civico.test(vett[1].value)) {
		  alertIndCorr(vett[1], "Non \u00E8 un numero civico (3 cifre con eventuale lettera finale)");
		  okay=false;
	}
	else {
		deleteAlert(vett[1]);
	}
	if (!ok_alfabetico.test(vett[2].value)) {
		  alertIndCorr(vett[2], "Come recapito indicare una persona");
		  okay=false;
	}
	else {
		deleteAlert(vett[2]);
	}
	if (!ok_alfabetico.test(vett[3].value)){ 
		  alertIndCorr(vett[3], "Is not the name of a city");
		  okay=false;
	}
	else {
		deleteAlert(vett[3]);
	}
	if (!ok_cap.test(vett[4].value)) {
		  alertIndCorr(vett[4], "Un CAP \u00E8 costituito esattamente da 5 cifre");
		  okay=false;
	}
	else {   
		deleteAlert(vett[4]);
	}
	return okay;      //sarà rimasto true solo se tutti i controlli sui campi sono andati a buon fine
}  
	  
function alertIndCorr(obj, mess) {  //guarda un certo nodo, se c'è gia l'alert lo lascia, altrimenti lo inserisce col messaggio specificato
	if ((obj.parentNode).lastChild.id!=undefined) { 
		return;  //l'alert c'è già, per come è fatta la ChechIndCorr è certamente col messaggio giusto per cui termino
	}
	var mm = document.createElement("span");    //procedura di inserimento dell'alert
	mm.setAttribute("class", "corrErr"); //setto classe
	mm.setAttribute("id", 2);			 // setto id
	mm.appendChild(document.createTextNode(mess));  //inserisco il messaggio-argomento nello span
	(obj.parentNode).appendChild(mm); 
	obj.style.border = "solid 2px red";     
}

function deleteAlert(obj) {  //se c'è elimina l'alert presente tra i fratelli del nodo argomento
	if ((obj.parentNode).lastChild.id==2){  //il nodo fratello in questione è un alert
		(obj.parentNode).removeChild((obj.parentNode).lastChild); //eliminazione
		obj.style.border="";
	}
}

