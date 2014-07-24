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
eine eigene Datenbank angelegt. test

Anleitung zum Anpassen an eine neue Umgebung
--------------------------------------------
Datenbank kopieren in neuen Namen;
Neuen Branch anlegen;
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
Tabelleneintraege aktualisieren: 
update room set humidity=20 where id=1;
Tabelleneintrag einpflegen: insert into jobaevent (name, value, type, done) values ('warnung', 'eine warnung', 'message', 0); 
Tabellenspalte hinzufuegen (add) / entfernen (drop column) / aendern (change): alter table room drop column lightone; alter table room change column lighttwo switch int(11);
Tabellenspaltenstruktur anzeigen: show columns from room; 
Eclipse-PHP-Debugging 
(XDebug-Installation: coderblog.de/how-to-install-use-configure-xdebug-ubuntu/;
XDebug-Eclipse-Verbindung, localhost und leeren Restpfad sollte auf index-Action verweisen) 

Entwicklernotizen
-----------------
Infotainment besitzt HomeMatic Temperatur, Luftfeuchtigkeitsmesser, schlaue Steckdose (Ventilator), CO2-Sensor;
Hiwiraum besitzt Luftfeuchtigkeitsmesser, Temperatur, schlaue Steckdose (Licht1), alles ZWave;
Alle weiteren Raeume 



