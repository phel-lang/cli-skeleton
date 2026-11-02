---
description: "Use when editing src/ or tests/ to decide what belongs in core/ (pure) versus commands/ (IO boundary)."
globs: src/**,tests/**,*.phel
---

# IO boundaries

`commands/` requires `core/`, never the reverse. Rationale: [docs/architecture.md](../../docs/architecture.md).

## `src/core/`: pure logic

- Takes and returns plain data (maps, vectors, structs); same input, same output.
- Must not print, prompt, read the clock, call `rand`, mutate global atoms, `php/exit`, touch the filesystem or sleep.
- May do math, build new data and throw on invalid input.
- Test with deterministic unit tests in `tests/core/`, no CLI harness.

## `src/commands/`: the only IO layer

- Read args and options with `cli/arg`, `cli/opt`; prompt with `cli/ask`.
- Print with `cli/success`, `cli/warning`, `cli/error`; return an exit code.
- Keep handlers thin: parse input, call a `core/` function, emit the result.
- Test with handler smoke tests in `tests/commands/` that drive the real dispatcher via `cli/argv` and `cli/buffered-output`.

## Fix these

- A `core/` function that prints, prompts, or calls `php/microtime` or `php/rand`: move the effect into the command and pass the value in.
- A command computing inline: extract to `core/` and unit-test it.
- When commands stop being thin, split into `core/`, `glue/` (pure wiring) and `io/` (effects), with the same one-way rule.
