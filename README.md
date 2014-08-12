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

MVC
---
JobaEvent repraesentiert Mitteilungen. Das Attribut type regelt, welche Art die
Mitteilung ist. Im index-View werden die Nachrichten gemaess der Typen in Listen 
gebuendelt. Aktuell existieren Log-Nachrichten, Gesundheitsnachrichten und Warnungen.

Snippets
--------
Webserver-Log-Datei: tail -f /var/log/apache2/error.log

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

Umgebungen
----------
* Master: (ehome-Umgebung)
* Default
* Sul

