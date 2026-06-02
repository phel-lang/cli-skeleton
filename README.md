# Phel CLI Skeleton

[Phel](https://phel-lang.org/) is a functional Lisp that compiles to PHP. This
is a minimal, opinionated starting point for a real **CLI application** with Phel.

It ships:

- A data-driven dispatcher on [`phel\cli`](https://phel-lang.org/) (a
  `symfony/console` wrapper) with sample `greet` + `add` subcommands.
- A pure **`core/` ÷ IO `commands/`** layering that stays testable as it grows
  ([docs/architecture.md](docs/architecture.md)).
- A pure function exported to PHP via an auto-generated wrapper.
- Fast inner loop: pre-commit gate, command scaffolder, and watch mode.

## Requirements

- PHP **>= 8.4** ([phpbrew](https://github.com/phpbrew/phpbrew) on Linux,
  [shivammathur/homebrew-php](https://github.com/shivammathur/homebrew-php) on macOS)
  and [Composer](https://getcomposer.org/) — or use the bundled
  `build/Dockerfile` + `docker-compose.yml`.

> Tracks Phel's **`dev-main`** branch to showcase the latest idioms. Pin
> `phel-lang/phel-lang` to a tagged release for a production app.

## Getting started

```bash
composer install      # also installs the .githooks pre-commit gate
make help             # list every dev task
```

## Commands

Every task is both a `make <target>` (with short aliases) and a
`composer <script>`:

| Task                        | Command                       |
| --------------------------- | ----------------------------- |
| Run from sources            | `composer dev [args]`         |
| Build standalone PHP        | `composer build` → `out/main.php` |
| Run tests                   | `composer test`               |
| Format                      | `composer format`             |
| Watch (reload on save)      | `composer watch`              |
| REPL                        | `composer repl`               |
| Local gate (format+lint+test) | `composer check`            |
| Scaffold a command          | `make new-command NAME=foo`   |

```bash
composer dev greet alice              # positional args forward as-is
composer dev -- greet alice --loud    # put -- before flags (composer convention)
php out/main.php add 1 2 3             # run the built binary
```

## Project layout

```
src/
├── main.phel        ; wires the Application + dispatches
├── core/            ; PURE logic — no IO (greeting, adder; adder exported to PHP)
├── commands/        ; IO boundary: parse args, print, exit (greet, add)
└── PhelGenerated/   ; auto-generated PHP wrappers
tests/
├── core/            ; pure unit tests (no CLI harness)
└── commands/        ; CLI handler smoke tests
docs/                ; architecture.md (layering), conventions.md (idioms)
bin/new-command      ; scaffold a command across both layers
example/             ; call an exported Phel fn from PHP
phel-config.php      ; build / export / format config
.githooks/pre-commit ; auto-installed gate (composer check)
```

## Adding a command

```bash
make new-command NAME=foo     # generates core + command + both tests
```

Then fill in the logic and **register it in `src/main.phel`** — add the
`:require` and put `foo-command` in the `:commands` vector (the scaffolder
prints the exact lines). Keep commands thin; put real logic in `core/`. See
[docs/architecture.md](docs/architecture.md).

## Exporting Phel to PHP

Mark a `core/` function `{:export true}` (see `src/core/adder.phel`), then:

```bash
composer export    # regenerates src/PhelGenerated/**
php example/using-exported-phel-function.php
```

## AI agent config (optional)

Conventions are authored once as agnostic specs under `.agnostic-ai/rules/` and
transpiled per tool with [agnostic-ai](https://github.com/chemaclass/agnostic-ai)
(`agnostic-ai sync`). The emitted `.claude/`, `AGENTS.md` are git-ignored — edit
the specs, not the output. `vendor/bin/phel agent-install --auto` adds Phel's
built-in idiom skills.

## Docker

```bash
docker compose up -d --build
docker exec -ti -u dev phel_cli_skeleton bash
composer install
```

## More

- [docs/architecture.md](docs/architecture.md) — the core/commands layering rule
- [docs/conventions.md](docs/conventions.md) — Phel idioms, naming, gotchas
- Phel docs: <https://phel-lang.org/documentation/getting-started/>
- `phel\cli` guide: `vendor/phel-lang/phel-lang/docs/cli-guide.md`
