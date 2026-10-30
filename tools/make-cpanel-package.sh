#!/usr/bin/env bash
# Builds deploy/mc-attorneys-cpanel.zip — the files to upload to cPanel (public_html).
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$ROOT/deploy"
TMP="$(mktemp -d)"
mkdir -p "$OUT"
rsync -a \
  --exclude='.git' --exclude='.gitignore' --exclude='.cpanel.yml' --exclude='tools' --exclude='deploy' \
  --exclude='docs' --exclude='README.md' --exclude='includes/config.php' --exclude='install/.lock' \
  --exclude='uploads/.gitkeep' \
  "$ROOT/" "$TMP/site/"
mkdir -p "$TMP/site/uploads"
cp "$ROOT/uploads/.htaccess" "$TMP/site/uploads/.htaccess"
# Web server must be able to read everything: folders 755, files 644.
find "$TMP/site" -type d -exec chmod 755 {} +
find "$TMP/site" -type f -exec chmod 644 {} +
rm -f "$OUT/mc-attorneys-cpanel.zip"
( cd "$TMP/site" && zip -rq -X "$OUT/mc-attorneys-cpanel.zip" . -x '.DS_Store' )
rm -rf "$TMP"
echo "Created $OUT/mc-attorneys-cpanel.zip"
unzip -l "$OUT/mc-attorneys-cpanel.zip" | tail -1
