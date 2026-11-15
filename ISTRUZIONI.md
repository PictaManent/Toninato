# Istruzioni per lo sviluppo in locale

Guida rapida per lavorare sul tema Toninato con WordPress in locale su Docker.

---

## 1. Prerequisiti

- **Docker Desktop** installato e avviato (l'icona della balena deve essere verde).
- **Git**.
- Porte libere: `8080` (sito) e `8081` (phpMyAdmin). Si cambiano nel file `.env`.

---

## 2. Indirizzi e credenziali

| Cosa             | Indirizzo                         | Credenziali                |
|------------------|-----------------------------------|----------------------------|
| Sito             | http://localhost:8080             | —                          |
| Pannello admin   | http://localhost:8080/wp-admin    | `rmweb` / `toninato2026rmw` |
| phpMyAdmin       | http://localhost:8081             | `toninato` / `toninato`    |
| Database (root)  | —                                 | `root` / `root`            |

Le credenziali del database si trovano nel file `.env`.

---

## 3. Prima installazione (su un PC nuovo)

Da eseguire una sola volta, dentro la cartella del progetto.

```bash
# 1. Clona la repo
git clone https://github.com/PictaManent/Toninato.git
cd Toninato

# 2. Crea il file .env partendo dall'esempio
cp .env.example .env            # Git Bash
Copy-Item .env.example .env     # PowerShell

# 3. Avvia i container
docker compose up -d

# 4. Installa WordPress (attendere qualche secondo dopo l'avvio)
docker compose run --rm wpcli wp core install --url=http://localhost:8080 --title=Toninato --admin_user=rmweb --admin_password=toninato2026rmw --admin_email=sviluppo@socialone.it --skip-email

# 5. Lingua italiana
docker compose run --rm wpcli wp language core install it_IT --activate

# 6. Attiva il tema
docker compose run --rm wpcli wp theme activate toninato
```

Se il punto 4 dà un errore di connessione al database, aspetta 10-20 secondi che MariaDB finisca di avviarsi e riprova.

---

## 4. Uso quotidiano

```bash
docker compose up -d       # avvia (o riavvia) l'ambiente
docker compose stop        # ferma i container, i dati restano
docker compose ps          # mostra lo stato dei container
docker compose logs -f wordpress   # log di WordPress/PHP in tempo reale (Ctrl+C per uscire)
```

Il tema si modifica direttamente nella cartella `theme/`: i cambiamenti si vedono subito ricaricando il browser, non serve riavviare niente.

---

## 5. Struttura del progetto

```
Toninato/
├── theme/                 ← il tema WordPress (l'unica parte da sviluppare)
│   ├── style.css          ← intestazione del tema (nome, versione)
│   ├── functions.php      ← supporti del tema, caricamento CSS/JS e font
│   ├── header.php         ← barra contatti, logo, menu
│   ├── footer.php         ← piè di pagina
│   ├── page.php           ← template predefinito delle pagine
│   ├── index.php          ← template di riserva (articoli, archivi, 404)
│   ├── page-templates/    ← template da scegliere nella pagina (Attributi → Template)
│   │   ├── template-home.php      ← "Home" (la homepage)
│   │   ├── template-servizi.php   ← "Servizi (elenco)"
│   │   ├── template-servizio.php  ← "Servizio (dettaglio)" per ogni area di attività
│   │   └── template-contatti.php  ← "Contatti"
│   ├── template-parts/    ← blocchi riusati: intestazione pagina, griglia servizi, contatti
│   ├── inc/
│   │   ├── data.php       ← DATI DELLO STUDIO: recapiti, servizi, menu di riserva
│   │   └── template-tags.php ← icone, logo testuale, funzioni di supporto
│   └── assets/
│       ├── css/main.css   ← tutti gli stili (colori e font in :root in cima)
│       ├── js/main.js     ← menu mobile e animazioni di comparsa
│       ├── fonts/         ← Bacasime Antique e Tajawal (ospitati in locale, niente Google)
│       └── images/        ← foto dello studio e dei servizi
├── tools/seed-content.php ← crea pagine, immagini e menu (vedi sotto)
├── deploy.py              ← pubblica il tema sullo stage
├── docker-compose.yml     ← configurazione dei container
├── .env.example           ← modello delle variabili (porte, credenziali)
├── .env                   ← variabili reali (NON va su git)
└── ISTRUZIONI.md          ← questo file
```

WordPress (core e plugin) e il database **non** stanno nella repo: vivono nei volumi Docker.

**Per cambiare telefono, indirizzo, email o servizi** si modifica solo `theme/inc/data.php`:
header, homepage e footer si aggiornano da soli.

### Pagine e menu

Le pagine esistono davvero in WordPress (Pagine → Tutte le pagine), ognuna con il suo template:

| Pagina | Indirizzo | Template |
|--------|-----------|----------|
| Home (homepage) | `/` | Home |
| Lo Studio | `/lo-studio/` | predefinito |
| Garanzia di professionalità | `/garanzia-di-professionalita/` | predefinito |
| Servizi | `/servizi/` | Servizi (elenco) |
| 8 aree di attività | `/servizi/<nome>/` | Servizio (dettaglio) |
| Diritto civile | `/diritto-civile/` | predefinito |
| Diritto penale | `/diritto-penale/` | predefinito |
| Contatti | `/contatti/` | Contatti |

- I **testi** si modificano dall'editor di WordPress. Nella Home il testo modificabile è la presentazione
  (il riquadro bianco sotto la foto); le altre sezioni della Home sono nel template.
- L'**immagine in evidenza** di ogni pagina è lo sfondo della sua intestazione.
- Il **riassunto** (excerpt) della pagina è la frase sotto il titolo.
- **Menu**: Aspetto → Menu. "Menu principale" è collegato all'header, "Menu footer" ai Link utili.

Per ricreare pagine, immagini e menu (per esempio dopo `docker compose down -v`):

```bash
docker compose run --rm wpcli wp eval-file /tools/seed-content.php          # crea solo ciò che manca
docker compose run --rm wpcli wp eval-file /tools/seed-content.php update   # riscrive anche testi e menu
```

Lo script usa come autore l'utente `rmweb` e non crea doppioni se rilanciato.
⚠️ Con `update` le modifiche fatte a mano a testi e menu vengono sovrascritte.

---

## 6. Comandi WP-CLI utili

Tutti i comandi hanno la forma `docker compose run --rm wpcli wp <comando>`.

```bash
# Plugin
docker compose run --rm wpcli wp plugin list
docker compose run --rm wpcli wp plugin install <nome> --activate

# Temi
docker compose run --rm wpcli wp theme list

# Utenti
docker compose run --rm wpcli wp user list
docker compose run --rm wpcli wp user update rmweb --user_pass=nuovapassword

# Aggiornamenti
docker compose run --rm wpcli wp core update
docker compose run --rm wpcli wp plugin update --all

# Permalink "belli" (/nome-pagina/)
docker compose run --rm wpcli wp rewrite structure '/%postname%/'

# Svuotare la cache
docker compose run --rm wpcli wp cache flush
```

---

## 7. Backup e ripristino del database

> Usare **Git Bash**, non PowerShell: PowerShell 5 rovina la codifica dei file con `>` e non supporta `<`.

```bash
# Esporta il database in backup.sql
docker compose exec -T db mariadb-dump -u root -proot toninato > backup.sql

# Importa backup.sql nel database
docker compose exec -T db mariadb -u root -proot toninato < backup.sql
```

Dopo aver importato un database preso da un sito online, sostituire l'indirizzo:

```bash
docker compose run --rm wpcli wp search-replace 'https://sito-online.it' 'http://localhost:8080' --skip-columns=guid
```

I file `.sql` non vanno committati.

---

## 8. Ripartire da zero

```bash
docker compose down -v     # ATTENZIONE: cancella database, plugin e upload
docker compose up -d
```

Poi rifare i punti 4, 5 e 6 della prima installazione, ricreare l'utente `rmweb` e lanciare
`tools/seed-content.php` per pagine e menu. La cartella `theme/` non viene toccata.

---

## 9. Versioni del tema

La versione si trova in `theme/style.css` (`Version: X.Y.Z`):

- **Y (minor)** sale quando si aggiungono nuove funzionalità: `0.1.0` → `0.2.0`
- **Z (patch)** sale per correzioni e modifiche solo di grafica/CSS: `0.1.0` → `0.1.1`

La versione serve anche a forzare il browser a ricaricare il CSS dopo una modifica.

---

## 10. Stage online

| Cosa             | Valore |
|------------------|--------|
| Indirizzo        | https://www.demosocialone.it/toninato4/ |
| Pannello admin   | https://www.demosocialone.it/toninato4/wp-admin (`rmweb`) |
| Cartella FTP     | `/www.demosocialone.it/toninato4` (FileZilla → *Demosocialone.it*) |
| Database         | `Sql1892487_5`, prefisso tabelle `toninato4_wp_` |

Credenziali FTP e database: in FileZilla e nel `wp-config.php` dello stage, **non** nella repo.

> ⚠️ Lo spazio FTP contiene **tutti** i siti dei clienti e il database ospita altre demo:
> lavorare solo dentro `toninato4` e solo sulle tabelle `toninato4_wp_`.

### Pubblicare il tema sullo stage

```bash
python deploy.py --dry-run   # prova: mostra cosa verrebbe caricato, non tocca niente
python deploy.py             # pubblica (chiede conferma)
python deploy.py -y          # pubblica senza chiedere conferma
python deploy.py --full      # ricarica tutti i file, non solo quelli cambiati
```

Su Windows c'è anche la scorciatoia `.\deploy` (accetta le stesse opzioni).

- Carica **solo** la cartella `theme/` in `/www.demosocialone.it/toninato4/wp-content/themes/toninato/`
  e rifiuta qualsiasi percorso fuori da lì.
- Carica solo i file cambiati e cancella dal server quelli eliminati in locale.
  L'elenco dei file pubblicati sta in `.deploy-manifest.json` nella cartella remota del tema.
- Avvisa se ci sono modifiche al tema non ancora committate.
- Credenziali FTP: le legge dal sito *Demosocialone.it* di FileZilla. Su un PC senza FileZilla
  si mettono nel `.env` (vedi `.env.example`), che non va su git.
- Non tocca il database né i file di WordPress: pubblica solo il tema.
- Serve Python 3 (nessuna libreria da installare).

**Database:** il MySQL di Aruba non accetta connessioni dall'esterno; si gestisce da
phpMyAdmin del pannello Aruba. Il server è MySQL 8: negli import aggiungere in testa
`SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION';` altrimenti le date `0000-00-00` di WordPress danno errore.

---

## 11. Problemi frequenti

| Problema | Soluzione |
|----------|-----------|
| `port is already allocated` | Un altro programma usa la porta: cambiare `WP_PORT` o `PMA_PORT` nel `.env` e rifare `docker compose up -d`. Se cambia la porta del sito, aggiornare anche l'URL: `wp option update home http://localhost:NUOVA` e `wp option update siteurl http://localhost:NUOVA`. |
| `Error establishing a database connection` | MariaDB si sta ancora avviando: aspettare qualche secondo. Se persiste, `docker compose logs db`. |
| `Cannot connect to the Docker daemon` | Docker Desktop non è avviato. |
| Le modifiche al CSS non si vedono | Ricaricare con Ctrl+F5 oppure alzare la versione in `style.css`. |
| Pagina bianca / errore PHP | `docker compose logs -f wordpress`: il debug è attivo e gli errori compaiono lì. |
| Le pagine danno 404 | Rigenerare i permalink: `docker compose run --rm wpcli wp rewrite flush`. |
