# mod_cluborganisation_birthday

**Joomla 5/6 Site-Modul für die ClubOrganisation-Komponente**  
**Version:** 1.0.0  
**Lizenz:** GPLv3

---

## Übersicht

Zeigt Geburtstage von aktiven Vereinsmitgliedern im Frontend an:

- **Heutige Geburtstage** – wer hat heute Geburtstag?
- **Bevorstehende Geburtstage** – wer hat in den nächsten X Tagen Geburtstag?

Nur Personen mit einer **aktiven Mitgliedschaft** werden angezeigt.  
Verstorbene Personen (`deceased IS NOT NULL`) werden ausgeblendet.

---

## Voraussetzungen

- Joomla 5 oder 6
- ClubOrganisation-Komponente (com_cluborganisation) installiert
- PHP 8.1+
- Personen-Tabelle `#__cluborganisation_persons` mit Feld `birthday` (DATE)

---

## Installation

```bash
# 1. Quellcode-Verzeichnis aufrufen
cd /pfad/zum/quellcode

# 2. Build-Script ausführen
chmod +x auto_install.sh
./auto_install.sh

# 3. ZIP installieren
# Backend → System → Install → Extensions
# → Upload: /opt/mod_cluborganisation_birthday_v1.0.0.zip
```

---

## Konfiguration

### Basic-Tab

| Parameter | Standard | Beschreibung |
|-----------|----------|--------------|
| **Tage in die Zukunft** | 30 | Wie viele Tage bevorstehende Geburtstage angezeigt werden |

### Advanced-Tab

Standard-Joomla Modul-Optionen (Layout, CSS-Suffix, Caching).

---

## Projektstruktur

```
mod_cluborganisation_birthday.xml     ← Joomla-Manifest
mod_cluborganisation_birthday.php     ← Einstiegsdatei
auto_install.sh                       ← Build & Package Script
services/
└── provider.php                      ← Service Provider (DI)
src/
├── Dispatcher/
│   └── Dispatcher.php               ← Daten aufbereiten & Template-Vars
└── Helper/
    └── BirthdayHelper.php           ← Datenbankabfragen
tmpl/
└── default/
    └── default.php                  ← Frontend-Template
language/
├── de-DE/
│   ├── de-DE.mod_cluborganisation_birthday.ini
│   └── de-DE.mod_cluborganisation_birthday.sys.ini
└── en-GB/
    ├── en-GB.mod_cluborganisation_birthday.ini
    └── en-GB.mod_cluborganisation_birthday.sys.ini
```

---

## Build-Script Konventionen

Alle Source-Dateien liegen **flach im Quellordner** mit folgenden Namen:

| Datei im Quellordner | Ziel im Modul |
|----------------------|---------------|
| `mod_cluborganisation_birthday.xml` | `mod_cluborganisation_birthday.xml` |
| `mod_cluborganisation_birthday.php` | `mod_cluborganisation_birthday.php` |
| `services_provider.php` | `services/provider.php` |
| `Dispatcher.php` | `src/Dispatcher/Dispatcher.php` |
| `BirthdayHelper.php` | `src/Helper/BirthdayHelper.php` |
| `tmpl_default.php` | `tmpl/default/default.php` |
| `de-DE.mod_cluborganisation_birthday.ini` | `language/de-DE/...` |
| `de-DE.mod_cluborganisation_birthday.sys.ini` | `language/de-DE/...` |
| `en-GB.mod_cluborganisation_birthday.ini` | `language/en-GB/...` |
| `en-GB.mod_cluborganisation_birthday.sys.ini` | `language/en-GB/...` |

---

## Autor

**Christian Schulz**  
technik@meinetechnikwelt.rocks
