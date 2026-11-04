# hanstudios.de FTPS Deploy (All-Inkl KAS)
#
# Spiegelt `php/` nach `/hanstudios.de/` auf dem Webspace. AV-Scanner des
# Hosters wirft Data-Connections >15KB bei voller Geschwindigkeit ab;
# deshalb --rate-limit 150k und TLSv1.2 nach memory-Notiz.
#
# Vorausgesetzt: lftp (`winget install lftp` oder scoop/chocolatey). curl
# kann es auch, aber lftp spiegelt Ordner in einem Rutsch und lässt
# `config.php` + `public/uploads/` in Ruhe.
#
# Aufruf:
#   powershell -ExecutionPolicy Bypass -File php/bin/deploy-ftps.ps1
#   powershell -File php/bin/deploy-ftps.ps1 -NurTrocken     # Vorschau ohne Upload
#   powershell -File php/bin/deploy-ftps.ps1 -NurMigration   # nur public/migrate-krumbach-live.php senden
#
# Passwort kommt nicht in den Chat und nicht ins Repo. Wird einmal beim
# Start abgefragt (SecureString) oder aus Umgebungsvariable KAS_FTP_PASS
# gelesen.

param(
  [string]$Host = 'w0219c08.kasserver.com',
  [string]$User = 'f0190c47',
  [string]$RemoteBase = '/hanstudios.de',
  [switch]$NurTrocken,
  [switch]$NurMigration
)

$ErrorActionPreference = 'Stop'

# ---- Passwort besorgen ----
$pass = $env:KAS_FTP_PASS
if (-not $pass) {
  $sec = Read-Host "FTP-Passwort fuer $User@$Host" -AsSecureString
  $pass = [Runtime.InteropServices.Marshal]::PtrToStringAuto(
    [Runtime.InteropServices.Marshal]::SecureStringToBSTR($sec))
}

# ---- lftp vorhanden? ----
$lftp = Get-Command lftp -ErrorAction SilentlyContinue
if (-not $lftp) {
  Write-Host "FEHLER: lftp nicht gefunden. Installation:" -ForegroundColor Red
  Write-Host "  winget install lftp" -ForegroundColor Yellow
  Write-Host "  oder: scoop install lftp" -ForegroundColor Yellow
  exit 1
}

$repo = Resolve-Path (Join-Path $PSScriptRoot '..\..')
$local = Join-Path $repo 'php'

# ---- lftp-Script bauen ----
# set ftp:ssl-force true  → FTPS (expliziter TLS auf Port 21)
# set ssl:verify-certificate yes
# set net:limit-rate 150000:150000
$dryRun = if ($NurTrocken) { '--dry-run' } else { '' }

if ($NurMigration) {
  # Nur die einmalige Migrationsdatei senden
  $script = @"
set ftp:ssl-protect-data true
set ftp:ssl-force true
set ssl:verify-certificate no
set net:limit-rate 150000:150000
set net:timeout 60
set net:max-retries 3
open -u "$User","$pass" "$Host"
cd "$RemoteBase/public"
put "$local/public/migrate-krumbach-live.php"
bye
"@
} else {
  # Vollständiger Mirror. Ausgeschlossen:
  #   - config.php (enthält DB-Passwort, auf Server bereits korrekt)
  #   - public/uploads/ (Kundenfotos)
  #   - .git, node_modules, tests/
  $script = @"
set ftp:ssl-protect-data true
set ftp:ssl-force true
set ssl:verify-certificate no
set net:limit-rate 150000:150000
set net:timeout 60
set net:max-retries 3
set mirror:use-pget-n 1
open -u "$User","$pass" "$Host"
mirror $dryRun --reverse --delete-first --parallel=1 --verbose `
  --exclude-glob config.php `
  --exclude-glob public/uploads/ `
  --exclude-glob public/uploads `
  --exclude-glob bin/deploy-*.ps1 `
  --exclude-glob tests/ `
  --exclude-glob .git/ `
  "$local" "$RemoteBase"
bye
"@
}

Write-Host "Starte FTPS-Deploy nach ${Host}:${RemoteBase} ..." -ForegroundColor Cyan
if ($NurTrocken) { Write-Host "(Trockenlauf – keine Uebertragung)" -ForegroundColor Yellow }

$script | & $lftp.Source
if ($LASTEXITCODE -ne 0) { throw "lftp ist mit Exit $LASTEXITCODE abgebrochen" }

Write-Host "FTPS-Deploy abgeschlossen." -ForegroundColor Green
Write-Host ""
Write-Host "NAECHSTER SCHRITT (einmal):" -ForegroundColor Yellow
Write-Host "  1. https://hanstudios.de/migrate-krumbach-live.php?token=KRUMBACH-2026-11-04 oeffnen"
Write-Host "  2. Nach Erfolgsmeldung: Datei wieder vom Server loeschen"
Write-Host "     (per FTP: /hanstudios.de/public/migrate-krumbach-live.php)"
