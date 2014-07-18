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
Es existieren JobaEvents und Rooms. 

MVC
---
JobaEvent repraesentiert Mitteilungen. Das Attribut type regelt, welche Art die
Mitteilung ist. Im index-View werden die Nachrichten gemaess der Typen in Listen 
gebuendelt. Aktuell existieren Log-Nachrichten, Gesundheitsnachrichten und Warnungen.

Philosophie und Strategie
-------------------------
Der master-Branch repraesentiert die generische Version. Um Erfahrungswerte zu sammeln,
existieren pro Instanz einer intelligenten Wohnung eigene Branches. Pro Instanz wird 
eine eigene Datenbank angelegt.

Anleitung zum Anpassen an eine neue Umgebung
--------------------------------------------
Ordner der Installation pruefen, zf2, etc.;
Kopie der vendor-Inhalte ins vendor-Verzeichnis sollte passen;
Datenbank kopieren in neuen Namen;
Neuen Branch anlegen und einchecken, neuer Branch sollte erkannt werden;
Netzwerkverbindungen zu Router und LAN/Internet pruefen;
Verbindung zu fhem verifizieren, etwa mit Funksteckdose;
Raumeigenschaften definieren bzw. ueberpruefen;
Datenbank mit neuen Raeumen bestuecken und Datenbank anpassen;
Aenderungen auf die Klassen Room und RoomTable durchziehen;
Floorplan anpassen bzw. auf leeren String setzen - wird dann ausgeblendet;

Hilfreiche Befehle
------------------
Apache-2-Error-Datei anzeigen: tail -f /var/log/apache2/error.log
Anmelden am Datenbankserver: mysql -u [user] -p [pass]
Datenbanken anzeigen: show databases;
Datenbank anwaehlen: use [database];
Datenbank kopieren in shell: mysqldump -h [server] -u [user] -p[password] db1 | mysql -h [server] -u [user] -p[password] db2 
Tabellen anzeigen: show tables;
Tabelleneintraege anzeigen: select * from [table];
Tabelleneintraege aktualisieren: update room set humidity=20 where id=1;
Tabelleneintrag loeschen: delete from room where id=2;
Tabelleneintrag einpflegen: insert into jobaevent (name, value, type, done) values ('warnung', 'eine warnung', 'message', 0); 
Tabellenspalte hinzufuegen (add) / entfernen (drop column) / aendern (change): alter table room drop column lightone; alter table room change column lighttwo switch int(11);
Tabellenspaltenstruktur anzeigen: show columns from room; 
Eclipse-PHP-Debugging 
(XDebug-Installation: coderblog.de/how-to-install-use-configure-xdebug-ubuntu/;
XDebug-Eclipse-Verbindung, localhost und leeren Restpfad sollte auf index-Action verweisen) 

Entwicklernotizen
-----------------
* ehome: Infotainment besitzt HomeMatic Temperatur, Luftfeuchtigkeitsmesser, schlaue Steckdose (Ventilator), CO2-Sensor;
Hiwiraum besitzt Luftfeuchtigkeitsmesser, Temperatur, schlaue Steckdose (Licht1), alles ZWave;
Alle weiteren Raeume 
* sul: Keller besitzt HomeMatic Wassermelder und Steckdose;
** datenbank anlegen, create database ehcserver_sul, use ehcserver_sul: 
* nbg: Wohnzimmer besitzt Lampe;
** datenbank anlegen
-- -------------------------
-- Table jobaevent:
DROP TABLE IF EXISTS `jobaevent`;
CREATE TABLE IF NOT EXISTS `jobaevent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `type` varchar(11) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `done` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;
INSERT INTO `jobaevent` (`id`, `name`, `value`, `type`, `start`, `end`, `done`) VALUES
(1, 'Protokoll', 'Licht Eins im Raum Küche ausgeschaltet.', 'message', '2013-10-01 15:11:00', '2013-11-20 23:00:00', 1),
(2, 'Blutzuckermessung', '180', 'health', '2013-10-03 16:43:10', '2013-11-20 19:00:00', 1),
(3, 'Warnung', 'Heizung ist eingeschaltet und ein Fenster ist auf.', 'message', '2013-10-07 20:03:04', '2013-11-20 21:03:04', 0);
-- -------------------------
-- Table room:
DROP TABLE IF EXISTS `room`;
CREATE TABLE IF NOT EXISTS `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `humidity` int(11) NOT NULL,
  `temperature` int(11) NOT NULL,
  `lightone` int(11) NOT NULL,
  `lighttwo` int(11) NOT NULL,
  `window` int(11) NOT NULL,
  `door` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;
INSERT INTO `room` (`id`, `name`, `humidity`, `temperature`, `lightone`, `lighttwo`, `window`, `door`) VALUES
(1, 'Küche', 64, 22, 0, 100, 0, 0),
(2, 'Wohnzimmer', 56, 23, 0, 100, 0, 0),
(3, 'Bad', 95, 18, 0, 0, 0, 0);
-- ----------------------------
-- Table user:
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
INSERT INTO `user` (`user_id`, `username`, `email`, `display_name`, `password`, `state`) VALUES
(1, NULL, 'guest@jochen-bauer.net', NULL, '$2y$14$g3NucbIMcGNMgxvEYHJ0y.OsVH048OQCpVBMDcx4THbSZ9UZ8hiaq', NULL);
-- end database
** EhcServer config autoload global.php anpassen;
** Verifikation das Datenbankzugriff aus Webanwendung heraus klappt;
** Verifikation fhem-Server, etwa http://localhost:8083/fhem
** Einbau der UseCases in fhem;
** Konfiguration der Webanwendung im Ehome-Modul;
** Anpassung der Datenbank;
** Anpassung des Models: Room, RoomTable, RoomFilter und RoomForm;
** Anpassung IndexController editroom();
** Anpassung IndexController doAction();
** Anpassung der Views: ehome-index und ehome editroom;
** Check der Wertaenderung index-View;
** Check der Wertaenderung editroom-View;






