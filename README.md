EhcServer
=========

Einleitung
----------
Die EhcServer-Anwendung dient als Abstraktionsschicht zur Hausautomatisierung.
Als Webframework wird das Zend Framework in der Version 2.x eingesetzt.

Konfiguration
--------------
Einige Einstellungen befinden sich in den Dateien der config-Verzeichnisse des ZF2.

Datenbank
---------
Es existieren die Tabellen user, jobaevent, room. 
In der Tabelle jobaevent existieren alle Nachrichten des Systems.
Eine Nachricht hat einen Typ, der diese genau einem Bereich zuordnet.
health fuer den Gesundheitsbereich, message fuer Systemnachrichen.
Es kann sein, dass durch das Name-Attribut Subkategorien gebildet werden, etwa bei Warnung als Subkategorie von message.
Aktuell vorkommende Name-Type-Paare sind:
energy - Amperemessung;
energy - Voltmessung;
health - Blutdruckmessung;
health - Blutzuckermessung;
health - Gewichtsmessung;
health - Puls;
message - Protokoll;
message - Warnung; 
Des Weiteren gibt es den Status done, hier steht die 0 fuer Achtung und die 1 fuer Ok;

Datenbank-Dump (SQL)
--------------------
* Folgenden Code in Datei ehcserver_master.sql speichern;
* Sicherstellen, dass DB ehcserver_master.sql lokal existiert;
* Dump einlesen via mysql -u [uname] -p [pass] [ehcserver_master] < ehcserver_master.sql (bei leerem PW entfaellt -p);
** START DB DUMP
DROP TABLE IF EXISTS `jobaevent`;
CREATE TABLE `jobaevent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `type` varchar(11) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `done` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=304 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `room`;
CREATE TABLE `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `humidity` int(11) NOT NULL,
  `temperature` int(11) NOT NULL,
  `switch` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
LOCK TABLES `jobaevent` WRITE;
INSERT INTO `jobaevent` VALUES (2,'Blutzuckermessung','180','health','2013-10-03 16:43:10','2013-11-20 19:00:00',1),
(4,'Gewichtsmessung','75','health','2013-10-14 06:00:00','0000-00-00 00:00:00',1),
(5,'Blutzuckermessung','175','health','2013-10-17 19:01:27','0000-00-00 00:00:00',1),
(6,'Blutzuckermessung','175','health','2013-10-22 19:01:31','0000-00-00 00:00:00',1),
(7,'Gewichtsmessung','83','health','2013-10-25 19:02:52','0000-00-00 00:00:00',0),
(8,'Gewichtsmessung','82.5','health','2013-10-27 20:02:52','0000-00-00 00:00:00',1),
(9,'Gewichtsmessung','83','health','2013-10-27 20:03:17','0000-00-00 00:00:00',1),
(10,'Gewichtsmessung','82.5','health','2013-10-28 20:03:17','0000-00-00 00:00:00',1),
(11,'Gewichtsmessung','83','health','2013-10-22 19:03:20','0000-00-00 00:00:00',1),
(12,'Gewichtsmessung','82.5','health','2013-10-29 20:03:20','0000-00-00 00:00:00',1),
(13,'Pulsmessung','65','health','2013-10-31 20:04:40','0000-00-00 00:00:00',1),
(14,'Pulsmessung','68','health','2013-11-02 20:05:14','0000-00-00 00:00:00',1),
(15,'Blutzuckermessung','154','health','2013-11-05 20:06:13','0000-00-00 00:00:00',1),
(16,'Pulsmessung','159','health','2013-11-13 20:06:44','0000-00-00 00:00:00',1),
(278,'Protokoll','Alle Systemnachrichten wurden gelöscht.','message','2014-05-14 15:13:41','2014-05-14 15:13:41',0),
(279,'Protokoll','Licht Nummer Zwei im Raum \'Besprechungsraum\' ausgeschaltet.','message','2014-06-17 10:52:36','2014-06-17 10:52:36',0),
(280,'Protokoll','Licht Nummer Eins im Raum \'Besprechungsraum\' eingeschaltet.','message','2014-06-17 10:52:41','2014-06-17 10:52:41',0),
(281,'Protokoll','Licht Nummer Zwei im Raum \'Besprechungsraum\' ausgeschaltet.','message','2014-07-03 15:06:17','2014-07-03 15:06:17',0),
(282,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-07 13:34:13','2014-07-07 13:34:13',0),
(283,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-07 13:55:10','2014-07-07 13:55:10',0),
(284,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-07 14:03:30','2014-07-07 14:03:30',0),
(285,'Warnung','eine warnung','message','2014-07-07 14:11:01','0000-00-00 00:00:00',1),
(286,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-07 14:19:51','2014-07-07 14:19:51',0),
(287,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-28 13:47:10','2014-07-28 13:47:10',0),
(288,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-28 13:47:18','2014-07-28 13:47:18',0),
(289,'Amperemessung','5.1','energy','2014-08-12 16:24:04','0000-00-00 00:00:00',0),
(290,'Voltmessung','180','energy','2014-08-12 16:24:55','0000-00-00 00:00:00',1),
(291,'Voltmessung','220','energy','2014-08-12 16:28:55','0000-00-00 00:00:00',0),
(292,'Voltmessung','221','energy','2014-08-12 16:29:00','0000-00-00 00:00:00',0),
(293,'Amperemessung','4.2','energy','2014-08-12 16:29:11','0000-00-00 00:00:00',0),
(294,'Amperemessung','4.3','energy','2014-08-12 16:29:15','0000-00-00 00:00:00',0);
UNLOCK TABLES;
LOCK TABLES `room` WRITE;
INSERT INTO `room` VALUES 
(1,'Besprechungsraum',20,22,0),
(2,'Energie',56,23,0),
(3,'Geschäftsführung',95,18,0),
(4,'Hiwiraum',60,20,0),
(5,'Infotainment',61,21,0),
(6,'LivingLab',62,22,0);
UNLOCK TABLES;
LOCK TABLES `user` WRITE;
INSERT INTO `user` VALUES 
(1,NULL,'guest@jochen-bauer.net',NULL,'$2y$14$g3NucbIMcGNMgxvEYHJ0y.OsVH048OQCpVBMDcx4THbSZ9UZ8hiaq',NULL);
UNLOCK TABLES;
** ENDE DB DUMP

MVC
---
JobaEvent repraesentiert Mitteilungen. Das Attribut type regelt, welche Art die
Mitteilung ist. Im index-View werden die Nachrichten gemaess der Typen in Listen 
gebuendelt. Aktuell existieren Log-Nachrichten, Gesundheitsnachrichten und Warnungen.

Konventionen fuer Entwickler
----------------------------
Fuer Feedback sind normalerweise FlashMessages zu nutzen, siehe IndexController indexAction() accessDenied;
Zeichenketten sollten in der Konfigurationsdatei im ehomeBundle-Array platziert werden.

Snippets
--------
Apache-2-Error-Datei anzeigen: tail -f /var/log/apache2/error.log 
Anmelden am Datenbankserver: mysql -u [user] -p [pass] 
Datenbanken anzeigen: show databases; 
Datenbank anwaehlen: use [database];
Datenbank einspielen: mysql -u [uname] -p[pass] [db_to_restore] < [backupfile.sql] 
Datenbank exportieren: mysqldump -u [user] -p  [dbname] > /home/myusername/backupfile.sql
Datenbank kopieren in shell: mysqldump -h [server] -u [user] -p[password] db1 | mysql -h [server] -u [user] -p[password] db2 
Tabellen anzeigen: show tables; 
Tabelleneintraege anzeigen: select * from [table]; 
Tabelleneintraege aktualisieren: update room set humidity=20 where id=1; 
Tabelleneintrag einpflegen: insert into jobaevent (name, value, type, done) values ('warnung', 'eine warnung', 'message', 0); 
Tabellenspalte hinzufuegen (add) / entfernen (drop column) / aendern (change): alter table room drop column lightone; alter table room change column lighttwo switch int(11); 
Tabellenspaltenstruktur anzeigen: show columns from room; 
Eclipse-PHP-Debugging (XDebug-Installation: coderblog.de/how-to-install-use-configure-xdebug-ubuntu/; 
XDebug-Eclipse-Verbindung, localhost und leeren Restpfad sollte auf index-Action verweisen) 

Abhaengigkeiten
---------------
* ZendFramework in Ordner vendor unterbringen;
* ZendFramework-Module EdpModuleLayouts, ZfcBase, ZfcUser;
* PHP-Bibliothek phpmailer;

Anpassungen
-----------
* local.php in /config/autoload anlegen und anpassen:
return array(
    'db' => array(
        'username' => 'xxx', 
        'password' => 'xxx',
    ),
);
* global.php in /config/autoload anpassen;
* Umgebungsvariable auf zendframework in public/index.php sicherstellen:
putenv('ZF2_PATH=/home/meinusername/workspacephp/EhcServer/vendor/zendframework/zendframework/library');
putenv('APPLICATION_ENV=development');

Deployment
----------
* Aktuelle Github-Version des master-Branches holen;
* Aktuelle Zend Framework Version holen;
* Aktuelle Version der ZendFramework-Module holen;
* Aktuelle Version der PHP-Bibliotheken holen;
* Datenbank bauen bzw. Dump einspielen, Namenskonvention ehcserver_branchname;
* Automatisierte Tests starten;
* Manuelle Tests ausfuehren;

Manuelles Testprotokoll
-----------------------
* Login in Anwendung;
* Anschalten des Ventilators von Startseite;
* Anschalten des Ventialtors aus Detailsicht (Raum);
* Testen der FlashMessages beim unberechtigten Index-Seiten-Zugriffs;
* Wegklicken der zweiten erzeugten Warnungsbox (Test toggleMessage());
* Test der JSON-Schnittstelle mit Client;

Umgebungen
----------
* Master: (ehome-Umgebung)
* Default
* Sul

