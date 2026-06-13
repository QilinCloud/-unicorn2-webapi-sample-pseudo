# Pseudo Implementation Workflows

Developer workflow:

1. Start with the existing function body in `sync.php`.
2. Replace the pseudo marketplace comment with a real API call.
3. Keep the ApiWeb response object shape unchanged.
4. Add a structured error response for every expected marketplace failure.
5. Test both happy path and unhappy path locally before publishing the connector.

