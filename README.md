# Phel CLI Skeleton

A starting point for a command-line app in [Phel](https://phel-lang.org/), a functional Lisp that compiles to PHP. It ships two sample commands, `greet` and `add`, with tests, a command scaffolder, and a pre-commit gate.

## What you get

- A command dispatcher built on `phel\cli`, which wraps `symfony/console`.
- Two layers: `core/` holds pure logic, `commands/` handles input and output. See [docs/architecture.md](docs/architecture.md).
- Unit tests for the core, smoke tests for every command, and mutation testing for the core.
- `bin/new-command` creates a command with its tests and registers it.
- A pure function exported to PHP through a generated wrapper.
- CI that builds the standalone binary and runs it.

## Requirements

- PHP 8.5 or newer, and [Composer](https://getcomposer.org/).
- Or Docker: the repo includes a `Dockerfile` and a compose file (see [Docker](#docker)).

The skeleton requires phel-lang `^0.54`. Pin an exact version before you ship an app.

## Quick start

```bash
composer install       # also installs the pre-commit hook
composer dev greet alice
composer check         # format check, lint, tests
```

`make help` lists every task.

## Tasks

Each task is a composer script. The `Makefile` calls the same scripts and adds short aliases.

| Task | Command | Alias |
| --- | --- | --- |
| Run from sources | `composer dev greet alice` | `make d ARGS="greet alice"` |
| Run tests | `composer test` | `make t` |
| Format files | `composer format` | `make f` |
| Fast gate: format check, lint, tests | `composer check` | `make c` |
| Mutation-test `core/` | `composer mutate` | `make mu` |
| Full CI gate | `composer ci` | `make ci` |
| Build the standalone binary at `out/main.php` | `composer build` | `make b` |
| Regenerate the PHP wrappers | `composer export` | `make export` |
| Recompile on save | `composer watch` | `make w` |
| Open a REPL | `composer repl` | `make r` |
| Create a command | `make new-command NAME=foo` | `make nc NAME=foo` |

Composer reads flags itself, so put `--` before flags meant for the app:

```bash
composer dev -- greet alice --loud
php out/main.php add 1 2 3
php out/main.php add -- -1 2     # -- is also needed for negative numbers
```

## Layout

```
src/
  app.phel          registers every command
  main.phel         runs the app (skipped during build)
  core/             pure logic: greeting, adder
  commands/         input and output: greet, add
  PhelGenerated/    generated PHP wrappers (git-ignored)
tests/
  core/             unit tests, no CLI harness
  commands/         one smoke test per command
  app-test.phel     runs the real app
bin/new-command     command scaffolder
docs/               architecture and conventions
example/            calls an exported Phel function from PHP
```

## Add a command

```bash
make new-command NAME=say-hi
```

This creates `src/core/say-hi.phel`, `src/commands/say-hi.phel`, and a test for each. It also adds the command to `src/app.phel`. If that file has been reshaped so the edit is unsafe, the script prints the two lines to add by hand.

Put the logic in `core/` and keep the command to reading arguments, calling core, and printing. [docs/architecture.md](docs/architecture.md) explains why.

## Export to PHP

Mark a `core/` function with `{:export true}` (see `src/core/adder.phel`), then:

```bash
composer export
php example/using-exported-phel-function.php
```

## Docker

```bash
docker compose up -d --build
docker exec -ti phel_cli_skeleton bash
composer install
```

The container runs as a non-root user.

## AI tool config

The rules for AI coding tools live as specs in `.agnostic-ai/` and are turned into each tool's own files by [agnostic-ai](https://github.com/chemaclass/agnostic-ai). The generated files are git-ignored. After cloning, run `agnostic-ai sync` to create them, and edit the specs, never the output. Install the CLI first, from [its docs](https://agnostic-ai.org/docs/installation/).

`vendor/bin/phel agent-install --auto` adds Phel's own idiom skills.

## More

- [CONTRIBUTING.md](CONTRIBUTING.md): setup, the gate, commit and PR conventions
- [docs/architecture.md](docs/architecture.md): the `core/` and `commands/` rule
- [docs/conventions.md](docs/conventions.md): naming, idioms, and gotchas
- [Phel documentation](https://phel-lang.org/documentation/getting-started/)
- `vendor/phel-lang/phel-lang/docs/cli-reference.md`: every `phel` command
