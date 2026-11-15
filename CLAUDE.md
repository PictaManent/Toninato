# CLAUDE.md — Progetto base R.M.Web (tema WordPress su misura)

Questo file spiega **come è costruito il progetto e perché**, per i programmatori di R.M.Web e per
Claude Code (che lo legge in automatico). Il progetto Toninato è la **base da cui partire per i nuovi
siti dell'agenzia**: struttura, strumenti e regole qui sotto valgono anche per i progetti successivi.

- Guida operativa passo passo (installazione, comandi, backup, problemi frequenti): [ISTRUZIONI.md](ISTRUZIONI.md)
- Panoramica breve: [README.md](README.md)

> Nessuna password in questo file né nella repo: le credenziali dello stage stanno in FileZilla e nel
> `wp-config.php` sul server (vedi [Stage](#6-stage-demo-su-aruba)). Le credenziali in `ISTRUZIONI.md` e
> `.env.example` sono solo quelle dell'ambiente locale Docker.

---

## 1. Cosa c'è nella repo

| Percorso | Cosa |
|----------|------|
| `theme/` | Il tema WordPress classico, scritto a mano. È l'unica parte che va online (via `deploy.py`). |
| `theme/acf-json/` | Gruppi di campi di Secure Custom Fields in JSON (Local JSON), scritti da SCF. |
| `tools/seed-content.php` | Crea pagine, immagini, menu e compila i campi SCF (WP-CLI `eval-file`). |
| `deploy.py`, `deploy.cmd` | Pubblicazione del tema sullo stage via FTP, con controlli di sicurezza. |
| `docker-compose.yml`, `.env.example` | Ambiente locale: WordPress + MariaDB + phpMyAdmin + WP-CLI. |
| `ISTRUZIONI.md` | Manuale per chi lavora sul progetto. |

WordPress (core, plugin, upload) e il database **non** stanno nella repo: vivono nei volumi Docker in
locale e sul server nello stage. Ciò che serve a ricrearli è nella repo: `seed-content.php` (contenuti)
e `acf-json/` (struttura dei campi).

Niente build step: niente npm, Sass o bundler. CSS e JS si scrivono direttamente in `theme/assets/`.

---

## 2. Ambiente locale (Docker)

```bash
cp .env.example .env
docker compose up -d
docker compose run --rm wpcli wp core install --url=http://localhost:8080 --title=<Nome> \
  --admin_user=rmweb --admin_password=<password> --admin_email=sviluppo@socialone.it --skip-email
docker compose run --rm wpcli wp language core install it_IT --activate
docker compose run --rm wpcli wp theme activate <slug-tema>
docker compose run --rm wpcli wp rewrite structure '/%postname%/'
docker compose run --rm wpcli wp plugin install secure-custom-fields --activate
docker compose run --rm wpcli wp eval-file /tools/seed-content.php
```

- Sito `http://localhost:8080`, phpMyAdmin `http://localhost:8081` (porte nel `.env`).
- `theme/` è montata nel container: le modifiche si vedono ricaricando, senza riavvii.
- `tools/` è montata in sola lettura in `/tools` nel container `wpcli`.
- **Git Bash su Windows**: anteporre `MSYS_NO_PATHCONV=1` ai comandi con percorsi tipo `/tools/...`,
  altrimenti Git Bash li trasforma in percorsi Windows (`C:/Program Files/Git/tools/...`).
- Il servizio `wpcli` gira con l'utente `33:33` (www-data), così i file che crea sono scrivibili da WordPress.

---

## 3. Architettura del tema

Tema **classico** (PHP), non a blocchi e senza page builder: massimo controllo su markup, prestazioni
e accessibilità.

```
theme/
├── style.css              intestazione del tema (Version: X.Y.Z), nessuno stile qui
├── functions.php          supporti, menu, CSS/JS (versionati con Version + filemtime), favicon, preload font
├── inc/data.php           DATI DELL'AZIENDA: recapiti, servizi di riserva, menu di riserva
├── inc/template-tags.php  funzioni di supporto: URL pagine, immagini del tema, icone SVG, logo
├── inc/fields.php         campi SCF: percorsi Local JSON, funzioni di lettura, valori predefiniti
├── header.php / footer.php
├── page.php / index.php   template predefiniti
├── page-templates/        template scelti dalla pagina (Attributi pagina → Template)
├── template-parts/        blocchi riusati (intestazione pagina, griglia servizi, CTA, contatti, recensioni)
├── acf-json/              gruppi di campi SCF (JSON)
└── assets/
    ├── css/main.css       tutti gli stili; colori e font come variabili in :root in cima
    ├── js/main.js         JS vanilla: menu mobile, animazioni di comparsa
    ├── fonts/             font self-hosted in woff2 (niente Google Fonts: GDPR)
    ├── images/            immagini di riserva (fallback) e sorgente per il seed
    └── favicon/           favicon .ico/.svg/.png
```

Principi:

- **Una sola fonte per i dati aziendali**: `inc/data.php` (`toninato_info()`): telefono, indirizzo,
  email, P.IVA, shortcode recensioni. Header, footer, CTA e pagina Contatti leggono da lì.
- **Pagine vere in WordPress** con il loro template, non pagine "finte" nel codice: così hanno URL,
  SEO, immagine in evidenza e riassunto modificabili.
- **Immagine in evidenza** = sfondo dell'intestazione della pagina; **riassunto** = frase sotto il titolo.
- **Schede dei servizi** = pagine figlie di `/servizi/` (titolo, riassunto, immagine in evidenza,
  ordine da Attributi pagina). `data.php` resta come riserva se non esistono.
- **Menu reali** (Aspetto → Menu) nelle posizioni `primary` e `footer`, con un menu di riserva nel
  codice se non sono assegnati.
- **Icone** SVG in linea da `toninato_icon( $name )`, niente icon font.
- **Escape sempre** (`esc_html`, `esc_attr`, `esc_url`); `phpcs:ignore` solo dove l'HTML è generato da
  funzioni sicure (icone, plugin).
- **CSS**: variabili in `:root` (`--c-navy`, `--c-accent`, `--f-display`...), mobile first sui
  breakpoint `max-width` già presenti (1439, 1199/1180, 980, 640 px), nessuno scroll orizzontale a 390 px.
- **Cache del browser**: CSS/JS hanno `?ver=Version.filemtime`, favicon `?v=Version`.

### Favicon

Generata dal monogramma del logo con il font del sito: Python con `fonttools` + `brotli` (per leggere il
woff2) e `Pillow`, in un **venv temporaneo** (non nel Python di sistema). Il `.svg` ha le lettere
convertite in tracciati (niente font esterni); nelle misure piccole del `.ico` le lettere sono più grandi
per restare leggibili. `functions.php` stampa i `<link rel="icon">` solo se in WordPress non è impostata
un'"Icona del sito", e reindirizza `/favicon.ico` (che WordPress altrimenti manda al proprio logo).

---

## 4. Contenuti modificabili: Secure Custom Fields (SCF)

### Perché SCF

[Secure Custom Fields](https://wordpress.org/plugins/secure-custom-fields/) è il fork di ACF mantenuto da
WordPress.org: **gratuito** e con le funzioni che in ACF sono solo PRO (repeater, galleria, contenuto
flessibile, clone, pagine opzioni). Stesse API di ACF (`get_field()`, `update_field()`...), quindi il
tema funziona anche con ACF PRO. **Non attivare SCF e ACF insieme.**

### Regola dell'agenzia: fallback + campi compilati

Ogni pagina con campi ACF/SCF va creata **in entrambi i modi**:

1. **Valori di riserva nel codice**: ogni campo ha un predefinito (`toninato_home_defaults()`) e il
   template legge con `toninato_field( 'nome', $default )`. La pagina è completa anche con il plugin
   spento o un campo vuoto.
2. **Campi già compilati con lo stesso materiale** (testi, link, ripetitori, immagini **caricate nella
   Libreria media**, non i file del tema) tramite `seed-content.php`, sia in locale sia sullo stage.
   Il cliente trova tutto pronto da modificare, senza ribattere niente.

Verifica obbligatoria: l'output della pagina dev'essere **identico** con i campi compilati e con il
plugin spento (confronto del testo e dei link della pagina, vedi sezione 8).

### Local JSON: dove stanno i gruppi di campi

- I gruppi si creano e modificano da **SCF → Gruppi di campi**, **solo in locale**.
- `inc/fields.php` imposta `acf/settings/save_json` e `load_json` su `theme/acf-json/`: a ogni salvataggio
  SCF scrive `group_<chiave>.json` nel tema → si committa → va sullo stage con il deploy.
- Sullo stage (o su un PC nuovo) i campi funzionano subito leggendo il JSON; per poterli modificare lì,
  il gruppo va **sincronizzato** nel database (SCF → Gruppi di campi → "Sincronizzazione disponibile",
  oppure `acf_import_field_group()` da script, vedi sotto). Non modificare i gruppi sullo stage: il
  deploy successivo sovrascrive il JSON.
- Chiavi stabili e leggibili: gruppo `group_tn_<pagina>`, campi `field_tn_<pagina>_<nome>`. Cambiarle
  significa perdere il collegamento con i valori salvati.

Errori già fatti, da non ripetere:

- I gruppi registrati **in PHP** (`acf_add_local_field_group`) funzionano ma **non compaiono** in
  SCF → Gruppi di campi: per questo si usa il JSON.
- Da script, **non salvare** un gruppo letto con `acf_get_field_group( 'group_...' )` quando la versione
  JSON non è ancora nel database: ha `ID = 0` e `acf_update_field_group()` crea un **doppione**. Per
  importare: controllare prima che non esista un post `acf-field-group` con quel `post_name`, poi
  `acf_import_field_group()` con `ID = 0`; per salvare un gruppo esistente, leggerlo per ID numerico.

### Come aggiungere i campi a una nuova pagina (ricetta)

1. **Predefiniti**: in `inc/fields.php` una funzione `<prefisso>_<pagina>_defaults()` con i contenuti
   attuali (immagini come nomi di file in `assets/images/`).
2. **Template**: leggere ogni valore con `toninato_field()`; immagini con `toninato_image_url()` /
   `toninato_image_tag()` (ID allegato o file del tema di riserva); link con `toninato_link()` +
   `toninato_link_attrs()` (accetta anche `tel:` e `mailto:`).
3. **Gruppo di campi** in SCF (locale), regola di posizione sul template della pagina, schede (tab) per
   sezione, istruzioni brevi in italiano per il cliente; campi immagine con *Formato di ritorno = ID*,
   link con *Array*. Salvare → nasce il JSON in `acf-json/`.
4. **Seed**: in `tools/seed-content.php` compilare i campi con `update_field( '<chiave campo>', ... )`
   usando le **chiavi** (non i nomi), immagini via `tn_media()` (importa una volta sola e restituisce
   l'ID, riconosciuto dal meta `_toninato_source`). Compilare solo se vuoti, o sempre con `update`.
5. **Verifiche**: output identico prima/dopo e con SCF spento; schermata di modifica senza errori;
   prova di modifica di un campo che si riflette sul sito (poi ripristinare).
6. **Stage**: deploy del tema, sincronizzazione del gruppo e seed tramite script temporaneo (sezione 6).

Già fatto: **Home** (`group_tn_home`, schede Testata, Presentazione, Aree di attività, Valori,
Diritto civile e penale, Galleria, Recensioni). Il testo di presentazione resta il contenuto della pagina.

---

## 5. Plugin usati

| Plugin | Perché | Note |
|--------|--------|------|
| Secure Custom Fields | campi modificabili dal pannello | gruppi in `theme/acf-json/` |
| Widgets for Google Reviews (Trustindex) | recensioni Google in home | la scheda Google si collega a mano dal pannello; senza collegamento la sezione non compare ai visitatori (`template-parts/reviews.php` controlla che lo shortcode produca qualcosa) |

Il tema non dipende dai plugin per funzionare: senza, mostra i contenuti predefiniti.

---

## 6. Stage (demo su Aruba)

| Cosa | Valore |
|------|--------|
| Demo | `https://www.demosocialone.it/<cartella>/` (questo progetto: `/toninato4/`) |
| FTP | sito **"Demosocialone.it"** nel Site Manager di FileZilla (`ftp.demosocialone.it`, FTP semplice) |
| Cartella | `/www.demosocialone.it/<cartella>` |
| Database | MySQL 8 `Sql1892487_5`, **un prefisso tabelle per demo** (qui `toninato4_wp_`) |
| PHP | 8.4 |
| Credenziali | FTP in FileZilla; database nel `wp-config.php` della demo. **Mai** in repo o in chat. |

### ⚠️ Sicurezza

- Lo spazio FTP contiene **tutti i siti dei clienti** dell'agenzia: si scrive **solo** nella cartella
  della demo del progetto. Il database ospita **altre demo**: si toccano **solo** le tabelle col
  prefisso della demo. Per una demo nuova, verificare che cartella e prefisso siano liberi.
- Si pubblica e si modifica lo stage **solo con il permesso esplicito** del responsabile del progetto.

### Pubblicare il tema: `deploy.py`

```bash
python deploy.py --dry-run   # mostra cosa caricherebbe/eliminerebbe, non scrive niente
python deploy.py             # pubblica (chiede conferma);  -y senza conferma;  --full ricarica tutto
```

- Carica **solo** `theme/` nella cartella del tema e rifiuta percorsi fuori dal "recinto"
  (`allowed_base` in `TARGETS`).
- Carica solo i file cambiati confrontando gli SHA-1 con `.deploy-manifest.json` sul server; elimina
  dal server i file tolti in locale **solo se presenti nel manifest**.
- Segnala con `?` i file presenti sul server ma non in locale (caricati a mano o prima del manifest):
  non li cancella. Su questo progetto un vecchio `front-page.php` rimasto sul server scavalcava il
  template Home.
- Avvisa se ci sono modifiche al tema non committate.
- Credenziali: dal sito FileZilla (`%APPDATA%\FileZilla\sitemanager.xml`) o dal `.env`
  (`DEPLOY_STAGE_FTP_*`). Solo libreria standard di Python.

### Modificare il database dello stage: script temporaneo

Il MySQL di Aruba **non accetta connessioni dall'esterno** e sullo stage non c'è WP-CLI. Per creare
pagine, installare plugin, sincronizzare campi, eliminare utenti si usa uno **script PHP temporaneo**:

1. nome casuale (`tn-run-<hex>.php`) e token casuale; caricato via FTP **nella cartella della demo**;
2. lo script risponde 404 senza il token giusto (`hash_equals`), poi `require wp-load.php`, imposta
   l'utente `rmweb` e usa le API di WordPress;
3. si richiama con `curl` e **si cancella subito dopo**, verificando che l'indirizzo risponda 404.

```php
<?php
if ( ! isset( $_GET['t'] ) || ! hash_equals( '<token>', (string) $_GET['t'] ) ) { http_response_code( 404 ); exit; }
header( 'Content-Type: text/plain; charset=utf-8' );
require __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/admin.php';
wp_set_current_user( get_user_by( 'login', 'rmweb' )->ID );
// ... operazioni ...
```

- Per lanciare `seed-content.php` sullo stage: caricarlo accanto con nome casuale e includerlo dopo aver
  definito una classe `WP_CLI` minima (`log`, `warning`, `error`, `success`) e `define( 'WP_CLI', true )`.
- Plugin: `Plugin_Upgrader` con `Automatic_Upgrader_Skin` (il filesystem di Aruba è `direct`), poi
  `activate_plugin()`. Un plugin appena attivato si carica **dalla richiesta successiva**: installazione
  e uso vanno in due chiamate.
- Le funzioni FTP di `deploy.py` (`deploy.credentials()`, `deploy.TARGETS`) si possono importare da un
  piccolo script Python per caricare/cancellare questi file.

### Cose da sapere su Aruba

- **Cache del proxy**: per verificare una modifica aggiungere `?nc=<casuale>` all'URL; l'header
  `X-Aruba-Cache` dice HIT/MISS/BYPASS.
- **Import SQL**: mettere in testa `SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION';` (le date
  `0000-00-00` di WordPress altrimenti danno errore su MySQL 8).
- Le richieste dirette ai file `.ico` non passano da WordPress: la favicon si dichiara con i `<link>`.
- Se lo stage non rispecchia il locale pur avendo gli stessi file, cercare file presenti solo sul server
  (`deploy.py --dry-run` li segnala).

---

## 7. Regole dell'agenzia

- **Utente WordPress**: sempre `rmweb` (email `sviluppo@socialone.it`), in locale e sullo stage. Autore
  di pagine, media e menu: `rmweb` (`post_author`, `wp_set_current_user`, WP-CLI `--user=rmweb`).
  L'utente `admin` dell'installazione si **elimina** passando tutto a `rmweb`
  (`wp user delete admin --reassign=<id>`). Email del sito (`admin_email`): `sviluppo@socialone.it`.
- **Autore del tema**: `Author: R.M.Web` in `style.css`.
- **Versioni** `X.Y.Z` in `style.css`: Y per nuove funzionalità, Z per correzioni e modifiche solo
  grafiche/CSS.
- **Commit**: Conventional Commits in inglese, una riga. Nessuna firma o attribuzione di strumenti AI.
- **Dopo le prove**: cancellare tutto ciò che è servito solo a testare (`.playwright-mcp/`, screenshot,
  script temporanei in locale **e sul server**, venv) e controllare `git status`.
- **Lingua**: interfaccia, testi, commenti e documentazione in italiano.

---

## 8. Come verifichiamo le modifiche

- `curl` delle pagine: codice 200 e nessun `Warning:` / `Fatal error` / `Notice:` nell'HTML; log PHP con
  `docker compose logs wordpress` (in locale `WORDPRESS_DEBUG` è attivo).
- **Confronto prima/dopo**: estrarre testo e link da `<main>` (Python, rimuovendo i tag) e fare `diff`.
  Unica differenza accettata: l'email offuscata da `antispambot()`, che cambia a ogni caricamento.
- **Pannello**: login con `curl` (cookie jar) e controllo delle schermate di modifica.
- **A schermo**: Playwright a 1440, 1000 e 390 px; screenshot in `.playwright-mcp/` da cancellare a fine prova.

---

## 9. Partire da questa base per un nuovo progetto

1. **Copia** la repo in una nuova cartella **senza** `.git`, `.env` e `theme/acf-json/*.json`; poi
   `git init`.
2. **Rinomina** il tema:
   - cartella del tema nel container: in `docker-compose.yml` `./theme:/var/www/html/wp-content/themes/<slug>`
     (due servizi) e `name:` del progetto compose;
   - `theme/style.css`: Theme Name, Theme URI, Description, `Version: 0.1.0`, `Text Domain`;
   - prefisso delle funzioni `toninato_` → `<slug>_` in tutto `theme/` e `tools/` (anche gli handle
     `toninato-main`); chiavi dei campi `tn_` → nuovo prefisso breve;
   - `.env.example`: nomi del database.
3. **Dati**: riscrivere `inc/data.php` (recapiti, servizi) e le pagine in `tools/seed-content.php`;
   sostituire immagini, font, colori (`:root` in `main.css`), logo/monogramma e favicon.
4. **Ambiente locale**: sezione 2; creare `rmweb`, eliminare `admin`, installare SCF, lanciare il seed.
5. **Campi SCF**: per ogni pagina seguire la ricetta della sezione 4 (fallback + campi compilati).
6. **Stage**:
   - nuova cartella `/www.demosocialone.it/<cartella>` e nuovo prefisso tabelle, verificati liberi;
     installare WordPress lì;
   - in `deploy.py` aggiornare `TARGETS` (`label`, `url`, `remote_path`, `allowed_base`);
   - `python deploy.py --dry-run`, poi deploy; con lo script temporaneo: tema attivo, permalink
     `/%postname%/`, utente `rmweb`, eliminazione di `admin`, plugin, sincronizzazione dei gruppi SCF,
     seed.
7. **Documentazione**: aggiornare `README.md`, `ISTRUZIONI.md` (indirizzi, tabella delle pagine,
   stage) e questo file.

---

## 10. Stato di questo progetto (Toninato)

- Cliente: Avv. Stefania Toninato, studio legale a Moncalieri (TO). Rifacimento del vecchio sito Joomla.
- Tema v0.5.0. Pagine: Home, Lo Studio, Garanzia di professionalità, Servizi (+7 aree), Diritto civile,
  Diritto penale, Contatti. Stage: `https://www.demosocialone.it/toninato4/` allineato al locale.
- Campi SCF: solo la Home. Da fare: le altre pagine con la stessa ricetta; una pagina opzioni SCF
  "Dati dello studio" per telefoni, indirizzo, email e testo del riquadro contatti (oggi in `data.php`).
- Ancora presenti i contenuti di default di WordPress ("Ciao mondo", "Pagina d'esempio", bozza Privacy).
