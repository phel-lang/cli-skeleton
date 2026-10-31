# Architecture

One rule keeps a CLI app testable as it grows: pure logic never knows about input or output. Output code depends on pure logic, never the other way round.

```
commands/  requires  core/
  (IO)                (pure)
```

- **`core/`** is pure. The same input gives the same output, with no printing, prompting, clock, randomness, filesystem, or `php/exit`. It never requires `phel.cli`. Anything that needs those belongs in a command.
- **`commands/`** is the boundary. It reads arguments and options, prompts, prints, and returns an exit code. It delegates the real work to `core/`.
- **`app.phel`** lists the commands. **`main.phel`** runs the app, and skips that during a build so `composer build` does not start the CLI.

## Why

- **Testing.** Core tests pass literal data in and compare literal data out. No harness, no mocks. Commands get a smoke test through the real dispatcher.
- **Reuse.** Pure functions are the ones worth exporting to PHP with `{:export true}`, or moving to another project.
- **Debugging.** A bug is either in the logic, which is deterministic and easy to reproduce, or in the wiring, which shows at the boundary.

## Example: `add`

| Layer | Responsibility |
| --- | --- |
| `core/adder.phel` | `parse-int` reads one token. `sum-tokens` returns the total, the invalid tokens, or an overflow flag. |
| `commands/adder.phel` | Reads the `numbers` argument, calls `sum-tokens`, prints the result or the error, returns 0 or 1. |

The command makes no decision about what counts as a valid number or an overflow. `tests/core/adder-test.phel` covers those cases with plain data, and mutation testing (`composer mutate`) checks that those tests catch changes to the logic.

## Testing each layer

| Layer | Test | How |
| --- | --- | --- |
| `core/` | `tests/core/*-test.phel` | Call the function with data. |
| `commands/` | `tests/commands/*-test.phel` | Run one command through `cli/run` with `cli/buffered-output`. |
| `app.phel` | `tests/app-test.phel` | Run the real app and check every command is listed. |

Tests require `cli-skeleton.app`, never `cli-skeleton.main`. Loading `main` runs the CLI and calls `php/exit`, which ends the test process.

## When two layers are not enough

When commands stop being thin, split the boundary into `core/`, `glue/`, and `io/`: pure logic, pure wiring, and effects. The dependency still points one way. Start with two layers and split when a command grows past reading input and printing.
