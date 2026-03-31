# Changelog

## 2026.3.0.2 — 2026-03-31

- **Fix**: `rc.unflared start` no longer blocks the Unraid boot sequence.
  The background subshell `( ) &` inherited the open pipe fd from the plugin system
  (`popen()`/`pclose()`), preventing `pclose()` from returning while cloudflared
  was running — blocking `rc.local` and delaying emhttpd startup.

## 2026.3.0.1 — 2026-03-29

Initial release.

- Native Cloudflare Tunnel service for Unraid (no Docker)
- cloudflared 2026.3.0
- Settings page: token configuration, start/stop/restart, version status, start at boot option
- Auto-start at array boot via event/started
- Daily update check with Unraid notification
- Config persisted on USB flash (/boot)
- GitHub Actions workflow for automated PLG updates on new cloudflared releases
