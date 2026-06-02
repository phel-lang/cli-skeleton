# Architecture

This skeleton enforces one rule that keeps a CLI app testable as it grows:

> **Pure logic never knows about IO. IO depends on pure logic, never the reverse.**

## Two layers

```
src/
├── core/        ; PURE: no IO, no side effects, no randomness, no clock.
│                ;       Same input -> same output. Unit-test directly.
└── commands/    ; IO BOUNDARY: read args/opts, prompt, print, exit code.
                 ;              Stay thin — delegate the real work to core.
```

Dependency direction is one-way:

```
commands/  ──requires──▶  core/
   (IO)                   (pure)
```

`core/` must never `(:require phel.cli ...)` or call `php/exit`, touch the
filesystem, read the clock, or generate randomness. If you need any of those,
it belongs in a command (or a dedicated IO namespace — see *Scaling* below).

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

For a bigger app, split the boundary in two — this is the `core / glue / io`
shape used by larger Phel projects:

```
core/   ; pure logic
glue/   ; pure wiring: compose core, translate input → intent
io/     ; the only place with effects: render!, read!, exit
```

Same rule, finer-grained: `io/ → glue/ → core/`, never reverse. Start with two
layers; promote to three when commands stop being thin.
