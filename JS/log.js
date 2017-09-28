/* ---      SONO QUELLE FUNZIONI CHE VENGONO INVOCATE NEL MODULO DI ISCRIZIONE, CIOE' ISCRIZIONE.PHP   ---     */

//Variabili globali
var alerts = [false, false, false, //memorizza, per ogni campo del form, se è ivi presente un messaggio di errore
	          false, false,false, 
			  false, false, false, //gli elementi n° 7 e 8 rimarranno sempre "false"	
			  false, false  	   
];
var errori_finali=0;  //	può valere 0 (la check() non ha trovato errori), 1(ha trovato almeno un campo vuoto) 
					  //	o 2(ha trovato almeno un campo vuoto ed almeno un campo errato)

//Funzioni di utilità, invocate dalle funzioni invocate direttamente da iscrizione.php

function find(array, elem) {  //ricerca in un vettore
        var indice; 
        for (indice in array) { 
                if (array[indice] == elem)
                        return true;
        }       
        return false; 
}

function get_div_alert(i){  //a seconda dell'indice del campo passato restituisce il tipo di div alert da usare
			switch(i){
			case 0: case 1: 
			case 2: case 3:
			case 4: 		return "div_alert_dati"; 
			case 5: case 6: return "div_alert_mail"; 
			case 9: case 10:return "div_alert_pwd"; 
			}
}

function insert_alert(i, mess){            //    inserisce un alert (avviso di errore) al campo di indice "i" con 
	var mm = document.createElement("span"); //  messaggio "mess"  e uno globale nella relativa sezione del form  
	mm.setAttribute("class", "span_error");        //per lo stile dell'alert
	mm.appendChild(document.createTextNode(mess));
	var campi = document.getElementsByTagName("INPUT"); //mi procuro il vettore dei campi di input
	(campi[i]).parentNode.insertBefore(mm, (campi[i]).nextSibling); 
	(campi[i]).style.background = "red";    //evidenzio il campo di input dove c'è l'errore
	var contenitore= document.getElementById(get_div_alert(i)); //mi procuro il div di errore di sezione (nascosto)
	contenitore.style.display = "inline";    //rendo visibile l'alert di errore sulla sezione
	alerts[i] = true;   //segnalo a livello globale che su questo input c'è un alert visualizzato
} 

function remove_alert (i){   // elimina l'avviso di errore (alert)  al campo (input) del form di indice "i"
	var campi = document.getElementsByTagName("INPUT");  //mi procuro il vettore dei campi di input
	(campi[i]).parentNode.removeChild((campi[i]).nextSibling); 
	(campi[i]).style.background = "white";  //lo sfondo quando i dati sono corretti è bianco
	alerts[i] = false;  //segnalo a livello globale che su questo input non c'è un alert visualizzato
	var exist_alerts = false; //se sugli altri campi della sezione c'è almeno un alert
	switch(i){ //a seconda dell'indice capisco qual'è la sezione di cui devo controllare i campi input
        case 0: case 1:
        case 2: case 3:  //controllo eventuali alert (avvisi di errore) sui campi della sezione dati personali
        case 4:         {for (var j=0; j<=4; j++){
							if (alerts[j]) {
								exist_alerts = true;
								break;
							}
						 }
            			 break;
						}
        case 5: case 9: { //controllo eventuali alert (avvisi di errore) sui campi di "conferma mail" e "conferma pwd"
						exist_alerts = alerts[i+1]; 
						break;
		}
        case 6: case 10:{ //controllo eventuali alert (avvisi di errore) sui campi "mail" e "pwd"
						exist_alerts = alerts[i-1]; 
						break;
		}
	} 
	if (!exist_alerts) {   //se non ho trovato avvisi di errore sulla sezione interessata
		var contenitore = document.getElementById(get_div_alert(i)); //div di errore sulla sezione interessata
		contenitore.style.display = "none"; //tolgo l'avviso di errore sulla sezione interessata
	}  
}


//Funzioni invocate da iscrizione.php

function controllaDati(i){ // effettua il controllo sulla sezione dati personali del form e mette/leva eventuali alert
  var existALFABETICO  = /^[A-Za-z]+[A-Za-zèùàòé\s\']*$/; //espressioni regolari
  var existCAP       = /\d{5}/;
  var existNUMERICI = /^\d*$/; 
  var campi = document.getElementsByTagName("INPUT");  // vettore dei campi di input
  var ok=true; //se ci sono stati errori
  switch(i){ 
    case 0:  //  campi: NOME
    case 1:  //         COGNOME
  	case 3: { //		CITTA'
		if (existALFABETICO.test(campi[i].value) == false) {
			mess= "Questo campo deve contenere un testo";
  			ok=false;
		}
    	break;
    }
    case 2: {  //campo TELEFONO
		if (existNUMERICI.test(campi[2].value) == false) {
			mess= "Questo campo \xE8 esclusivamente numerico";
  			ok=false;
		}
    	break;
    }
    case 4: { //campo CAP
		if (existCAP.test(campi[4].value) == false) {
			mess= "Un CAP \xE8 costituito da 5 cifre";
  			ok=false;
		}
    	break;
    }
  } //fine switch
  if (ok!=alerts[i]) 
	return;  //se ci sono alert ed il campo è errato ritorno "false", se non ci sono ed è corretto ritorno "true"
  if (!ok)      
  	insert_alert(i, mess); //non ci sono alert ed il campo è errato: metto l'avviso di errore (alert) su campo e sezione
  else 
  	remove_alert (i); //ci sono alert ed il campo è corretto: tolgo l'avviso di errore (alert) da campo e (forse) da sezione
  return; 
}

function controllaMail(i, vett){ // effettua il controllo sulla sezione mail del form e mette/leva eventuali alert
 var existMAIL = /[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/; //espressione regolare
 var campi = document.getElementsByTagName("INPUT"); //vettore dei campi input
 i+=5;  
 switch(i){ //a seconda del campo faccio controlli diversi
   case 5: {//STO ANALIZZANDO IL CAMPO "MAIL"
   		(campi[6]).value=""; //azzero il campo conferma password
		if (alerts[6])  //poichè azzero il campo di conferma devo togliere eventuali avvisi di errore su esso
			remove_alert(6); 
  		if (existMAIL.test(campi[5].value) == false){ //non è un indirizzo email
          	(campi[6]).setAttribute("readonly", "true");
			(campi[6]).style.background="grey"; //blocco il campo "Conferma Password"
			if (!alerts[5])  //se non c'era già metto l'avviso di errore sul campo "Mail"
				insert_alert(5, "Non \xE8 un indirizzo email"); 
          return;
   		} 
		else {	 //la mail ha un formato valido
			var elem = (campi[5]).value;
			if (find(vett, elem)) { //la mail è già presente nel DB (vett contiene tutte le mail ivi presenti)
				(campi[6]).setAttribute("readonly", "true"); 
				(campi[6]).style.background="grey";  //blocco il campo "Conferma Password"
				if (alerts[5]) //tolgo l'eventuale vecchio alert e ne metto uno nuovo
					remove_alert(5);
				insert_alert(5, "C'\xE8 gi\xE1  un utente registrato sul nostro sito con questa mail");
				return;
			}
			else {      //la mail è corretta è non è presente nel DB, tutto ok!
				(campi[6]).removeAttribute("readonly"); //molti browser controllano solo l'esistenza di readonly 
				(campi[6]).style.background="white";						
				if (alerts[5])  //levo leventuale avviso di errore sul campo "Mail"
					remove_alert(5); 
				return;
			}
   		}
   } //fine analisi campo "mail"
   case 6: {//STO ANALIZZANDO IL CAMPO "CONFERMA MAIL"
   		if (existMAIL.test(campi[6].value) == false){ //se il formato del campo non è quello di una mail
            if (alerts[6])  remove_alert(6);                 //se non c'era metto l'alert del formato sbagliato
            insert_alert(6, "Non \xE8 un indirizzo email");	 //se c'era lo tolgo e ci metto quello che ci vuole adesso (poteva coincidere)
          	return;
   		}  //il formato è giusto
  		   if (campi[5].value!=campi[6].value) { //le due mail non coincidono
				if (alerts[6]) remove_alert(6);  //se non c'era metto l'alert della non uguaglianza  sul campo "Conferma password"
    			insert_alert(6, "I due indirizzi di posta elettronica non coincidono");   //se c'era lo tolgo e ci metto quello che ci vuole adesso (poteva coincidere) 												 
  				return;
  		  }	
		  else { //tutto ok: le due mail sono in formato corretto e coincidono
					if (alerts[6]) 
					remove_alert(6); //se c'era un alert sul campo "Conferma password" lo tolgo
					return;
		  }
   		break; //non ci si arriva mai
  	} //fine analisi campo "conferma mail"
 }//fine switch	
}

function controllaPwd(i){  //è la funzione di controllo per entrambi i campi di password (inserimento e conferma)
 var existPASSWORD = /[a-zA-Z0-9]{8}/; //espressione regolare 
 var campi = document.getElementsByTagName("INPUT"); //vettore campi input del form
 i+=9;
 switch(i){ //distinguo quale dei 2 campi sto controllando
    case 9: {    //STO ANALIZZANDO IL CAMPO DI INSERIMENTO PASSWORD
		(campi[10]).value=""; //azzero il campo "conferma password"
		if (alerts[10])  //poichè azzero il campo di conferma devo togliere eventuali avvisi di errore su esso
            remove_alert(10); 
  		if (existPASSWORD.test(campi[9].value) == false) { //la password inserita non ha il corretto formato
          	(campi[10]).setAttribute("readonly", "true"); //  blocco il campo conferma (vuoto)
            (campi[10]).style.background="grey"; 		  //  e lo rendo grigio	
            if (!alerts[9])  //  se non c'era metto un avviso di errore sul campo di inserimento pwd
            	insert_alert(9, "Scegliere una password di 8 simboli alfanumerici"); 
          	return;															 
   		} 
        else {  //la password è ok
			(campi[10]).removeAttribute("readonly"); //molti browser controllano solo l'esistenza di readonly 
			(campi[10]).style.background="white";	 //libero il campo "conferma password"
			if (alerts[9])         //se c'era un vecchio avviso di errore su "password" lo rimuovo
				remove_alert(9); 
   			return;                      
   		}
   } //fine analisi "password" 
   case 10: {   //STO ANALIZZANDO IL CAMPO DI CONFERMA DELLA PASSWORD
   		if (existPASSWORD.test(campi[10].value) == false){  //la password confermata ha un formato sbagliato
            if (!alerts[10]) {  //se non c'era avviso dell'errore sul campo di inserimento lo metto
            	insert_alert(10, "Scegliere una password di 8 simboli alfanumerici"); 
			}
			else {   //altrimenti tolgo il vecchio avviso e metto uno nuovo, così non sbaglio mai
				remove_alert(10);
				insert_alert(10, "Scegliere una password di 8 simboli alfanumerici");
			}
          	return;   
   		}  //arrivati qui vuol dire che il formato del campo "conferma password" era corretto
  		if (campi[9].value!=campi[10].value) { //la password confermata ha formato ok ma è diversa dalla prima inserita
			if (!alerts[10])  //se non c'era avviso dell'errore lo metto
    			insert_alert(10, "Le due password non coincidono");   
  			else {	//tolgo il vecchio avviso e metto uno nuovo, così non sbaglio mai
				remove_alert(10); 
  				insert_alert(10, "Le due password non coincidono"); 
			}	 												 
  			return;
  		}	
		else { //la password confermata ha formato ok ed è uguale alla prima inserita: va tutto bene
			if (alerts[10])	     //tolgo il vecchio avviso (se c'era)
				remove_alert(10); 
			return;
		}
   }//fine analisi "conferma password" 
 }//fine switch	
}

function controllaCodice() { //controllo se il codice è stato letto/ascoltato correttamente 
	var codice_inserito = document.getElementById("codice");  //mi procuro il campo inserito
	var alert_codice= document.getElementById("span_code_err");
	var image = document.getElementById("nobot");
	if (codice_inserito.value!="679482") { //erorre sul codice
 		alert_codice.style.display="inline"; //mostro l'alert sul codice (rendendolo visibile)
		image.style.border="outset red"; //enfasi sull'immagine
		alerts[11]=true;  //segnalo la presenza di un alert su questo campo di input
	}
	else { //il codice è stato inserito correttamente 
 		alert_codice.style.display="none"; //levo l'eventuale alert sul campo
		image.style.border="none";
		alerts[11]=false; //segnale che non ci sono errori su questo campo
	}
	return;
}

function svuotaAlerts(){ //elimina tutti gli avvisi di errore presenti
  var i;
  for (i=0;i<=10;i++){
      if (alerts[i])                         
         remove_alert(i);   
  }
}
 
function check(){ //usando gli alerts comunica all'utente quali errori ci sono e ritorna true se va tutto ok
  var ok=true; //inizialmente non ho ancora registrato alcun errore
  var contenitore= document.getElementById("div_conferma"); //il div dove metterò gli avvisi di errore finali
  var campi = document.getElementsByTagName("INPUT"); //vettore degli input
  var campi_errati=""; //inizialmente non ci sono campi vuoti nè errati non avendo ancora cominciato la verifica
  var campi_vuoti="";
  var conta_errati=0, conta_vuoti=0;
  var i;  //contatore per cicli
  for (;errori_finali!=0; errori_finali--) //        rimuovo 0, 1 (ci sono solo errori di campi vuoti o errati)
	contenitore.removeChild(contenitore.lastChild);//o 2 (ci sono campi vuoti e campi errati) avvisi finali vecchi
  for (i=0;i<=11;i++){ //SCORRO TUTTI I CAMPI DI INPUT PER TROVARE CAMPI VUOTI O ERRATI
  		if ((campi[i]).value=="") { //se il campo è vuoto 
  		    if (i==3) {        //   se si tratta di Città 				
     			if (alerts[3]) //    e c'è un alert lo levo perchè essendo facoltativo va bene che sia vuoto
					remove_alert (3);     
				continue;	//salto alla prossima iterazione del for		
			}	 //non è il campo città					
			if (campi_vuoti!="") //aggiungo il nome del campo alla stringa coi campi vuoti
				campi_vuoti += ", ";
  			campi_vuoti += (campi[i]).name;
  			conta_vuoti++; //incremento il n° dei campi vuoti
  			continue;     //salto alla prossima iterazione del for
  		}
  		if (alerts[i]) {  
  			 if (campi_errati!="") 
				campi_errati += ", ";
  			 campi_errati += (campi[i]).name;
  			 conta_errati++;
  		}		
 }  //fine del for di ricerca dei campi vuoti/errati
 if (conta_errati!=0){ //SE C'E' ALMENO UN CAMPO ERRATO	
    errori_finali++; //va a 1
  	var nodo, nodo2;
  	if (conta_errati==1) //singolare (1 solo campo errato)
		nodo= document.createTextNode("Controlla il contenuto del campo ");
  	if (conta_errati>1)  //plurale (almeno 2 campi errati)
		nodo= document.createTextNode("Controlla il contenuto dei seguenti campi: ");
  	var r = document.createElement("div"); //inserisco l'avviso finale dei campi errati
  	r.setAttribute("class", "div_form");
  	var s = document.createElement("div");
  	s.setAttribute("class", "p_form");
  	r.appendChild(nodo);
  	r.appendChild(s);
  	s.appendChild(document.createTextNode(campi_errati));
  	contenitore.appendChild(r);
  	ok = false; //c'è almeno un errore di un qualche tipo
  }
  if (conta_vuoti!=0) { //SE C'E' ALMENO UN CAMPO VUOTO	
  	 errori_finali++;  //va a 1
  	 var nodo;
  	 if (conta_vuoti==1)  //singolare (1 solo campo vuoto)
		nodo2= document.createTextNode("Riempi il campo ");
  	 if (conta_vuoti>1)  //plurale (almeno 2 campi vuoti)
		nodo2 = document.createTextNode("Riempi i seguenti campi: ");
  	 var rr = document.createElement("div"); //inserisco l'avviso finale dei campi vuoti
  	 rr.setAttribute("class", "div_form");
  	 var ss = document.createElement("div");
  	 ss.setAttribute("class", "p_form");
  	 rr.appendChild(nodo2);
  	 rr.appendChild(ss);
  	 ss.appendChild(document.createTextNode(campi_vuoti));
  	 contenitore.appendChild(rr);
  	 ok = false; //c'è almeno un errore di un qualche tipo
  }
  var con = document.getElementById("contratto"); //PER FINIRE VERIFICO CHE IL CONTRATTO SIA STATO ACCETTATO
  if (!(con.checked)) { //non è stato spuntata l'accettazione termini
    window.alert("Devi dichiarare di accettare i termini del contratto"); //alert diretto sul contratto
    ok = false; //c'è almeno un errore di un qualche tipo
  }
  return ok;
}

