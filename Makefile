up:
	docker compose up -d --build

down:
	docker compose down

install:
	docker compose exec php composer install

env:
	@if [ ! -f .env.dev ]; then \
		echo "Создаю .env.dev"; \
		touch .env.dev; \
	fi
	@if ! grep -q "^DATABASE_URL=" .env.dev; then \
		echo 'DATABASE_URL="postgresql://symfony:symfony@db:5432/symfony?serverVersion=16&charset=utf8"' >> .env.dev; \
		echo "Добавлен DATABASE_URL"; \
	else \
		sed -i 's|^DATABASE_URL=.*|DATABASE_URL="postgresql://symfony:symfony@db:5432/symfony?serverVersion=16\&charset=utf8"|' .env.dev; \
		echo "DATABASE_URL обновлён"; \
	fi

migrate: env
	docker compose exec php php bin/console doctrine:migrations:migrate --silent

test:
	docker compose exec php composer run-tests

init: up install migrate
