
start:
	docker-compose up -d --build
	composer install
	@echo "Givin cache & logs permissions..."
	@sudo chmod 777 -R var
	@echo "Done!"
	@echo "Run http://localhost:8080"

stop:
	@echo "Stop & remove containers..."
	@docker rm -vf elevators-webserver elevators-php-fpm
	@echo "Removing cache & logs..."
	@rm -rf var/
	@echo "Removing vendors dir..."
	@rm -rf vendor
	@echo "Done. Bye!"