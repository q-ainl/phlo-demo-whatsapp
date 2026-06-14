# data/app.json notes

Minimal resource set for a phloWA front-end:

- `WhatsApp` + `HTTP` - the client that talks to the phloWA gateway.
- `security/creds` - reads `data/creds.ini` and `PHLO__*` env vars into
  `%creds`. Sensitive values are wrapped so they never print by accident.
- `files/file` - inbound uploads arrive in the payload as `file` objects, which
  the `image` send passes straight to the gateway.
- `DB/JSONDB` + `DB/JSON.result` - the flat-file store for the inbound log.
- `phlo.async` + `DOM/form` + `DOM/toasts` - async form posts with toasts.

No theme resource is loaded; the app styles itself in `app.style.phlo`.
