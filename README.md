# Notenverwaltung
[PHP/JS/MySQL] Notenverwaltung als Webanwendung


### Systemvoraussetzungen
- Eingerichteter Webserver
- PHP 7.x oder neuer
- MySQL Server

### Installation
1. Repository klonen
´´´ git clone https://github.com/Paderborner-Dommusik/Notenverwaltung.git /folder/to/webserver```

2. MySQL Datenbank einrichten
- Im Repo befindet sich eine 'database.sql' -> in eine neue Datenbank importieren und einen User mit lese/schreib-rechten erstellen
- Verbindungsdaten unter classes/db_data.php einfügen
- Demo Verbindungsdaten sind in der Datenbank unter 'users' abgelegt.

### Wichtige Information
- Das System besteht zum großen Teil aus zusammengebasteltem Spaghetti Code und ist seit seiner Erfindung historisch gewachsen
- Wir übernehmen keine Haftung für eventuelle Probleme mit dem System, es ist jedoch über 2 Jahre problemlos gelaufen
- es ist eine (relativ stumpfe) Rechteverwaltung mit eingebaut, die am besten einfach mit den drei Demo-Accounts ausprobiert werden sollte.
- Für weitere Fragen zur Struktur einfach eine Nachricht an mail@r3ne.de - es gibt zwar keinen Support aber klar gestellte Fragen kann ich nebenbei beantworten.
- Bei Auftretenden Fehlern bitte Issues anlegen. Ich werde versuchen die zu fixen, falls sie reproduzierbar sind. (Siehe dazu [1337])

### Mitarbeiten? Gerne. Ich teste Pull Requests einmal auf die bekannten Funktionen und schaue einmal über den Code. Werden dann gemerged. Bitte auch hier niederschreiben was genau gemacht wurde.

[1337] http://blog.cimcloud.com/blog/how-to-write-a-great-support-ticket