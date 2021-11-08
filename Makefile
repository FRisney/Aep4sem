deps:
	@cd ./app; composer install

init: deps up ips

up:
	@docker-compose up -d

down:
	@docker-compose down

ips:
	@echo -n API IP
	@docker inspect aep4sem-php-1 | grep 'IPAddress' | tail -n1 | sed -E 's/.+:\ "/\t/' | sed -E 's/".*//'
	@echo -n DB IP
	@docker inspect aep4sem-db-1  | grep 'IPAddress' | tail -n1 | sed -E 's/.+:\ "/\t/' | sed -E 's/".*//'
