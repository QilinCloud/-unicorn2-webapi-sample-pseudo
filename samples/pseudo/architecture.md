# Pseudo Implementation Architecture

The implementation is function based because the ApiWeb runtime dispatches method names from Unicorn requests to PHP functions.

Each function should follow this structure:

1. Validate the incoming request data.
2. Call the marketplace API or SDK.
3. Map marketplace data to ApiWeb interface objects.
4. Return success data or structured errors.
5. Continue per object when only one object failed.

