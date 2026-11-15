#!/usr/bin/env python3
"""
Deploy del tema Toninato via FTP.

    python deploy.py                 # pubblica il tema sullo stage
    python deploy.py --dry-run       # mostra cosa cambierebbe, senza toccare niente
    python deploy.py --full          # ricarica tutti i file, non solo quelli cambiati

Carica SOLO la cartella theme/ dentro la cartella del tema sul server e non
tocca nient'altro. Carica solo i file modificati: l'elenco di quelli già
pubblicati sta nel file .deploy-manifest.json nella cartella remota del tema.

Credenziali FTP, in ordine di priorità:
  1. variabili DEPLOY_<TARGET>_FTP_HOST / _FTP_USER / _FTP_PASS (anche nel file .env)
  2. il sito di FileZilla indicato da DEPLOY_<TARGET>_FTP_SITE
"""

import argparse
import base64
import ftplib
import hashlib
import io
import json
import os
import posixpath
import subprocess
import sys
import xml.etree.ElementTree as ET
from pathlib import Path

ROOT = Path(__file__).resolve().parent
THEME_DIR = ROOT / "theme"
MANIFEST = ".deploy-manifest.json"
IGNORE = {".DS_Store", "Thumbs.db", "desktop.ini", MANIFEST}

# Destinazioni. "allowed_base" è il recinto: il percorso remoto DEVE stare qui dentro.
TARGETS = {
    "stage": {
        "label": "Stage (demosocialone.it/toninato4)",
        "url": "https://www.demosocialone.it/toninato4/",
        "remote_path": "/www.demosocialone.it/toninato4/wp-content/themes/toninato",
        "allowed_base": "/www.demosocialone.it/toninato4/wp-content/themes/",
        "ftp_site": "Demosocialone.it",
    },
}


# --------------------------------------------------------------------------- #
# Utilità
# --------------------------------------------------------------------------- #

def die(msg):
    print(f"\n✖ {msg}")
    sys.exit(1)


def load_env():
    env = {}
    path = ROOT / ".env"
    if path.is_file():
        for line in path.read_text(encoding="utf-8").splitlines():
            line = line.strip()
            if not line or line.startswith("#") or "=" not in line:
                continue
            k, v = line.split("=", 1)
            env[k.strip()] = v.strip().strip('"').strip("'")
    env.update({k: v for k, v in os.environ.items() if k.startswith("DEPLOY_")})
    return env


def filezilla_site(name):
    appdata = os.environ.get("APPDATA") or str(Path.home() / ".config")
    xml = Path(appdata) / "FileZilla" / "sitemanager.xml"
    if not xml.is_file():
        return None
    for s in ET.parse(xml).iter("Server"):
        if (s.findtext("Name") or "").strip().lower() == name.lower():
            p = s.find("Pass")
            pw = p.text if p is not None else ""
            if p is not None and p.get("encoding") == "base64":
                pw = base64.b64decode(pw).decode("utf-8")
            if (s.findtext("Protocol") or "0") != "0":
                die(f"Il sito FileZilla '{name}' non usa FTP semplice: non supportato.")
            return {
                "host": s.findtext("Host"),
                "port": int(s.findtext("Port") or 21),
                "user": s.findtext("User"),
                "pass": pw,
            }
    return None


def credentials(target_name, target, env):
    key = f"DEPLOY_{target_name.upper()}_"
    if env.get(key + "FTP_HOST"):
        return {
            "host": env[key + "FTP_HOST"],
            "port": int(env.get(key + "FTP_PORT", 21)),
            "user": env.get(key + "FTP_USER", ""),
            "pass": env.get(key + "FTP_PASS", ""),
        }
    site = env.get(key + "FTP_SITE", target["ftp_site"])
    creds = filezilla_site(site)
    if not creds:
        die(
            f"Credenziali FTP non trovate: né {key}FTP_HOST nel .env, "
            f"né il sito '{site}' in FileZilla."
        )
    return creds


def sha1(path):
    h = hashlib.sha1()
    with open(path, "rb") as fh:
        for chunk in iter(lambda: fh.read(65536), b""):
            h.update(chunk)
    return h.hexdigest()


def local_files():
    files = {}
    for p in sorted(THEME_DIR.rglob("*")):
        if p.is_file() and p.name not in IGNORE:
            rel = p.relative_to(THEME_DIR).as_posix()
            files[rel] = sha1(p)
    return files


def theme_version():
    for line in (THEME_DIR / "style.css").read_text(encoding="utf-8").splitlines():
        if line.strip().lower().startswith("version:"):
            return line.split(":", 1)[1].strip()
    return "?"


def git_dirty():
    try:
        out = subprocess.run(
            ["git", "status", "--porcelain", "--", "theme"],
            cwd=ROOT, capture_output=True, text=True, check=True,
        ).stdout.strip()
        return out
    except (OSError, subprocess.CalledProcessError):
        return ""


# --------------------------------------------------------------------------- #
# FTP
# --------------------------------------------------------------------------- #

class Remote:
    def __init__(self, creds, base, allowed_base, dry_run):
        self.base = base.rstrip("/")
        self.allowed = allowed_base.rstrip("/") + "/"
        self.dry = dry_run
        self.made = set()
        if not (self.base + "/").startswith(self.allowed) or ".." in self.base:
            die(f"Percorso remoto {self.base} fuori dal recinto {self.allowed}")
        self.ftp = ftplib.FTP()
        self.ftp.connect(creds["host"], creds["port"], timeout=60)
        self.ftp.login(creds["user"], creds["pass"])
        self.ftp.set_pasv(True)

    def path(self, rel):
        full = posixpath.normpath(posixpath.join(self.base, rel))
        if not full.startswith(self.base + "/"):
            die(f"Percorso non consentito: {full}")
        return full

    def exists_dir(self, path):
        cur = self.ftp.pwd()
        try:
            self.ftp.cwd(path)
            return True
        except ftplib.error_perm:
            return False
        finally:
            self.ftp.cwd(cur)

    def ensure_dir(self, path):
        if path in self.made:
            return
        inside = (path + "/").startswith(self.allowed) and (path + "/") != self.allowed
        if not inside:
            # sopra il recinto non si crea niente: la cartella deve esistere già
            if not self.exists_dir(path):
                die(f"La cartella {path} non esiste sul server.")
        elif not self.exists_dir(path):
            self.ensure_dir(posixpath.dirname(path))
            if not self.dry:
                self.ftp.mkd(path)
        self.made.add(path)

    def read_manifest(self):
        buf = io.BytesIO()
        try:
            self.ftp.retrbinary("RETR " + self.path(MANIFEST), buf.write)
            return json.loads(buf.getvalue().decode("utf-8"))
        except (ftplib.error_perm, ValueError):
            return {}

    def write_manifest(self, data):
        if self.dry:
            return
        payload = json.dumps(data, indent=1, sort_keys=True).encode("utf-8")
        self.ftp.storbinary("STOR " + self.path(MANIFEST), io.BytesIO(payload))

    def upload(self, rel):
        target = self.path(rel)
        self.ensure_dir(posixpath.dirname(target))
        if not self.dry:
            with open(THEME_DIR / rel, "rb") as fh:
                self.ftp.storbinary("STOR " + target, fh)

    def delete(self, rel):
        if not self.dry:
            try:
                self.ftp.delete(self.path(rel))
            except ftplib.error_perm:
                pass

    def close(self):
        try:
            self.ftp.quit()
        except Exception:
            self.ftp.close()


# --------------------------------------------------------------------------- #

def main():
    ap = argparse.ArgumentParser(description="Deploy del tema Toninato via FTP.")
    ap.add_argument("target", nargs="?", default="stage", choices=sorted(TARGETS))
    ap.add_argument("--dry-run", action="store_true", help="mostra le modifiche senza caricare niente")
    ap.add_argument("--full", action="store_true", help="ricarica tutti i file")
    ap.add_argument("-y", "--yes", action="store_true", help="non chiedere conferma")
    args = ap.parse_args()

    if hasattr(sys.stdout, "reconfigure"):
        sys.stdout.reconfigure(encoding="utf-8")

    target = TARGETS[args.target]
    env = load_env()
    remote_path = env.get(f"DEPLOY_{args.target.upper()}_PATH", target["remote_path"])

    print(f"→ Destinazione: {target['label']}")
    print(f"  Cartella remota: {remote_path}")
    print(f"  Versione tema: {theme_version()}")
    if args.dry_run:
        print("  Modalità prova: non verrà caricato niente.")

    dirty = git_dirty()
    if dirty:
        print("\n⚠ Ci sono modifiche al tema non ancora committate:")
        print("  " + dirty.replace("\n", "\n  "))

    files = local_files()
    remote = Remote(credentials(args.target, target, env), remote_path, target["allowed_base"], args.dry_run)

    try:
        if not remote.exists_dir(remote.base):
            print("\nLa cartella del tema non esiste ancora sul server: verrà creata.")

        old = {} if args.full else remote.read_manifest()
        changed = [f for f, h in files.items() if old.get(f) != h]
        removed = [f for f in old if f not in files]

        print(f"\nFile locali: {len(files)} · da caricare: {len(changed)} · da eliminare: {len(removed)}")
        for f in changed:
            print(f"  + {f}")
        for f in removed:
            print(f"  - {f}")

        if not changed and not removed:
            print("\n✔ Niente da pubblicare: lo stage è già aggiornato.")
            return

        if args.dry_run:
            print("\n(prova terminata, nessuna modifica fatta)")
            return

        if not args.yes:
            ans = input("\nProcedo con il deploy? [s/N] ").strip().lower()
            if ans not in ("s", "si", "sì", "y", "yes"):
                print("Annullato.")
                return

        for i, f in enumerate(changed, 1):
            print(f"  [{i}/{len(changed)}] {f}")
            remote.upload(f)
        for f in removed:
            print(f"  eliminato {f}")
            remote.delete(f)

        remote.write_manifest(files)
        print(f"\n✔ Deploy completato: {target['url']}")
    finally:
        remote.close()


if __name__ == "__main__":
    try:
        main()
    except KeyboardInterrupt:
        print("\nInterrotto.")
        sys.exit(130)
    except (ftplib.all_errors) as e:
        die(f"Errore FTP: {e}")
