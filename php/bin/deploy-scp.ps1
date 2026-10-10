# Canlı VPS'e (atelier-vps) PHP sürümünü scp ile gönderir.
#
# php/YAYIN-VPS.md'deki "kısa yol"un betiğidir: arşivi üretir, scp ile
# gönderir ve sunucu adımlarını (yedek, üstüne açma, izinler, şema,
# php-fpm reload) tek seferde koşar.
#
# Belgeden tek farkı: paket çalışma ağacından üretilir, yani henüz commit
# edilmemiş değişiklikler de gider. `git stash create` depoyu değiştirmez,
# yalnızca anlık ağaç nesnesi üretir. config.php ve public/uploads zaten
# sürüm dışı olduğu için pakete girmez; sunucuda üstüne açmak onlara dokunmaz.
#
# Kullanım:
#   powershell -ExecutionPolicy Bypass -File php/bin/deploy-scp.ps1
#   powershell -ExecutionPolicy Bypass -File php/bin/deploy-scp.ps1 -NurPaket
#
# Gereken: git, scp, ssh (Windows OpenSSH) ve ~/.ssh/config'de 'atelier-vps'.

param(
  [string]$Server = 'atelier-vps',
  [string]$Ziel   = '/var/www/atelier',
  [string]$PhpFpm = 'php8.3-fpm',
  [switch]$NurPaket
)

$ErrorActionPreference = 'Stop'

# Windows PowerShell 5.1 setzt beim Pipen in ein natives Programm ein BOM vor
# den Text. Auf dem Server wurde daraus "﻿set: command not found", und
# set -e lief nie (10.10.2026). UTF-8 ohne BOM.
$OutputEncoding = New-Object System.Text.UTF8Encoding $false

$repo    = Resolve-Path (Join-Path $PSScriptRoot '..\..')
$tar     = Join-Path $env:TEMP 'atelier-php.tar.gz'
$tarUnix = $tar -replace '\\', '/'

Push-Location $repo
try {
  $ref = git stash create
  if (-not $ref) { $ref = 'HEAD' }
  Write-Host "Paket kaynagi: $ref"

  if (Test-Path $tar) { Remove-Item $tar }
  git archive --format=tar.gz -o $tarUnix "${ref}:php"

  $mb = [math]::Round((Get-Item $tar).Length / 1MB, 1)
  Write-Host "Paket hazir: $tar ($mb MB)"
  if ($NurPaket) { return }

  Write-Host "Gonderiliyor -> ${Server}:/root/ ..."
  scp $tar "${Server}:/root/"

  Write-Host 'Sunucuda aciliyor ...'
  $uzak = @"
set -e
yedek=/var/www/atelier-yedek-`$(date +%F-%H%M)
rsync -a --exclude 'public/uploads' $Ziel/ `$yedek/
tar xzf /root/atelier-php.tar.gz -C $Ziel
chown -R www-data:www-data $Ziel
find $Ziel -type d -exec chmod 750 {} +
find $Ziel -type f -exec chmod 640 {} +
cd $Ziel
php bin/schema-anwenden.php
systemctl reload $PhpFpm
echo "Bitti. Yedek: `$yedek"
"@ -replace "`r`n", "`n"

  $uzak | ssh $Server 'bash -s'
  Write-Host 'Tamam.'
}
finally { Pop-Location }
