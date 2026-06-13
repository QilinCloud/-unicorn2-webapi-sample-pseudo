# Pseudo Implementation Components

- `sync.php`: ApiWeb method implementation file.
- Helper functions near the top of `sync.php`: shared response, failure-mode and sample-data helpers.
- Method functions below the helpers: one function per ApiWeb operation.

Keep marketplace-specific credentials in `config.php` or `.env`, not in this file.

