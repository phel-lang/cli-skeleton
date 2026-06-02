# Phel conventions & gotchas

Short, opinionated notes for people (and AI agents) new to Phel. See the full
language guide under `vendor/phel-lang/phel-lang/docs/`.

## Namespaces

- Dots, not backslashes: `cli-skeleton.core.adder` (file `src/core/adder.phel`).
- Tests pluralize the root and add `-test`:
  `cli-skeleton-tests.core.adder-test` (file `tests/core/adder-test.phel`).
- The build entrypoint is `cli-skeleton.main`; the `*build-mode*` guard in
  `main.phel` stops it from running during build/REPL/export.

## Naming

- `kebab-case` for functions, vars, and namespaces.
- `name?` for predicates (`loud?`), `name!` for side-effecting fns (`render!`).
- `def-` / `defn-` for namespace-private vars and functions.

## Docstrings

Put a string right after the name; it shows up in the REPL via `(doc foo)`.

```phel
(defn adder
  "Sum every numeric argument. Pure."
  [& numbers]
  (apply + numbers))
```

## Keep side effects out of pure code

Anything non-deterministic — IO, the clock, randomness — belongs at the IO
boundary, never in `core/` (see [architecture.md](architecture.md)). A common
trick for testing side effects is to gate them behind an env var, e.g. a
command checks `(php/getenv "CLI_SKELETON_NO_COLOR")` so tests can disable color.

## Performance, when you actually need it

These are *not* needed for the sample code — reach for them only on a measured
hot path:

- **Type hints** skip dynamic dispatch on primitives:
  `(defn- ^int cell-at [pgrid ^int x ^int y] ...)`.
- **Atoms as caches** memoize pure results without a mutable global:
  ```phel
  (def- cache (atom {}))
  (defn lookup [k]
    (or (get @cache k)
        (let [v (expensive k)] (swap! cache assoc k v) v)))
  ```
- **`php/array`** is mutable and far faster than persistent vectors for
  per-cell read/write loops; keep it local and convert at the boundary.

## Error handling

Prefer returning `nil` for "no value" and letting the caller supply a default
(`(or (parse x) fallback)`) over throwing. Reserve exceptions for truly
exceptional cases.
