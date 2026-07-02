# Phlo WhatsApp demo - in a single app

A standalone app that drives [Phlo WhatsApp](https://github.com/q-ainl/phlo-whatsapp) through the engine's `WhatsApp`
resource. It sends text, images, polls and locations, and receives inbound
messages on a secret-protected webhook.


## Safe by design

This repo contains **no phone numbers and no secrets**. Credentials live only in
`data/creds.ini` (gitignored) or in the environment. Without them the app is
inert: the send buttons explain how to configure it, and the `/receive` webhook
rejects every request with `401`. You can deploy it publicly as-is.

## Run with Docker

```sh
git clone https://github.com/q-ainl/phlo-demo-whatsapp.git
cd phlo-demo-whatsapp
docker compose up
# watch the `whatsapp` service logs for a QR code and scan it with WhatsApp
# then open http://localhost:8080
```

`docker-compose.yml` runs the app plus the **phlo-whatsapp** bridge
(whatsapp-web.js) as a sidecar. Set a shared `WA_SECRET` on both services, scan the
QR to link a real account, and sending and receiving work end to end. Without a
linked account the app still serves; the send buttons and the `/receive` webhook
stay inert.

## What it shows

- **Sending** - one route per `WhatsApp` resource method:
  `text`, `image` (file upload), `poll`, `location`, plus a `status` check.
  Every send is wrapped so a missing or offline gateway returns a clean toast
  instead of an error.
- **Receiving** - `POST /receive` verifies the shared secret with
  `hash_equals`, then logs the inbound message. Point your Phlo WhatsApp gateway's
  webhook at this URL.
- **Credential shielding** - `%creds->whatsapp->url` / `->secret` are read from
  `data/creds.ini` or `PHLO__whatsapp__url` / `PHLO__whatsapp__secret` env vars.

## Configure it (to send/receive for real)

1. Run a Phlo WhatsApp gateway (whatsapp-web.js) somewhere you control, linked to your
   own number, exposing a secret-gated HTTP endpoint.
2. Copy the credentials template and fill in your values:
   ```sh
   cp data/creds.ini.example data/creds.ini
   # edit data/creds.ini: url + secret of your gateway
   ```
3. In the gateway config, set its inbound webhook to
   `https://your-host/receive` with the same secret.

That is all. The page then shows "Configured", the send forms reach your
gateway, and inbound messages appear in the Inbox.

## Layout

```
app.phlo            # send/receive routes, helpers and the UI
type.message.phlo   # inbound message log (JSON file, gitignored)
app.style.phlo      # styling
data/app.json       # resources (WhatsApp, creds, async, ...)
data/creds.ini.example  # template - copy to data/creds.ini (gitignored)
www/app.php         # entry point
```

## Never committed

`creds.ini`, `.env`, `.wwebjs_auth/`, `data/received.json` and uploads are all
in `.gitignore`. The linked WhatsApp account session (`.wwebjs_auth/`) belongs
to the gateway process and never touches this repo.
