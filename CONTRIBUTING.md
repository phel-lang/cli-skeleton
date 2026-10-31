# Contributing

Thanks for helping. This repo is a template, so a change should keep it small and easy to read.

## Set up

```bash
git clone https://github.com/phel-lang/cli-skeleton.git
cd cli-skeleton
composer install
```

You need PHP 8.5 or newer and Composer. `composer install` also points Git at `.githooks/`, so the pre-commit hook runs the fast gate.

## Before you push

```bash
composer ci
```

It runs the format check, the linter, the tests, mutation testing on `core/`, the build, and the export. CI runs the same steps and then runs the built binary and the PHP example.

For a quick loop while you work, use `composer check` (format check, lint, tests) or `composer watch`.

## Where code goes

- `src/core/`: pure logic. No printing, prompting, clock, randomness, or exit codes.
- `src/commands/`: reads arguments, calls `core/`, prints, returns an exit code.
- `src/app.phel`: the list of commands. A command missing here fails `tests/app-test.phel`.

Read [docs/architecture.md](docs/architecture.md) before adding a command and [docs/conventions.md](docs/conventions.md) for naming and gotchas. `make new-command NAME=foo` creates the files and registers the command.

## Tests

- Test `core/` functions with plain data in and out. Do not use the CLI harness there.
- Give each command a smoke test through the real dispatcher.
- Fix a bug by writing the failing test first.

## Commits and pull requests

- Use conventional commit subjects that say what changed: `fix: reject non-integer input in add`. Types in use: `feat`, `fix`, `ref`, `docs`, `ci`, `build`, `chore`.
- Open one pull request per issue and link it with `Closes #123`.
- State how you checked each acceptance criterion. The PR template has a section for it.
- Keep unrelated cleanups out. Open a new issue for them.
- Do not rewrite history on a branch that is already pushed.

## Reporting bugs

Open an issue with your Phel and PHP versions and the exact commands that fail. Bugs in the compiler or in `phel` commands such as `lint` and `format` belong in [phel-lang/phel-lang](https://github.com/phel-lang/phel-lang/issues).

## AI tool config

Rules for AI coding tools are specs under `.agnostic-ai/`. Edit the specs, then run `agnostic-ai sync`. The generated files are git-ignored, and the pre-commit hook runs `agnostic-ai sync --check` when the CLI is installed.
