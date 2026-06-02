---
description: Phel language conventions for source and test files
globs: src/**,tests/**,*.phel
---

# Phel Conventions

Depth: [docs/conventions.md](../../docs/conventions.md).

## Naming

- kebab-case for functions and variables: `build-greeting`, `run-adder`.
- `defn-` / `def-` for private (not exported).
- `?` suffix for predicates (`loud?`), `!` suffix for side-effecting fns.
- Namespaces match the path with a **dot** separator:
  `cli-skeleton.core.adder`, `cli-skeleton.commands.greet`. NEVER backslash.
- Test ns: `cli-skeleton-tests.<layer>.<name>-test` (plural `tests`, `-test`
  suffix on both file and ns).

## Docstrings

Public `defn` carries a docstring (shows up via `(doc foo)` in the REPL).
Skip it on private `defn-` unless the behavior is subtle.

## Semantics

- `defstruct` for data types, not PHP classes.
- Threading: `->` first-arg, `->>` last-arg.
- `for` builds sequences, `doseq` does side effects.
- CLI args via `(cli/argv argv)`, not raw `php/$argv`.
- `*build-mode*` guard around top-level side effects in `main.phel` — without
  it `composer build` would execute the app.

## Exporting to PHP

Mark a pure `core/` fn `{:export true}`, run `composer export` to regenerate
`src/PhelGenerated/`. Only export pure functions.

## Formatting & checks

`vendor/bin/phel format` auto-formats; `composer check` runs the fast gate
(format-check + lint + test). A pre-commit hook runs it automatically.
