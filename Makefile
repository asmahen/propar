.PHONY: init, db, start

init:
	composer install
	composer update
	npm install

db:
	php bin/console doctrine:database:drop --force
	php bin/console doctrine:database:create
	php bin/console doctrine:migration:migrate
	php bin/console doctrine:fixtures:load
	npm run dev

start:
	symfony server:start

stop:
	symfony server:stop

cache:
	symfony console cache:clear