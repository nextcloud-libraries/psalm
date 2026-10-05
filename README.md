<!--
  - SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
# Nextcloud Psalm plugin

Psalm checks shared by Nextcloud server and apps.

## Usage

```sh
composer require --dev nextcloud/psalm-plugin
vendor/bin/psalm-plugin enable nextcloud/psalm-plugin
```

## Checks

| Check | Issue type |
|---|---|
| Calls to methods marked `#[\OCP\AppFramework\Attribute\Expensive]` | `ExpensiveMethodCall` |
| `in_array()` without explicit `$strict` | `UnrecognizedExpression` |
| `and` / `or` operators | `UnrecognizedExpression` |
| `==` / `!=` (except between `DateTimeInterface`/`DateTimeZone`) | `UnrecognizedExpression` |
| Static properties / static variables | `ImpureStaticProperty` / `ImpureStaticVariable` |
| Positional attribute arguments | `InvalidDocblock` |
| Public controller method parameters as taint sources | (taint analysis) |

## Tests

```sh
composer test
```
