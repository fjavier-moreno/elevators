
start:
	docker-compose up -d --build
	docker exec -it elevators-php-fpm php /usr/local/bin/composer install
	@echo "Done!"
	@echo "Run http://localhost:8080"

stop:
	@echo "Stop & remove containers..."
	@docker rm -vf elevators-webserver elevators-php-fpm
	@echo "Removing cache & logs..."
	@rm -rf var/*
	@echo "Removing vendors dir..."
	@rm -rf vendor
	@echo "Done. Bye!"