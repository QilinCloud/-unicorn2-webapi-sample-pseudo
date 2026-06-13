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
