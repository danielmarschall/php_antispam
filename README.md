
# ViaThinkSoft AntiSpam-Schutz

Folgende Funktion soll Ihnen helfen, E-Mail-Adressen per JavaScript-Ausgabe sicher darzustellen, sodass der Benutzer sie auch anklicken und verwenden kann.

**Beschreibung**

Jedes Zeichen wird einzeln mit dem Zeichen-Code in einer einzelnen Zeile geschrieben. Selbst mailto, @ und .de werden mit verschlüsselt. Folglich kann man den Mechanismus als sehr sicher einstufen.

Der Benutzer muss JavaScript aktiviert haben, um die E-Mail-Adresse zu sehen. Die Verwendung von JavaScript ist aber Webseiten üblich und ist bei allen Browser standardmäßig aktiviert.

Vorteilhaft ist, dass kein Spam-Bot diesen Mechanismus kennt, da dieser nicht häufig angewandt wird. Ich bitte daher, diesen Spamschutz nicht in große Systeme ohne Erlaubnis einzubauen, da bei zu starkem Bekanntheitsgrades der JavaScript-Struktur, die Spam-Bots dem Mechanismus entgegenwirken könnten.

Auch ohne PHP ist der Spamschutz möglich, da der Code bereits im Voraus berechnet und die Ausgabe in einer HTML-Datei eingebaut werden kann.

**Benutzung**

-   1. Parameter: E-Mail-Adresse ohne Angabe von "mailto:"
-   2. Parameter: Angabe des Linktextes oder des Linkbildes
-   3. Parameter: Soll der 2. Parameter auch verschlüsselt werden? Nur ein Text darf verschlüsselt werden, ein Bild nicht! Der Link-Text muss verschlüsselt werden, wenn er die E-Mail-Adresse enthält, da sonst der Schutz verfällt.
-   4. Parameter (Optional): Die CSS-Klasse für den Link.

**Beispiele**

    <?php  
      
    echo secure_email('test@example.com', 'Schreib mir!', 0);  
    echo secure_email('test@example.com', 'test@example.com', 1);  
    echo secure_email('test@example.com', '<img src="...">', 0);  
      
    ?>
