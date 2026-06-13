# Samples Architecture

`sync.php` at the repository root loads the implementation file from this folder.

The active flow is:

1. `api.php` receives a signed Unicorn request.
2. `sync.php` loads framework classes and the configured implementation.
3. `classes/class.config.php` resolves `samples/pseudo/sync.php`.
4. `samples/pseudo/sync.php` returns deterministic example data and structured negative responses.

