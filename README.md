# Toninato

Tema WordPress su misura, con ambiente di sviluppo locale in Docker.

Guida completa (installazione, comandi, backup, problemi frequenti): [ISTRUZIONI.md](ISTRUZIONI.md).

Come è costruito il progetto e come usarlo come **base per i nuovi siti R.M.Web** (architettura, campi
Secure Custom Fields, stage, regole dell'agenzia): [CLAUDE.md](CLAUDE.md).

## Struttura

- `theme/` — il tema, montato nel container come `wp-content/themes/toninato`
- `docker-compose.yml` — WordPress, MariaDB, phpMyAdmin e WP-CLI

## Avvio

```bash
cp .env.example .env
docker compose up -d
```

- Sito: http://localhost:8080
- phpMyAdmin: http://localhost:8081

## WP-CLI

```bash
docker compose run --rm wpcli wp <comando>
```

## Deploy sullo stage

```bash
python deploy.py --dry-run   # prova
python deploy.py             # pubblica il tema su https://www.demosocialone.it/toninato4/
```

## Stop

```bash
docker compose down        # ferma i container
docker compose down -v     # ferma e cancella database e file WordPress
```
