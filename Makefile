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

start:
	symfony server:start
	npm run dev

stop:
	symfony server:stop

cache:
	symfony console cache:clear