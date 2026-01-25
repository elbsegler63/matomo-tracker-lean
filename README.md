# Matomo Tracker (Lean)

Ein extrem leichtgewichtiges WordPress-Plugin zur Einbindung von **Matomo Web Analytics**
ohne Cookies, ohne Admin-Tracking und ohne Performance-Einbußen.

Dieses Plugin richtet sich an Entwickler:innen und technisch versierte Anwender:innen,
die Matomo **kontrolliert, datenschutzfreundlich und PageSpeed-neutral** einsetzen möchten.

---

## Eigenschaften

- ✅ **Cookieless Tracking** (`disableCookies`)
- ✅ **Kein Tracking für eingeloggte Nutzer** (Admin, Editor, Autor usw.)
- ✅ **Keine Admin-Oberfläche**
- ✅ **Keine Datenbank-Optionen**
- ✅ **Asynchrones Laden im Footer**
- ✅ **PageSpeed / Lighthouse-freundlich** (TBT = 0)
- ✅ **Self-hosted Matomo**
- ❌ kein Tag Manager
- ❌ kein Tracking-Ballast
- ❌ kein „Schnick-Schnack“

---

## Voraussetzungen

- WordPress 5.8+
- Eigene Matomo-Installation (self-hosted)
- Zugriff auf die `wp-config.php`

---

## Installation

1. Ordner anlegen:
wp-content/plugins/matomo-tracker-lean/

2. Plugin-Datei anlegen:
matomo-tracker-lean.php


3. Plugin-Code einfügen (siehe Repository).

4. Plugin im WordPress-Backend aktivieren.

---

## Konfiguration (wp-config.php)

Die Konfiguration erfolgt **ausschließlich** über Konstanten in der `wp-config.php`.

Füge **oberhalb** von  
`/* That's all, stop editing! Happy publishing. */`  
folgende Zeilen ein:

```php
define('MATOMO_URL', 'https://deine.matomo.url.de');
define('MATOMO_SITE_ID', 4);
```

3. Plugin-Code einfügen (siehe Repository).

4. Plugin im WordPress-Backend aktivieren.

---

***Hinweise***
Die URL muss https:// enthalten

Kein trailing Slash

MATOMO_SITE_ID ist die von Matomo vergebene Website-ID

***Funktionslogik***

Tracking wird nur ausgeführt, wenn alle Bedingungen erfüllt sind:

Besucher ist nicht eingeloggt

Frontend-Request (kein Admin, Ajax, JSON)

Seite ist keine Vorschau

MATOMO_URL ist gesetzt

MATOMO_SITE_ID > 0

In allen anderen Fällen wird kein Tracking-Code geladen.

**Datenschutz**

Es werden keine Cookies gesetzt

Kein LocalStorage

Kein SessionStorage

Keine personenbezogenen IDs

Geeignet für DSGVO-sparsame Setups
(rechtliche Bewertung liegt beim Seitenbetreiber)


**Überprüfung**

***Tracking aktiv?***

Inkognito-Fenster öffnen

Website aufrufen

Matomo → „Besucher in Echtzeit“

Besucher erscheint

***Keine Cookies?***

DevTools → Application / Storage → Cookies

Keine _pk_* Cookies vorhanden

***Admin wird nicht getrackt?***

Als Admin einloggen

Seite aufrufen

Kein neuer Besucher in Matomo

***Erweiterbarkeit***

Für spätere Consent-Logik existiert ein Filter:

add_filter('matomo_lean_should_track', function ($allowed) {
    return $allowed; // hier z. B. Consent prüfen
});


Das Plugin selbst bleibt dabei unverändert.

**Versionierung**

1.0.0

Erste stabile Version

Cookieless Tracking

Kein Admin-Tracking

Footer-Einbindung via WP-Script-API

Safe-Fallback (Tracking aus, wenn nicht konfiguriert)
