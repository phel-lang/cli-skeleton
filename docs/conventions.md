# Phel conventions and gotchas

Short notes for people and tools new to Phel. The language docs are in `vendor/phel-lang/phel-lang/docs/`, and `vendor/bin/phel doc <name>` prints the docs of any function.

## Namespaces

- Use dots, not backslashes: `cli-skeleton.core.adder` lives in `src/core/adder.phel`.
- Test namespaces use `tests` and end in `-test`: `cli-skeleton-tests.core.adder-test` lives in `tests/core/adder-test.phel`.
- The entry point is `cli-skeleton.main`. Its `*build-mode*` guard stops the app from running during build, REPL, and export.

## Naming

- Use `kebab-case` for functions, vars, and namespaces.
- End predicates with `?` (`loud?`) and side-effecting functions with `!` (`render!`).
- Use `defn-` and `def-` for private functions and vars.

## Docstrings

Put a string after the name. `(doc foo)` shows it in the REPL.

```phel
(defn adder
  "Sum every numeric argument. Pure."
  [& numbers]
  (apply + numbers))
```

## Keep side effects out of `core/`

IO, the clock, and randomness belong in `commands/` (see [architecture.md](architecture.md)). To test code that depends on the environment, read the value in the command and pass it in as an argument.

## Gotchas

- **Flags and composer.** Composer takes flags for itself. Write `composer dev -- greet alice --loud` so `--loud` reaches the app.
- **Negative numbers.** The console parser reads `-1` as an option. Write `add -- -1 2`. The same applies to `php out/main.php`.
- **Parsing integers.** `parse-long` clamps an out-of-range value to the largest integer. `parse-int` in `core/adder.phel` returns `nil` instead, so overflow stays visible.
- **`:coerce` on arguments.** It works on single values only. For an `:array` argument, convert in the command or validate in `core/`.
- **Generated files.** `src/PhelGenerated/` is created by `composer export` and is git-ignored.

## Error handling

Return `nil` for "no value" and let the caller pick a default: `(or (parse x) fallback)`. Throw only for a state the caller cannot handle. For input that users get wrong, return data that names the problem, as `sum-tokens` does, and let the command print it.

## Performance

Reach for these only on a measured hot path:

- Type hints skip dynamic dispatch on primitives: `(defn- ^int cell-at [pgrid ^int x ^int y] ...)`.
- An atom can memoize a pure result without a mutable global:
  ```phel
  (def- cache (atom {}))
  (defn lookup [k]
    (or (get @cache k)
        (let [v (expensive k)] (swap! cache assoc k v) v)))
  ```
- `php/array` is mutable and much faster than a vector for per-cell loops. Keep it local and convert at the boundary.
