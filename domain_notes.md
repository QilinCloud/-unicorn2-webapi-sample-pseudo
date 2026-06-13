# Domain Notes

Use item-level errors for marketplace-specific failures. Do not abort the whole
request when one object fails. Unicorn can continue with the next object when
`Result.Errors` is set only on the failed item.
