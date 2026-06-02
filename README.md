# Phel CLI Skeleton

[Phel](https://phel-lang.org/) is a functional Lisp that compiles to PHP. This
repository is a minimal, opinionated starting point for building a real **CLI
application** with Phel.

It ships:

- A data-driven command dispatcher built on top of [`phel\cli`](https://phel-lang.org/)
  (a thin wrapper over `symfony/console`) with two sample subcommands:
  `greet` and `add`.
- A **pure `core/` ÷ IO `commands/` layering** that keeps logic testable as the
  app grows (see [docs/architecture.md](docs/architecture.md)).
- An exportable pure function (`core/adder.phel`) consumed from PHP via the
  auto-generated `PhelGenerated\CliSkeleton\Core\Adder` class.
- Tests that exercise both pure logic (no harness) and the CLI handler boundary
  using `phel\cli`'s built-in test helpers.
- A pre-commit hook (auto-installed by Composer) that runs the CI gate locally.

## Requirements

- PHP **>= 8.4** ([phpbrew](https://github.com/phpbrew/phpbrew) on Linux,
  [shivammathur/homebrew-php](https://github.com/shivammathur/homebrew-php) on macOS)
- [Composer](https://getcomposer.org/)

> A `build/Dockerfile` and `docker-compose.yml` are included if you'd rather not
> install PHP locally.

> This skeleton tracks Phel's **`dev-main`** branch to showcase the latest
> idioms. Pin `phel-lang/phel-lang` to a tagged release in `composer.json` for a
> production app.

## Getting started

```bash
git clone <this repo>
cd cli-skeleton
composer install      # also installs the .githooks pre-commit gate
make help             # discover every dev task
```

> All dev tasks are available both as `make <target>` (with short aliases like
> `make t`/`make c`) and `composer <script>`. `make help` lists them.

### Run the CLI from sources

```bash
composer dev                          # invoke default command (greet)
composer dev greet alice              # positional args forward as-is
composer dev -- greet alice --loud    # use -- before option flags (composer convention)
vendor/bin/phel run cli-skeleton.main add 1 2 3
```

### Compile a standalone PHP code

```bash
composer build                        # → out/main.php
php out/main.php greet alice
php out/main.php add 1 2 3
```

### Run the tests

```bash
composer test
```

### Format

```bash
composer format
```

### Watch (recompile + reload on save)

```bash
composer watch        # or: make watch
```

### REPL

```bash
composer repl
```

### AI agent config (optional)

Project conventions for AI assistants are authored **once** as agnostic specs
under `.agnostic-ai/` (see `rules/io-boundaries.md`, `rules/phel.md`) and
transpiled to each tool's native location with
[agnostic-ai](https://github.com/chemaclass/agnostic-ai):

```bash
agnostic-ai sync          # regenerate .claude/, AGENTS.md, … from the specs
agnostic-ai sync --check  # CI/pre-commit: fail on drift
```

The emitted files (`.claude/`, `AGENTS.md`) are git-ignored and disposable —
**edit the specs, not the output.** The pre-commit hook runs `sync --check`
when the tool is installed.

You can also install Phel's built-in idiom skills for your assistant:

```bash
vendor/bin/phel agent-install --auto
```

## Project layout

```
src/
├── main.phel                         ; wires the Application + dispatches
├── core/                             ; PURE logic — no IO (see docs/architecture.md)
│   ├── greeting.phel                 ; (build-greeting name loud?)
│   └── adder.phel                    ; pure logic, exported to PHP
├── commands/                         ; IO boundary: parse args, print, exit
│   ├── greet.phel                    ; subcommand: greet
│   └── adder.phel                    ; subcommand: add
└── PhelGenerated/                    ; auto-generated PHP wrappers
tests/
├── core/                             ; pure unit tests (no CLI harness)
└── commands/                         ; CLI handler smoke tests
example/
└── using-exported-phel-function.php  ; call a Phel fn from PHP
docs/
├── architecture.md                   ; the core/commands layering rule
└── conventions.md                    ; Phel idioms, naming, gotchas
bin/new-command                       ; scaffold a command across both layers
phel-config.php                       ; build / export / format config
.githooks/pre-commit                  ; auto-installed CI gate (composer check)
```

### Adding a new command

Scaffold all four files (pure core + IO command + both tests) at once:

```bash
make new-command NAME=foo     # or: bin/new-command foo
```

Then finish wiring it:

1. Fill in the pure logic in `src/core/foo.phel` (and its test in
   `tests/core/foo-test.phel` — literal data in/out, no harness).
2. Flesh out the **command** `src/commands/foo.phel` — keep it thin: read
   args/opts, call your `core` fn, print the result. The `phel\cli` spec is
   documented at `vendor/phel-lang/phel-lang/docs/cli-guide.md`.
3. **Register it** in `src/main.phel`: add the `:require` and put
   `foo-command` in the `:commands` vector (the scaffolder prints the exact
   lines to paste).

See [docs/architecture.md](docs/architecture.md) for why logic and IO are split.

### Exporting Phel functions to PHP

Mark a function with `{:export true}` (see `src/core/adder.phel`),
then run:

```bash
composer export    # regenerates src/PhelGenerated/**
php example/using-exported-phel-function.php
```

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
