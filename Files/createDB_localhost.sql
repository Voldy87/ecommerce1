#,"localhost","root","","tia"
#  Gli script php accedono al db con le credenziali sopra (file dbconfig.php sotto "Classi"), nell ordine host, user, password e database
#  la virgola dopo il # serve per dare un formato CSV alla prima riga, che viene letta dallo script "dbconfig.php"
#     Per progetti non didattici mai accedere come root senza password (password vuota: "").
#     Se cambio il nome del database alla riga 1 occorre effettuare la stessa modifica alle righe 7,8,9.
# 
DROP DATABASE if exists tia;  #creo il database, eventualmente eliminando un omonimo già presente
CREATE DATABASE tia;
USE tia;
#
#creo le tabelle
#
CREATE TABLE utenti (
ID_Utente INTEGER NOT NULL,
Nome VARCHAR (20) NOT NULL,
Cognome VARCHAR (20) NOT NULL,
Sesso CHAR (1) NOT NULL,
Telefono VARCHAR (15) NOT NULL,
Citta VARCHAR (45),
CAP CHAR (5) NOT NULL,
Regione VARCHAR (30),
Mail VARCHAR (45) NOT NULL,
Password CHAR (8) NOT NULL,
Newsletter BOOLEAN NOT NULL,
PRIMARY KEY (ID_Utente)
);

CREATE TABLE prodotti (
ID_Prodotto INTEGER NOT NULL,
Nome VARCHAR (20) NOT NULL,
Prezzo REAL NOT NULL,
PRIMARY KEY (ID_Prodotto)
);


CREATE TABLE acquisti (
ID_Utente INTEGER NOT NULL,
ID_Prodotto INTEGER NOT NULL,
Numero_Pezzi INTEGER NOT NULL,
PRIMARY KEY (ID_Prodotto, ID_Utente)
);

CREATE TABLE wishlist (
ID_Utente INTEGER NOT NULL,
Lista_Prodotti VARCHAR (100),
PRIMARY KEY (ID_Utente)
);

#
# popolo le tabelle
#

INSERT INTO utenti VALUES (0, 'John', 'Doe', 'M', '1122334455', 'Paperopoli',
                          '00000', 'Calisota', 'fakemail@fakeprovider.fak', 'password', true);
INSERT INTO utenti VALUES (1, 'Yulyan', 'Bazwezzyechkin', 'M', '0987654321', 'Livorno',
                          '57127', 'Toscana', 'yulyan1888@fakeprovider.fak', 'password', true);
INSERT INTO utenti VALUES (2, 'Maria', 'Poppollannattorre', 'F', '123456789', NULL,
                          '48126', NULL, 'poppollannattorre@fakeprovider.fak', 'geronimo', false);
													
INSERT INTO prodotti VALUES (0, 'Air_Bold', 129);
INSERT INTO prodotti VALUES (1, 'Bad_Boys', 99);
INSERT INTO prodotti VALUES (2, 'Big_Bum', 150);
INSERT INTO prodotti VALUES (3, 'BlackJack_Combo', 139);
INSERT INTO prodotti VALUES (4, 'Bowl_Roll', 299);
INSERT INTO prodotti VALUES (5, 'Bug_Style', 78);
INSERT INTO prodotti VALUES (6, 'Check_This', 79);
INSERT INTO prodotti VALUES (7, 'Crocodile_Dundee', 89);
INSERT INTO prodotti VALUES (8, 'Diavolo_Blu', 69);
INSERT INTO prodotti VALUES (9, 'Diavolo_Rosso', 190);

INSERT INTO acquisti VALUES (0, 2, 2);
INSERT INTO acquisti VALUES (0, 3, 3);
INSERT INTO acquisti VALUES (1, 1, 1);

INSERT INTO wishlist VALUES (0, '3.7.8.');
INSERT INTO wishlist VALUES (1, '2.');
INSERT INTO wishlist VALUES (2, '1.');
