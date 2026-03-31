# Changelog

## 2026.3.0.2 — 2026-03-31

- **Fix** : `rc.unflared start` ne bloque plus la séquence de boot Unraid.
  Le subshell background `( ) &` héritait du pipe fd ouvert par le système de plugins
  (`popen()`/`pclose()`), ce qui empêchait `pclose()` de retourner tant que cloudflared
  tournait — bloquant `rc.local` et retardant le démarrage d'emhttpd.

## 2026.3.0.1 — 2026-03-29

Initial release.

- Native Cloudflare Tunnel service for Unraid (no Docker)
- cloudflared 2026.3.0
- Settings page: token configuration, start/stop/restart, version status, start at boot option
- Auto-start at array boot via event/started
- Daily update check with Unraid notification
- Config persisted on USB flash (/boot)
- GitHub Actions workflow for automated PLG updates on new cloudflared releases
