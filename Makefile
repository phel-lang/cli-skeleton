.PHONY: help install d dev t test f format fc c check ci mu mutate w watch nc new-command b build export r repl doctor clean

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2}'

install: ## composer install
	composer install --no-interaction --no-ansi --no-progress

d: dev
dev: ## run CLI from sources (pass args: make dev ARGS="greet alice")
	composer dev -- $(ARGS)

nc: new-command
new-command: ## scaffold a new command across both layers (make new-command NAME=foo)
	@bin/new-command $(NAME)

t: test
test: ## run phel tests
	composer test

f: format
format: ## auto-format sources
	composer format

fc: ## format check only (CI-style, exits non-zero on drift)
	composer format-check

c: check
check: ## fast pre-commit gate: format-check + lint + test
	composer check

ci: ## full CI gate: format-check + lint + test + mutate + build + export
	composer ci

mu: mutate
mutate: ## mutation-test the pure core (fails below 90% MSI)
	composer mutate

b: build
build: ## build standalone PHP binary -> out/main.php
	composer build

export: ## regenerate PHP wrappers in src/PhelGenerated/
	composer export

w: watch
watch: ## recompile + reload changed namespaces on save
	composer watch

r: repl
repl: ## start phel REPL
	composer repl

doctor: ## verify environment + module health
	composer doctor

clean: ## drop build artifacts and caches
	rm -rf out .phel/cache
