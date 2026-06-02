# Architecture

This skeleton enforces one rule that keeps a CLI app testable as it grows:

> **Pure logic never knows about IO. IO depends on pure logic, never the reverse.**

## Two layers

```
commands/  ──requires──▶  core/
  (IO)                    (pure)
```

- **`core/`** — PURE: no IO, no clock, no randomness. Same input → same output,
  unit-testable directly. Never `(:require phel.cli ...)`, `php/exit`, the
  filesystem, or `rand`. If you need those, they belong in a command.
- **`commands/`** — the IO boundary: read args/opts, prompt, print, return an
  exit code. Stay thin — delegate the real work to `core/`.

### Why this matters

- **Testability.** `core/` is tested with literal data in / literal data out,
  no CLI harness, no mocks (`tests/core/*`). Commands get a thin smoke test
  through the real dispatcher (`tests/commands/*`).
- **Reuse.** Pure functions are the ones worth exporting to PHP
  (`{:export true}`), reusing across commands, or moving to another project.
- **Reasoning.** A bug is either in pure logic (deterministic, easy to repro)
  or in wiring (visible at the boundary). Rarely both.

## Worked example

`greet` splits cleanly:

| Layer                       | Responsibility                              |
| --------------------------- | ------------------------------------------- |
| `core/greeting.phel`        | `(build-greeting name loud?)` → string      |
| `commands/greet.phel`       | read `name`/`--loud`, ask if missing, print |

The command reads input and prints; the string itself is built by a pure
function that a test can call with `(build-greeting "alice" true)`.

## Scaling beyond two layers

When commands stop being thin, split the boundary into `core/ → glue/ → io/`
(pure logic → pure wiring → effects) — same one-way rule, finer-grained. This
is the shape larger Phel projects use; start with two layers and promote later.
