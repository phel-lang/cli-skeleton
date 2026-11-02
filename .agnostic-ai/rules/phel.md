---
description: "Use when writing Phel source or tests (naming, namespaces, docstrings, exports)."
globs: src/**,tests/**,*.phel
---

# Phel conventions

More: [docs/conventions.md](../../docs/conventions.md).

- kebab-case names (`build-greeting`). `defn-` / `def-` for private. `?` suffix for predicates (`loud?`), `!` for side-effecting functions.
- Namespaces match the path with a dot: `cli-skeleton.core.adder`, `cli-skeleton.commands.greet`. Never a backslash.
- Test namespace: `cli-skeleton-tests.<layer>.<name>-test` (plural `tests`; `-test` on file and namespace).
- Public `defn` has a docstring (shown by `(doc foo)`). Private `defn-` only when the behavior is subtle.
- `defstruct` for data types, not PHP classes. `->` threads first, `->>` last. `for` builds sequences, `doseq` does side effects.
- Read CLI args with `(cli/argv *argv*)`, not `php/$argv`. `*argv*` holds user args without the program name.
- Guard top-level side effects in `main.phel` with `*build-mode*`, or `composer build` runs the app.
- Export to PHP: mark a pure `core/` function `{:export true}` and run `composer export` to regenerate `src/PhelGenerated/`. Export pure functions only.
- `vendor/bin/phel format` formats. `composer check` (format check, lint, tests) runs in the pre-commit hook.
