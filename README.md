# Unicorn 2 ApiWeb Pseudo Marketplace Sample

This repository is a standalone ApiWeb connector skeleton. It contains no real
marketplace transport. Every method returns valid sample data and includes a
comment showing where a third-party developer should call their marketplace API.

Use this repository when you want to build a new connector from scratch and need
the method shape, result handling, metadata, DTOs and negative responses without
OTTO-specific logic.

Public protocol documentation:

- https://webservice.marcos-software.de/index.html
- https://webservice.marcos-software.de/endpoints.html
- https://webservice.marcos-software.de/samples.html
- https://webservice.marcos-software.de/conformance.html
- https://webservice.marcos-software.de/field-test-checklist.html
- https://webservice.marcos-software.de/openapi.yaml

## Local start

```powershell
$env:APIWEB_TEST_KEY='local-dev-api-key-2026'
php -S 127.0.0.1:18080 -t .
```

In Unicorn 2 configure:

```text
http://127.0.0.1:18080/api.php
```

## Where to add marketplace code

Open `samples/pseudo/sync.php`. Each ApiWeb method has a comment like:

```php
/* Replace this block with: POST /marketplace/products */
```

Replace that block with your own marketplace client call. Do not throw for
ordinary item errors. Add `$result->addError(code, message)` and continue with
the next object.

## Negative tests

Set `APIWEB_FAILURE_MODE` to `invalid_credentials`, `getOrders:quota`,
`getOrders:api_down` or `getOrders:unknown` to test error handling.

## Field-tested pitfalls

- ApiWeb body hashes and HMAC signatures are Base64 encoded, not hex encoded.
- Response signatures use transport marker `RESPONSE`.
- Response `X-Unicorn-Api-Method` must be the original ApiWeb method, never
  `response`.
- Sign the exact raw JSON body that is sent over HTTP.
- After FTP upload, verify the exact public URL to `api.php`; some hostings use
  a subfolder.
- Unsigned public calls to `api.php` should return a JSON `401`; `404` means
  wrong path and `500` means PHP/server failure.
- Protect `config.php`, `.env`, backups and logs from public download.
- For write methods, resolve the marketplace offer/listing/unit id from
  `ShopId`, SKU or EAN when the marketplace does not use the same identifier.
