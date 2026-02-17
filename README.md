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
Verstorbene Personen werden ausgeblendet.

---

## Voraussetzungen

- Joomla 5 oder 6
- ClubOrganisation-Komponente (com_cluborganisation) installiert
- PHP 8.1+

---

## Konfiguration

### Basic-Tab

| Parameter | Standard | Beschreibung |
|-----------|----------|--------------|
| **Tage in die Zukunft** | 30 | Wie viele Tage bevorstehende Geburtstage angezeigt werden (1–365) |
| **Überschrift „Heute"** | *(leer)* | Eigene Überschrift für den Heute-Abschnitt; leer = Standardtext |
| **Überschrift „Bevorstehend"** | *(leer)* | Eigene Überschrift für den Bevorstehend-Abschnitt; leer = Standardtext |
| **Zeitraum in Überschrift anzeigen** | Ja | Hängt „(nächste X Tage)" an die Überschrift des Bevorstehend-Abschnitts |
| **„Heute" ausblenden wenn leer** | Nein | Blendet den Heute-Abschnitt komplett aus, wenn heute niemand Geburtstag hat |
| **Bevorstehende Geburtstage anzeigen** | Ja | Bevorstehenden Abschnitt anzeigen oder komplett deaktivieren |
| **„Bevorstehend" ausblenden wenn leer** | Nein | Blendet den Bevorstehend-Abschnitt aus, wenn im Zeitraum niemand Geburtstag hat |
| **Alter anzeigen** | Ja | Zeigt das neue Alter in Klammern an, z. B. „(wird 42)" |

### Advanced-Tab

Standard-Joomla Modul-Optionen (Layout, CSS-Suffix, Caching).

---

## Autor

**Christian Schulz**
