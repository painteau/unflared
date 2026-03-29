<p align="center">
  <img src="source/usr/local/emhttp/plugins/unflared/unflared.png" width="80" alt="unflared">
</p>

<h1 align="center">unflared</h1>

<p align="center">
  Unraid plugin to run a <strong>Cloudflare Tunnel</strong> as a native service — no Docker required.
</p>

<p align="center">
  <a href="https://github.com/painteau/unflared/releases"><img src="https://img.shields.io/github/v/release/painteau/unflared" alt="release"></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="license"></a>
</p>

---

## Features

- **Native service** — runs `cloudflared` directly on the Unraid host, no Docker dependency
- **Settings page** — configure your tunnel token, start/stop/restart, version status
- **Start at boot** — optional auto-start when the array comes up
- **Update notifications** — daily check, Unraid notification when a new cloudflared version is available
- **Persistent** — binary and config stored on `/boot` (USB flash), survives reboots
- **Clean uninstall** — removes all runtime files, config kept for safety

---

## Requirements

- Unraid 6.9.0 or later
- A [Cloudflare Tunnel](https://developers.cloudflare.com/cloudflare-one/connections/connect-networks/) token (free Cloudflare account)

---

## Installation

1. In your Unraid WebUI, go to **Plugins → Install Plugin**
2. Paste the following URL and click **Install**:

```
https://raw.githubusercontent.com/painteau/unflared/main/unflared.plg
```

---

## Configuration

1. Go to **Settings → Network Services → unflared**
2. Paste your **Tunnel Token** (from the [Cloudflare Zero Trust dashboard](https://one.dash.cloudflare.com) → Networks → Tunnels → Create tunnel → Cloudflared)
3. Check **Start service at boot** if you want it to start automatically
4. Click **Save**, then **Start**

---

## Service management

Via the Settings page, or via SSH:

```bash
/etc/rc.d/rc.unflared start|stop|restart|status
```

Logs: `/var/log/cloudflared.log`

---

## Updates

unflared **never auto-updates** the `cloudflared` binary. A daily cron checks the latest release and sends an Unraid notification if a new version is available. To update, reinstall the plugin from the same URL.

---

## Uninstall

Go to **Plugins → unflared → Remove**.

Config is kept at `/boot/config/plugins/unflared/`. To fully clean:
```bash
rm -rf /boot/config/plugins/unflared
```

---

## Versioning

Format: `<cloudflared_version>.<plugin_revision>` — e.g. `2026.3.0.1` = cloudflared `2026.3.0`, plugin revision 1.

---

## Roadmap

Already in `main`, included in the next release:

- [x] Auto-restart cloudflared on crash (wrapper loop, no watchdog dependency)
- [x] Unraid notification on crash (throttled to max 1 every 4 hours)
- [x] Unraid notification after install when no token is configured yet

Planned:

- [ ] Log viewer in the settings page (tail of `/var/log/cloudflared.log`)
- [ ] Log rotation to prevent unbounded growth during uptime
- [ ] Token format validation before saving
- [ ] Graceful stop with SIGKILL fallback after timeout
- [ ] Tunnel metrics in the UI (cloudflared exposes a local metrics endpoint)
- [ ] Community Applications (CA) store listing

---

## License

[MIT](LICENSE) © 2026 Painteau
