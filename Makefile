.PHONY: phpcs phpstan codeception test install install-dev bundle docker-tag docker-login docker-push docker-build grumphp update

update:
	composer update

install:
	composer install --no-dev

install-dev:
	composer install

phpcs:
	./vendor/bin/phpcs --standard=./vendor/spryker/code-sniffer/Spryker/ruleset.xml ./src/FondOfSpryker

phpcbf:
	./vendor/bin/phpcbf --standard=./vendor/spryker/code-sniffer/Spryker/ruleset.xml ./src/FondOfSpryker

phpstan:
	./vendor/bin/phpstan analyse -l 4 ./src/FondOfSpryker

codeception:
	./vendor/bin/codecept run --coverage --coverage-xml --coverage-html

phpmd:
	./vendor/bin/phpmd ./src xml cleancode,codesize,controversial,design --exclude DandelionServiceProvider,Git

phpcpd:
	./vendor/bin/phpcpd ./src

grumphp: phpcs phpstan codeception phpmd phpcpd

test: install-dev phpcs phpstan codeception phpmd phpcpd
