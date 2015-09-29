/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : ecommerce

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2015-07-22 16:22:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for acquisti
-- ----------------------------
DROP TABLE IF EXISTS `acquisti`;
CREATE TABLE `acquisti` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Costo` double NOT NULL,
  `Quantita` int(11) NOT NULL,
  `IDProd` int(11) NOT NULL,
  `IDSped` int(11) DEFAULT NULL,
  `IDStato` int(11) DEFAULT NULL,
  `IDOrdine` int(12) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDProd` (`IDProd`),
  KEY `IDStato` (`IDStato`) USING BTREE,
  KEY `acquisti_ibfk_3` (`IDSped`),
  KEY `IDGruppo` (`IDOrdine`),
  CONSTRAINT `acquisti_ibfk_1` FOREIGN KEY (`IDProd`) REFERENCES `prodotti` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `acquisti_ibfk_3` FOREIGN KEY (`IDSped`) REFERENCES `spedizioni` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `acquisti_ibfk_4` FOREIGN KEY (`IDStato`) REFERENCES `stati` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `acquisti_ibfk_5` FOREIGN KEY (`IDOrdine`) REFERENCES `ordini` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

-- ----------------------------
-- Records of acquisti
-- ----------------------------
INSERT INTO `acquisti` VALUES ('2', '327.82', '3', '2', '28', '2', '1');
INSERT INTO `acquisti` VALUES ('3', '720', '2', '7', '28', '2', '1');
INSERT INTO `acquisti` VALUES ('4', '15.83', '1', '10', '29', '2', '2');
INSERT INTO `acquisti` VALUES ('5', '379.99', '1', '5', '29', '2', '2');
INSERT INTO `acquisti` VALUES ('6', '359.98', '1', '6', '29', '2', '2');

-- ----------------------------
-- Table structure for categorie
-- ----------------------------
DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  `Desc` text CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  `Icon` varchar(255) COLLATE utf32_bin DEFAULT NULL,
  `IDRep` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `categorie_ibfk_1` (`IDRep`),
  CONSTRAINT `categorie_ibfk_1` FOREIGN KEY (`IDRep`) REFERENCES `reparti` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

-- ----------------------------
-- Records of categorie
-- ----------------------------
INSERT INTO `categorie` VALUES ('1', 'Case', 'Dove riporre il vostro hardware!', 'case.png', '1');
INSERT INTO `categorie` VALUES ('2', 'Schede Video', 'Se vuoi giocare, qua trovi le migliori schede video!', 'schedavideo.png', '1');
INSERT INTO `categorie` VALUES ('3', 'Hard Disk', 'Per poter conservare tutti i vostri dati', 'harddisk.png', '1');
INSERT INTO `categorie` VALUES ('5', 'DVD', 'Film e Serie TV per i vecchi lettori DVD', 'dvd.png', '4');
INSERT INTO `categorie` VALUES ('10', 'Console', 'Una console è un dispositivo elettronico di elaborazione di tipo special purpose concepito esclusivamente o primariamente per giocare con i videogiochi.', 'console.png', '2');
INSERT INTO `categorie` VALUES ('11', 'Bluray', 'I tuoi film preferiti in alta qualità!', 'bluray.png', '4');

-- ----------------------------
-- Table structure for corrieri
-- ----------------------------
DROP TABLE IF EXISTS `corrieri`;
CREATE TABLE `corrieri` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nominativo` varchar(40) CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  `Costo` double NOT NULL,
  `Tempi_Consegna` varchar(255) COLLATE utf32_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

-- ----------------------------
-- Records of corrieri
-- ----------------------------
INSERT INTO `corrieri` VALUES ('1', 'Bartolini', '10', '6-7 Giorni');
INSERT INTO `corrieri` VALUES ('2', 'Poste Italiane', '0', '1 Mese');
INSERT INTO `corrieri` VALUES ('3', 'DHL', '15', '4-5 Giorni');
INSERT INTO `corrieri` VALUES ('4', 'SDA', '10', '1 Settimana');
INSERT INTO `corrieri` VALUES ('5', 'Bartolini Express', '20', '2-3 Giorni');
INSERT INTO `corrieri` VALUES ('6', 'DHL Ultra', '30', '1 Giorno');

-- ----------------------------
-- Table structure for ordini
-- ----------------------------
DROP TABLE IF EXISTS `ordini`;
CREATE TABLE `ordini` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Data_Acq` datetime DEFAULT NULL,
  `IDUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDUser` (`IDUser`),
  CONSTRAINT `ordini_ibfk_1` FOREIGN KEY (`IDUser`) REFERENCES `utenti` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ordini
-- ----------------------------
INSERT INTO `ordini` VALUES ('1', '2015-07-22 15:30:47', '1');
INSERT INTO `ordini` VALUES ('2', '2015-07-22 15:34:44', '1');

-- ----------------------------
-- Table structure for prodotti
-- ----------------------------
DROP TABLE IF EXISTS `prodotti`;
CREATE TABLE `prodotti` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  `Desc` text CHARACTER SET utf32 COLLATE utf32_roman_ci,
  `Prezzo` double(11,2) DEFAULT NULL,
  `Quantita` int(11) DEFAULT NULL,
  `IMG` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci DEFAULT NULL,
  `IDCat` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `prodotti_ibfk_1` (`IDCat`),
  KEY `ID` (`ID`),
  CONSTRAINT `prodotti_ibfk_1` FOREIGN KEY (`IDCat`) REFERENCES `categorie` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

-- ----------------------------
-- Records of prodotti
-- ----------------------------
INSERT INTO `prodotti` VALUES ('2', 'Aerocool X-Warrior Devil Red Edition', 'AeroCool X-Warrior Devil Red Edition EN56687', '136.59', '12', 'xwarrior.png', '1');
INSERT INTO `prodotti` VALUES ('3', 'Corsair Graphite 760T Black Big Tower', 'Corsair Graphite 760T', '193.43', '30', 'corsair_graphite_760T_black.png', '1');
INSERT INTO `prodotti` VALUES ('4', 'Aerocool GT-S White Edition', 'Aerocool GT-S. Fattore di forma: Full-Tower, Modello: PC, Materiali: SECC. Posizione alimentazione: Fondo. Front fans installed: 1x 200 mm, Diametro delle ventole frontali supportato: 20 cm, Rear fans installed: 1x 140 mm. Dimensioni di hard disk drive supportati: 63, 5, 88, 9 mm (2.5, 3.5\"). Larghezza: 25,5 cm, Profondità: 64 cm, Altezza: 56 cm', '139.76', '30', 'Aerocool_GT-S_White_Edition.png', '1');
INSERT INTO `prodotti` VALUES ('5', 'PlayStation 4', 'Il sistema PlayStation4 ti consente di giocare al massimo, grazie a giochi dinamici e connessi, grafica e velocità di prim\'ordine, personalizzazione intelligente e funzionalità social estremamente integrate. \r\n\r\nIl sistema PS4 è pensato per i videogiocatori, facendo in modo che sulla piattaforma siano disponibili i giochi migliori e le esperienze più coinvolgenti. Il cuore del sistema PS4 è formato da un chip personalizzato che contiene otto core x86-64 e un processore grafico di ultima generazione da 1,84 TFLOPS con 8 GB di memoria a sistema unificato e GDDR5 ultra-rapida, che semplifica la creazione di giochi e aumenta la qualità dei contenuti disponibili per la piattaforma. Il risultato finale sono nuovi giochi dotati di grafica ricca e di alta qualità e di esperienze coinvolgenti come mai prima d\'ora.\r\n\r\nL\'architettura interna di PS4, dal disco ottico al meccanismo di alimentazione, è stata studiata per mantenere l\'hardware leggero e sottile, in modo da esaltare la flessibilità progettuale.\r\nPS4 è dotata di un design semplice e moderno, accentuato da linee nette e definite. La superficie di PS4 è suddivisa in quattro sezioni, come se quattro blocchi distinti fossero uniti per creare una figura unica con vano disco, tasti, spia di accensione e ventola, collocati all’interno delle sezioni. La spia di accensione, posizionata nella parte superiore, emette la tipica luce blu PlayStation quando la console viene accesa. Le parti anteriore e posteriore sono leggermente angolate: in questo modo, l\'utente non solo ha un facile accesso al tasto di accensione e al vano disco quando la console è posizionata in orizzontale o verticale, ma ciò permette anche di nascondere i cavi connessi all\'unità. Il colore nero e la finitura lucida e opaca conferiscono un look sofisticato e adatto a qualsiasi tipo di ambiente.\r\n\r\nIl controller DualShock4 vanta innovazioni che offrono esperienze di gioco ancora più coinvolgenti, incluso un sensore a sei assi a elevata sensibilità e un touch pad situato nella parte superiore del controller che offre nuovi modi per giocare e interagire con i contenuti. Con il nuovo tasto SHARE, invece, riproduzione in streaming di video e opzioni di condivisione sono sempre a portata di dito.\r\n\r\nAffronta infinite sfide personali con la tua comunità e condividi i tuoi epici trionfi con la semplice pressione di un tasto. Premi il \"tasto SHARE\" sul controller, passa in rassegna gli ultimi minuti di gioco, seleziona la parte che ti interessa e torna a giocare: il video verrà caricato mentre giochi. Il sistema PS4 migliora anche la possibilità di assistere alle partite degli altri, consentendoti di trasmettere le tue partite in tempo reale.', '379.99', '245', 'ps4_black.png', '10');
INSERT INTO `prodotti` VALUES ('6', 'Xbox One', 'La confezione include:\r\nConsole Xbox One HD 500GB\r\nController Wireless Standard\r\nCuffie con microfono (Chat Headset, con filo)\r\nCavo HDMI\r\nAlimentatore\r\n14 giorni abbonamento di prova Xbox Live Gold', '399.98', '247', 'xbox_one.png', '10');
INSERT INTO `prodotti` VALUES ('7', 'ASUS STRIX - Nvidia GeForce GTX 970, OC, 4 GB (3.5GB+0.5GB) GDDR5, 256 bit', 'ASUS 90YV07F0-M0NA00. Processore grafico / fornitore: NVIDIA, Processore grafico: GeForce GTX 970, Frequenza del processore: 1114 MHz. Memoria dell\'adattatore grafico discreto: 4 GB, Tipo memoria adattatore grafico: GDDR5-SDRAM, Ampiezza dati: 256 Bit. Tipo interfaccia: PCI Express 3.0, Versione HDMI: 2.0, versione DisplayPort: 1.2. Versione DirectX: 12.0, Versione OpenGL: 4.4. Tipo di raffreddamento: Attivo', '360.00', '65', 'asus_strix_970_oc.jpg', '2');
INSERT INTO `prodotti` VALUES ('8', 'Western Digital WD10EZEX Caviar BLUE HardDisk SATA 1 TB, 64MB Cache', 'HDA 1000GB WD WD10EZEX 7200rpm 64MB', '54.48', '90', 'wd_wd10ezex_caviar_blue.png', '3');
INSERT INTO `prodotti` VALUES ('9', 'Harry Potter Collezione Completa (8 Dvd)', 'Contenuti del cofanetto:\r\n-Harry Potter e La Pietra Filosofale (2001)\r\n-Harry Potter e La Camera Dei Segreti (2002)\r\n-Harry Potter e Il Prigioniero Di Azkaban (2004)\r\n-Harry Potter e Il Calice Di Fuoco (2005)\r\n-Harry Potter e L\'Ordine Della Fenice (2007)\r\n-Harry Potter e Il Principe Mezzosangue (2009)\r\n-Harry Potter e I Doni Della Morte - Parte 01 (2010)\r\n-Harry Potter e I Doni Della Morte - Parte 02 (2011) ', '22.99', '2495', 'dvd_harrypotter_collezione_completa.jpg', '5');
INSERT INTO `prodotti` VALUES ('10', 'American Sniper', 'Attori: Bradley Cooper, Kyle Gallner, Cole Konis, Ben Reed, Elise Robertson\r\nRegista: Clint Eastwood\r\nFormato: PAL\r\nAudio: Italian (Dolby Digital 5.1), English (Dolby Digital 5.1), French (Dolby Digital 5.1)\r\nLingua: Italiano, Inglese, Francese\r\nSottotitoli: Italiano, Inglese, Francese, Greco, Olandese\r\nRegione: Regione 2 (Ulteriori informazioni su Formati DVD.)\r\nFormato immagine: 1.85:1\r\nNumero di dischi: 1\r\nStudio: Warner Home Video\r\nData versione DVD: 13 mag. 2015\r\nDurata: 126 minuti', '15.83', '424', 'dvd_american_sniper.jpg', '5');
INSERT INTO `prodotti` VALUES ('11', 'Il Signore Degli Anelli - La Trilogia Extended Edition (6 Blu-Ray+9 Dvd Extra)', 'Una delle più magiche e spettacolari avventure nella storia del cinema prende vita con queste edizioni estese su Blu-ray della trilogia \"Il Signore Degli Anelli\", donando un\'immagine nitida e un suono limpido. \r\n\r\nA seconda della durata di ogni edizione estesa e al fine di presentare i film in qualità perfetta, ogni film è diviso in due dischi Blu-ray.\r\n\r\nCaratteristiche speciali:\r\n\r\nQuesto cofanetto include più di 26 ore di contenuti aggiuntivi, seguiti da documentari, creati da Costa Botes, che contengono i backstage. Costa Botes è stato in grado di girare un backstage avvincente. \r\n\r\nQuesti documentari, con oltre quattro ore e mezza di filmati, mostrano le difficoltà che hanno dovuto affrontare i registi, il cast e la troupe durante le riprese, ma includono anche momenti comici e personali sul set. I documentari di Costa Botes contengono caratteristiche speciali create da Michael Pellerin per rendere questo cofanetto il più completo in assoluto. \r\n\r\nLa Trilogia \"Il Signore Degli Anelli\" - L\'Edizione Estesa include un remaster del film \"Il Signore Degli Anelli - La Compagnia Dell\'Anello\".\r\n\r\n\r\nL\'interpretazione su schermo di Peter Jackson del libro \"Il Signore Degli Anelli\" di Tolkien. Tutti e tre i film compresi in un elegante cofanetto(in cartone e include 3 amaray).', '29.99', '415', 'bluray_signoreanelli_trigloa_extra.jpg', '11');
INSERT INTO `prodotti` VALUES ('12', 'Star Wars - Saga 9 Blu-ray', 'Star Wars: La Saga Completa. L\'intera collezione finalmente a casa tua! \r\n\r\n\r\nA partire dal 13 settembre finalmente disponibile in Italia, in un unico cofanetto Blu-ray da collezione, l\'intera Saga di Star Wars. Un\'esperienza unica in 9 dischi Blu-ray per godere della magia dell\'avventura più amata nella storia del cinema con l’incredibile qualità dell’alta definizione e con l’audio originale 6.1 DTS Surround. \r\n\r\nOltre ai 6 film della serie il cofanetto offre 40 ore di contenuti speciali, scene tagliate, estese ed alternative, nuovi documentari e una sezione trasversale sulle innumerevoli parodie di Star Wars apparse nella cultura pop degli ultimi 30 anni. Tra le tante imperdibili esclusive anche immagini dietro le quinte, making of, interviste, documentari vintage, modellini, costumi e tanto altro ancora. \r\n\r\nImmergetevi in una visione senza precedenti di \"una galassia lontana, lontana\". ', '71.27', '243', 'bluray_starwars_saga_completa.jpg', '11');
INSERT INTO `prodotti` VALUES ('13', 'Padrino Trilogia (Ed. Restaurata) (4 Blu-Ray)', 'Film contenuti nel cofanetto Il padrino (The Godfather)Principali interpretiMarlon Brando; Al Pacino; James Caan; Richard Castellano; Robert Duvall; Diane Keaton; Sterling Hayden; Richard Conte; John Marley; Talia Shire; John Cazale; Franco CittiRegiaFrancis Ford CoppolaProduzioneParamount Home Entertainment, 2007 Il padrino. Parte seconda (The Godfather, Part II)Principali interpretiAl Pacino; Robert Duvall; Diane Keaton; Robert De Niro; Talia Shire; Morgana King; John Cazale; Marianna Hill; Lee Strasberg; Leopoldo Trieste; Roger CormanRegiaFrancis Ford CoppolaProduzioneParamount Home Entertainment, 2007 Il padrino. Parte terza (The Godfather, Part III)Principali interpretiGeorge Hamilton; Bridget Fonda; Joe Mantegna; Eli Wallach; Andy Garcia; Talia Shire; Diane Keaton; Al Pacino; Raf Vallone; Gregory Corso; Sofia Coppola; Franco CittiRegiaFrancis Ford CoppolaProduzioneParamount Home Entertainment, 2007 Il padrino. Supplements DiscProduzioneParamount Home Entertainment, 2008', '21.00', '3', 'bluray_padrino_trilogia.jpg', '11');

-- ----------------------------
-- Table structure for recensioni
-- ----------------------------
DROP TABLE IF EXISTS `recensioni`;
CREATE TABLE `recensioni` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Titolo` text CHARACTER SET latin1 NOT NULL,
  `Testo` text CHARACTER SET latin1 NOT NULL,
  `Data` datetime NOT NULL,
  `Voto` int(11) NOT NULL,
  `IDAcq` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDAcq` (`IDAcq`),
  CONSTRAINT `recensioni_ibfk_1` FOREIGN KEY (`IDAcq`) REFERENCES `acquisti` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

-- ----------------------------
-- Records of recensioni
-- ----------------------------

-- ----------------------------
-- Table structure for reparti
-- ----------------------------
DROP TABLE IF EXISTS `reparti`;
CREATE TABLE `reparti` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  `Desc` text CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

-- ----------------------------
-- Records of reparti
-- ----------------------------
INSERT INTO `reparti` VALUES ('1', 'Informatica', 'Prodotti per il mondo del computer');
INSERT INTO `reparti` VALUES ('2', 'Elettronica', 'Tutto ciò che riguarda l\'elettronica!');
INSERT INTO `reparti` VALUES ('4', 'Film', 'Tutte le ultime novità dal mondo dello spettacolo');

-- ----------------------------
-- Table structure for sconti
-- ----------------------------
DROP TABLE IF EXISTS `sconti`;
CREATE TABLE `sconti` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Percentuale` int(10) DEFAULT '0',
  `IDProd` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDProd` (`IDProd`),
  CONSTRAINT `sconti_ibfk_1` FOREIGN KEY (`IDProd`) REFERENCES `prodotti` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

-- ----------------------------
-- Records of sconti
-- ----------------------------
INSERT INTO `sconti` VALUES ('1', '20', '2');
INSERT INTO `sconti` VALUES ('2', '10', '6');

-- ----------------------------
-- Table structure for spedizioni
-- ----------------------------
DROP TABLE IF EXISTS `spedizioni`;
CREATE TABLE `spedizioni` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Data_Part` date DEFAULT NULL,
  `Data_Arr` date DEFAULT NULL,
  `Luogo_Part` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  `Citta_Arr` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  `Indirizzo_Arr` int(5) DEFAULT NULL,
  `CAP_Arr` varchar(255) COLLATE utf32_bin DEFAULT NULL,
  `IDCorr` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDCorr` (`IDCorr`),
  CONSTRAINT `spedizioni_ibfk_1` FOREIGN KEY (`IDCorr`) REFERENCES `corrieri` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

-- ----------------------------
-- Records of spedizioni
-- ----------------------------
INSERT INTO `spedizioni` VALUES ('20', null, null, 'Messina', 'Messina', '0', '98125', '6');
INSERT INTO `spedizioni` VALUES ('21', null, null, 'Messina', 'Messina', '0', '98125', '6');
INSERT INTO `spedizioni` VALUES ('22', null, null, 'Messina', 'Messina', '0', '98125', '4');
INSERT INTO `spedizioni` VALUES ('23', null, null, 'Messina', 'Messina', '0', '98125', '1');
INSERT INTO `spedizioni` VALUES ('24', null, null, 'Messina', 'Messina', '0', '98125', '5');
INSERT INTO `spedizioni` VALUES ('25', null, null, 'Messina', 'Messina', '0', '98125', '1');
INSERT INTO `spedizioni` VALUES ('26', null, null, 'Messina', 'Messina', '0', '98125', '3');
INSERT INTO `spedizioni` VALUES ('27', null, null, 'Messina', 'Messina', '0', '98125', '6');
INSERT INTO `spedizioni` VALUES ('28', null, null, 'Messina', 'Messina', '0', '98125', '6');
INSERT INTO `spedizioni` VALUES ('29', null, null, 'Messina', 'Messina', '0', '98125', '6');

-- ----------------------------
-- Table structure for stati
-- ----------------------------
DROP TABLE IF EXISTS `stati`;
CREATE TABLE `stati` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

-- ----------------------------
-- Records of stati
-- ----------------------------
INSERT INTO `stati` VALUES ('1', 'In Consegna');
INSERT INTO `stati` VALUES ('2', 'In Preparazione');
INSERT INTO `stati` VALUES ('3', 'In Spedizione');
INSERT INTO `stati` VALUES ('4', 'Consegnato');

-- ----------------------------
-- Table structure for utenti
-- ----------------------------
DROP TABLE IF EXISTS `utenti`;
CREATE TABLE `utenti` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  `Cognome` varchar(255) COLLATE utf32_bin DEFAULT NULL,
  `Citta` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  `Indirizzo` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  `CAP` int(5) DEFAULT NULL,
  `Num_Tel` varchar(10) CHARACTER SET utf32 COLLATE utf32_roman_ci NOT NULL,
  `Data_Nasc` date NOT NULL,
  `Cod_Fisc` varchar(16) CHARACTER SET utf32 COLLATE utf32_roman_ci DEFAULT NULL,
  `Username` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci DEFAULT NULL,
  `Email` varchar(255) CHARACTER SET utf32 COLLATE utf32_roman_ci DEFAULT NULL,
  `Rank` int(10) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

-- ----------------------------
-- Records of utenti
-- ----------------------------
INSERT INTO `utenti` VALUES ('1', 'Davide', 'Iuffrida', 'Messina', 'Via Calispera Pal.P n.2', '98125', '3457995249', '1994-01-20', 'FFRDVD94A20F158W', 'Daviex', '200194', 'david.iuffri94@hotmail.it', '4');
INSERT INTO `utenti` VALUES ('3', 'Davide', 'Iuffrida', 'Messina', 'Via Calispera Pal.P n.2', '98125', '3457995249', '1994-01-20', 'FFRDVD94A20F158W', 'Rikku', '200194', 'adminrikku@gmail.com', '1');
