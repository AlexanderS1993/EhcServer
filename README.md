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
Datenbank kopieren in neuen Namen;
Neuen Branch anlegen;
Netzwerkverbindungen zu Router und LAN/Internet pruefen;
Verbindung zu fhem verifizieren, etwa mit Funksteckdose;
Datenbank mit neuen Raeumen bestuecken;
Raumeigenschaften definieren bzw. ueberpruefen;
Floorplan anpassen oder ausblenden;
Unbesetzte Funktionalitaet kennzeichnen;

Hilfreiche Befehle
------------------
Anmelden am Datenbankserver: mysql -u [user] -p [pass]
Datenbanken anzeigen: show databases;
Datenbank anwaehlen: use [database];
Datenbank kopieren in shell: mysqldump -h [server] -u [user] -p[password] db1 | mysql -h [server] -u [user] -p[password] db2 
Tabellen anzeigen: show tables;
Tabelleneintraege anzeigen: select * from [table];

Entwicklernotizen
-----------------

Infotainment besitzt HomeMatic Temperatur, Luftfeuchtigkeitsmesser, schlaue Steckdose (Ventilator), CO2-Sensor;
Hiwiraum besitzt Luftfeuchtigkeitsmesser, Temperatur, schlaue Steckdose (Licht1), alles ZWave;
Alle weiteren Raeume 

