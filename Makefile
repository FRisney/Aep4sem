deps:
	cd app/
	composer install

init:
	docker-compose up -d

down:
	docker-compose down
