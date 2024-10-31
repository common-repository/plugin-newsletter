=== Plugin: Newsletter ===
Contributors: Adrian Preuss
Version: 1.5
Donate link: http://hovida-design.de
Tags: Newsletter, Mail
Requires at least: 3.2.1
Tested up to: 3.2.1
Stable tag: 1.5

Mit diesem Plugin können Newsletter versendet werden. Es kann ein Widget in der Sidebar hinzugefügt werden und eine extra Seite mit einem Newsletter-Formular erstellt werden.

== Description ==
Mit diesem Plugin können Newsletter versendet werden. Es kann ein Widget in der Sidebar hinzugefügt werden und eine extra Seite mit einem Newsletter-Formular erstellt werden.

**Funktionen:**

*   Das Plugin erstellt ein Widget
*   Das Plugin kann auf einer Seite eingebunden werden
*   Diverse Einstellmöglichkeiten
*   Multiinstanz-fähig
*   Templatebasiert
*   Template-Editor mit Syntax-Highlightning und Vorschau

**Aktuelle ToDo:**

* Einstellungen hinzufügen
* Texte über Administration editieren
            
== Installation ==

Downloade das Plugin oder installiere es direkt über der Wordpress-installation bzw. der Plugin-Store. Nachdem das Plugin aktiviert wurde, kann es direkt eingesetzt werden.

> Fragen oder Probleme?
> Kontaktiere mich: [a.preuss@hovida-design.de](mailto:a.preuss@hovida-design.de "a.preuss@hovida-design.de")
== Upgrade Notice ==

Sofern es Updates gibt, brauchen die Dateien nur ersetzt oder neu installiert werden.

== Frequently Asked Questions ==

= Wie kann ich das Plugin installieren? =

Gehe in die Wordpress-Administration und suche nach diesem Plugin. Dort kannst du das Plugin installieren.
Optional kannst du auf der Wordpress-Hauptseite das Plugin downloaden und manuell im Plugin-Ordner hochladen?

= Ich möchte weitere Plugins von dir! =

Kein Problem! Kontaktiere mich, ich erstelle gerne weitere Plugins auf Anfrage.
Kostenlose Plugins die ich erstelle, werden generell veröffentlicht!

= Ich habe ein Fehler gefunden! =

Bitte kontaktiere mich, ich werde dies dann umgehend korrigieren.

= Wie erstelle ich ein Template? =

Hierfür benötigst du Kenntnisse in HTML und CSS.
Über dem Dokument musst du noch einige Templatespezifische Angaben machen.

Um ein neues Template anzulegen, erstelle ein neues HTML-Dokument im **templates** Verzeichnis des Plugins (/wp-content/plugins/plugin-newsletter/templates/).
Die HTML-Datei kann wie ein normales HTML-Dokument aufgebaut werden. Am Anfang der Datei müssen aber Templatespezifische Angaben gemacht werden.

**Beispiel:**
`/*
	Name:			Standarttemplate
	Author:			Adrian Preuss
	Version:		1.0
	Type:			html
	Author Mail:	a.preuss@hovida-design.de
	Author WWW:		www.hovida-design.de
*/
<html>
	<head>
		<!-- ... -->
	</head>
	<body>
		<!-- Eigendliches HTML-Dokument -->
	</body>
</html>`


= Welche Tags kann ich im Template benutzen? =
`[TITLE] - Gibt den Titel aus (Blogtitel)
[SUBJECT] - Gibt den Betreff des Newsletters aus
[TEXT] - Gibt den Text des Newsletters aus`

Weitere Tags auf anfrage.

= Welche Tags kann ich im Newsletter nutzen? =
`[USER_EMAIL] - Die E-Mail Adresse des Nutzers
[USER_NAME] - Der angegebene Name des Nutzers`

Weitere Tags auf anfrage.

= Wie binde ich das Newsletter-Formular auf einer Seite bzw. einem Artikel ein? =
Gebe dazu einfach den Tag **[plugin_newsletter]** ein. Das Newsletter-Formular wird dann automatisch eingefügt.

== Change log ==
= 1.5 =
"[23.11.2011, Last Update: 09:55 Uhr] - Adrian Preuss"

* PHPMailer entfernt und auf Wordpress-Funktion umgestellt

= 1.4 =
"[07.11.2011, Last Update: 13:57 Uhr] - Adrian Preuss"

* Sicherheitsupdate: Update der Administration
* Rechtschreibfehler ausgebessert
* Administration: Einige änderungen der Nutzeroberfläche vorgenommen
* Double-Opt-In verfahren hinzugefügt

= 1.3 =
"[16.09.2011, Last Update: 07:02 Uhr] - Adrian Preuss"

* Korrektur der Template-Vorschau (Beispieltexte werden nun eingefügt)
* Abänderung des Standart-Templates
* Korrektur des Backends (Templates)
* Korrektur der Installationsroutine: Bitte das Plugin nach Update neu aktivieren!

= 1.2 =
"[16.09.2011, Last Update: 06:27 Uhr] - Adrian Preuss"

* Unbenutzte Wordpress-Actions entfernt (Verursachte auf einigen Servern einen PHP-Fehler) - Vielen Dank an Nicole (Eine Nutzerin des Plugins)
* Installationsroutine war noch alt: Hier fehlten 3 MySQL-Felder

= 1.1 =
"[13.09.2011, Last Update: 05:55 Uhr] - Adrian Preuss"

* Überarbeitung des Template-Editors (Syntax-Highlightning, Vorschau des Templates)
* Korrektur beim Ein- & Austragen von E-Mail Adressen
* E-Mail ist in der Datenbank nun als Unique gekennzeichnet
* Bugfix: Versenden von Newsletter - Ausgetragene E-Mail Adressen bekommen nun keine Newsletter mehr

= 1.0 =
"[11.09.2011, Last Update: 10:15 Uhr] - Adrian Preuss"

* Überarbeitung der Plugin-Seite
* Erstellung und Veröffentlichung des Plugins

== Screenshots ==
1. Newsletter versenden
2. Template Verwaltung
3. Newsletter Navigation (Einstellungen)
4. Widget-Formular (Komplett über CSS anpassbar)
5. Seiten-Formular (Komplett über CSS anpassbar)
6. Plugin-Übersicht
7. Template bearbeiten
8. Newsletter Nutzer (Mit angabe der IP-Adresse und des Hostnamens)
9. Vorschau des Templates (Realtime)
