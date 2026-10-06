# MI Kirby Backend

Headless CMS-Backend für den Studiengang Medieninformatik an der TH Köln, gebaut mit [Kirby CMS](https://getkirby.com). Die Inhalte werden ausschließlich als JSON-API bereitgestellt — kein eigenes Frontend.

## Voraussetzungen

- PHP 8.1 – 8.4
- Composer

## Installation

```bash
composer install
```

## Lokale Entwicklung

```bash
composer dev
```

Der lokale Server läuft anschließend unter `http://localhost:8000`.

Beim ersten Start muss einmalig ein Admin-Account angelegt werden:
`http://localhost:8000/panel`

## API-Endpunkte

Alle Endpunkte sind öffentlich (kein Auth erforderlich) und liefern `application/json`.

| Endpunkt | Beschreibung |
|---|---|
| `GET /abschlussarbeiten` | Alle veröffentlichten Abschlussarbeiten, sortiert nach Datum (absteigend) |
| `GET /abschlussarbeiten/{slug}` | Einzelne Abschlussarbeit; 404 wenn unveröffentlicht |

## Inhaltspflege

Inhalte werden über das Kirby Panel gepflegt: `/panel`

Login via **TH-Köln-Keycloak** (SSO) — nur Adressen mit `@th-koeln.de` sind zugelassen. Lokale Passwort-Logins sind deaktiviert.

## Dateistruktur

```
mi-kirby-backend/
├── composer.json                        Abhängigkeiten & Skripte
├── index.php                            Kirby-Bootstrap
├── .htaccess                            Routing & Zugriffsschutz
├── content/
│   └── abschlussarbeiten/
│       └── {slug}/
│           └── abschlussarbeit.txt      Inhaltsdateien (Kirby-Format)
└── site/
    ├── blueprints/pages/
    │   ├── abschlussarbeiten.yml        Panel-Listenansicht
    │   └── abschlussarbeit.yml          Panel-Eingabemaske (4 Tabs)
    ├── config/
    │   ├── config.php                   Kirby-Konfiguration & OAuth-Setup
    │   ├── config.local.php             Lokale Credentials (nicht committen)
    │   ├── config.local.php.example     Vorlage für lokale Credentials
    │   └── helpers.php                  JSON-Serialisierung
    └── templates/
        ├── abschlussarbeiten.php        JSON-Array-Ausgabe
        └── abschlussarbeit.php          JSON-Einzeleintrag
```

Neue Abschlussarbeiten erhalten im Panel automatisch eine URL aus dem Titel. Veröffentlichung erfolgt über den Status-Schalter im Panel (Unveröffentlicht / Veröffentlicht).

## Was nicht im Repository liegt

| Pfad | Grund |
|---|---|
| `content/` | Inhalte werden im Produktiv-Panel gepflegt – der Server ist die maßgebliche Quelle. |
| `site/accounts/` | Benutzerkonten sind pro Umgebung verschieden und enthalten Passwort-Hashes. |
| `site/config/config.local.php` | Credentials und umgebungsspezifische Einstellungen. |

Aktuelle Inhalte vom Server holen (überschreibt lokale Inhalte):

```bash
rsync -av --delete {user}@{server}:{pfad}/content/ ./content/
```

**Achtung beim Deployment per `git pull`:** `content/` und `site/accounts/` waren früher eingecheckt. Ein Server, der noch einen alten Stand hat, verliert diese Dateien beim nächsten Pull. Vorher sichern.

## Keycloak SSO einrichten

1. `site/config/config.local.php` aus der Vorlage anlegen:

   ```bash
   cp site/config/config.local.php.example site/config/config.local.php
   ```

2. Einen Keycloak-Client beim TH-Köln-Admin anlegen (Typ: `confidential`). Die Redirect-URI lautet:

   ```
   https://{deine-domain}/oauth/login/thkoeln
   ```

3. Die Felder in `config.local.php` befüllen:

   | Feld | Beschreibung |
   |---|---|
   | `clientId` | Client-ID aus dem Keycloak-Client |
   | `clientSecret` | Client-Secret aus dem Keycloak-Client |
   | `keycloakBase` | Basis-URL des Keycloak-Servers, z. B. `https://sso.th-koeln.de` |
   | `realm` | Name des Keycloak-Realms |
   | `redirectUri` | Vollständige Redirect-URI inkl. Domain |

## Datenmodell: Abschlussarbeit

### Pflichtfelder

| Feld | Typ | Beschreibung |
|---|---|---|
| `title` | string | Vollständiger Titel |
| `type` | enum | `Praxisprojekt`, `Bachelorarbeit`, `Masterarbeit` |
| `date` | date | Abgabedatum (YYYY-MM-DD) |
| `status` | enum | `in-preparation`, `in-progress`, `in-evaluation`, `finished` |
| `visibility` | enum | `published`, `unpublished` |
| `firstname` | string | Vorname |
| `lastname` | string | Nachname |
| `first_supervisor` | string | Erstprüfung |
| `second_supervisor` | string | Zweitprüfung |

### Optionale Felder

| Feld | Typ | Beschreibung |
|---|---|---|
| `abstract` | string | Zusammenfassung (Markdown) |
| `keywords` | string[] | Schlagwörter |
| `awards` | string[] | Auszeichnungen, z. B. `RTL-Preisgewinner 2025` |
| `thesis_url` | string | PDF der Arbeit (Upload oder externe URL; Upload hat Vorrang) |
| `teaser_image_url` | string | Teaserbild (Upload oder externe URL; Upload hat Vorrang) – im Panel Pflicht |
| `teaser_image_copyright` | string | Copyright-Angabe zum Teaserbild – im Panel Pflicht |
| `avatar_url` | string | Profilfoto (Pfad oder URL) |
| `repository_url` | uri | Repository-URL |
| `project_url` | uri | Projekt- oder Demo-URL |
| `final_presentation_youtube_id` | string | YouTube-Video-ID (11-stellig) |
| `personal_website_url` | uri | Persönliche Website |
| `personal_social_media_urls` | uri[] | Social-Media-Profile |
| `cooperation_partner` | string | Name des Kooperationspartners |
| `cooperation_partner_url` | uri | Website des Kooperationspartners |

## Lizenz

Internes Werkzeug der TH Köln – Medieninformatik. Keine öffentliche Lizenz.
