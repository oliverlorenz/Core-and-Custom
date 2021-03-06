Core-and-Custom
===============

Core-and-Custom ist ein Autoloader für PHP-Klassen. Er besitzt die Fähigkeit auf einem Kern (z.B. ein selbst entwickeltes Framework) aufzusetzen und diese durch eine lokale Ableitung zu modifizieren. Der Vorteil besteht darin, dass das der Kern updatefähig bleibt, auch wenn es Projektspezivische Änderungen gibt. Dafür werden zwei Bereiche definiert: Core und Custom. Die Custom-Klassen erben dabei von den Core-Klassen, können aber einzelne Funktionen überschreiben. Beim Programmieren kann dann auf eine "unspezifizierte" Klasse zugegriffen werden. Welche Klasse dahinter liegt entscheidet der Autoloader selbst. 

Ein Beispiel:

Man erstellt eine neue Klasse core_ol_Object. Der Klassenname muss dabei wie auch im Zend-Framework üblich der Verzeichnisstruktur folgen. In diesem Fall wird die Datei folgendermaßen abgelegt: /core/ol/Object.php. Diese Klasse mit der einfachen Ausgabe soll den Kern unserer Anwendung bilden.

Dateiinhalt:
```php
class core_ol_Object {
    public function printUsage() {
        print 'core usage description';
    }
}
```

Die index.php könnte nun so aussehen um die Funktion printUsage() auszuführen.

```php
require_once('core/init.php'); // init autoloader
$object = new ol_Object(); // Zugriff über unspezifizierte Klasse
$object->printUsage();

// Ausgabe: Core Description
```

Wie man sehen kann wird hier das Präfix "core_" weggelassen. In desem Moment greift der Autoloader und versucht erst eine Klasse mit dem Namen ol_Object, dann custom_ol_Object und dann core_ol_Object zu finden. 

Nun ist es möglich nach dem definierten Prinzip eine projektspezivische Ableitung zu definieren. Die Klasse wird in diesem Fall in folgendem Pfad abgelegt und benannt. /custom/ol/Object.php

Dateiinhalt:
```php
class custom_ol_Object extends core_ol_Object {
    public function printUsage() {
        print 'custom usage description';
    }
}
```

Die index.php bleibt unberühert. Trotzdem wird beim erneuten ausführen die Ausgabe "custom usage description" herausschreiben.

?>
