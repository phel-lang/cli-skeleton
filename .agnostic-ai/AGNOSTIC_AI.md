# cli-skeleton

A Phel CLI skeleton (PHP 8.5, phel-lang `^0.54`). `src/core/` is pure logic, `src/commands/` is the input and output boundary, and `src/app.phel` lists the commands. Read `docs/architecture.md` before adding code and `CONTRIBUTING.md` before opening a PR.

## Commands

- `composer ci`: the full gate (format check, lint, tests, mutation, build, export). Run it once before pushing.
- `composer check`: the fast gate used by the pre-commit hook.
- `composer dev -- greet alice --loud`: run from sources. Put `--` before flags.
- `composer mutate`: mutation-test `src/core/`; it fails below 90%.
- `make new-command NAME=foo`: scaffold a command, its tests, and its registration in `src/app.phel`.

## Rules of the codebase

- Tests require `cli-skeleton.app`, never `cli-skeleton.main`.
- Parse integers with `parse-int` in `core/adder.phel`, not `parse-long`, which clamps overflow.
- Commit subjects are conventional (`fix:`, `feat:`, `ref:`, `docs:`, `ci:`, `chore:`).
- Edit specs under `.agnostic-ai/`, then run `agnostic-ai sync`; never edit generated files.
