EhcServer
=========

Schnittstelle
-------------
Verbindungscheck : ehcserverip/ehomejson/index/user/pass

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
FHEM-API Switch anschalten: ...?cmd.Ventilator=set Ventilator off & room=Infotainment;
FHEM-API Humidity lesen: ...?cmd.listtemp={FW_devState%28%22TemperaturUndLuftfeuchtigkeit%22,%22%22%29}&XHR=1 Ergebnis: result body <div id="TemperaturUndLuftfeuchtigkeit" class="col2">T: 26.5 H: 36</div>
Netgear-Router (Netgear69): routerlogin.net (Problematisch bei zwei aktiven Netzwerken, besser IP siehe http://192.168.1.1/index.htm);
SSH Eclipse ssh -X xxxxx@xxx.xxx.xxx.xx /home/ehome/software/eclipse/eclipse-php/luna/eclipse/eclipse
SSH Firefox ssh -X xxxxx@xxx.xxx.xxx.xx firefox --no-remote -P username
SSH MySQL ssh xxxxx@xxx.xxx.xxx.xx; mysql -u xxxx -p;
Tabellen anzeigen: show tables; 
Tabelleneintraege anzeigen: select * from [table]; 
Tabelleneintraege aktualisieren: update room set humidity=20 where id=1; 
Tabelleneintrag einpflegen: insert into jobaevent (name, value, type, done) values ('warnung', 'eine warnung', 'message', 0); 
Tabellenspalte hinzufuegen (add) / entfernen (drop column) / aendern (change): alter table room drop column lightone; alter table room change column lighttwo switch int(11); 
Tabellenspaltenstruktur anzeigen: show columns from room; 
Eclipse-PHP-Debugging (XDebug-Installation: coderblog.de/how-to-install-use-configure-xdebug-ubuntu/; 
XDebug-Eclipse-Verbindung, localhost und leeren Restpfad sollte auf index-Action verweisen) 
ZWave-API-Setup: Raspberry mit Razberry-Aufsatz, WebUI im gleichen Netz (Default RaspberryPiWLan, raspberry) unter 10.11.12.1:8083;
Zwave-API-Doc: razberry.z-wave.me/docs/Z-WayAPI.pdf und razberry.z-wave.me/docs/zwayDev.pdf
Zwave-API-GetData as JSON: http://10.11.12.1:8083/ZWaveAPI/Data/0
Zwave-API-SetStrommessgeraet http://10.11.12.1:8083/ZWaveAPI/Run/devices%5B5%5D.instances%5B0%5D.Basic.Set%28255%29
Zwave-API-Set Strommessgeraet via JSON GetData-Analyse value bei Device 5 von 0 auf 255 erhoeht, diverse Timestamps neu gesetzt; Eclipse-Compare hilft bei Vergleich der Dateien; Device 5 key 37 level value

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
* Serverumgebung auf Updates pruefen;
* Aktuelle Github-Version des master-Branches holen;
* Aktuelle Zend Framework Version holen;
* Aktuelle Version der ZendFramework-Module holen;
* Aktuelle Version der PHP-Bibliotheken holen;
* Datenbank bauen bzw. Dump einspielen, Namenskonvention ehcserver_branchname;
* Automatisierte Tests starten (OrgaManager);
* Manuelle Tests ausfuehren;
* ...
* Versionsarchiv erstellen und ablegen;

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

Pairing-Notizen
---------------
* HomeMatic Bewegungsmelder
* HomeMatic Funksteckdose
* HomeMatic Luftfeuchtigkeit und Bewegung

Unit-Tests
----------
* siehe framework.zend.com/manual/2.0/en/user-guide/unit-testing.html
* PHPUnit-Installation giocc.com/installing-phpunit-on-ubuntu-11-04-natty-narwhal.html
