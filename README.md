# Product Import Service

Сервис реализует импорт товаров из CSV-файла с сохранением в PostgreSQL.
Архитектура построена по принципам **DDD** и **Clean Architecture**.
Импорт легко расширяется для поддержки новых источников данных.

---

## Требования

* [Docker](https://www.docker.com/)
* [Make](https://www.gnu.org/software/make/) *(опционально, но рекомендуется для упрощения команд)*

---

## Установка и запуск с нуля

### 1. Клонирование репозитория

```bash
git clone git@github.com:bdaler/productImporter.git
cd productImporter
```

### 2. Быстрый старт

Для запуска проекта с чистого состояния выполните:

```bash
make init
```

### 3. Ручной запуск без Make
1. Поднять контейнеры:
```bash
docker compose up -d --build
```
2. Установить зависимости:
```bash
docker compose exec php composer install
```
2.1. Создать файл `.env.dev`, если он не был создан автоматически, и добавить туда строку подключения к базе:
```dotenv
DATABASE_URL="postgresql://symfony:symfony@db:5432/symfony?serverVersion=16&charset=utf8"
```
3. Выполнить миграции базы данных:
```bash
docker compose exec php php bin/console doctrine:migrations:migrate
```
---

## Тестирование

Проект использует **PHPUnit**.

* Запуск всех тестов:

```bash
make test
```

* Запуск конкретного теста:

```bash
docker compose exec php vendor/bin/phpunit tests/Component/Domain/ValueObject/ImportResultTest.php
```

---

## Импорт CSV

### Команда импорта

```bash
docker exec -it php php bin/console app:import-products path/to/file.csv
```

### Пример

```bash
docker exec -it php php bin/console app:import-products products.csv
```

### Формат CSV

```csv
code,name,price,quantity
A12346,Бумага,1253.25,10.0005
```

* **price** — указывается в рублях через точку.
* В базе хранится в копейках (целое число).

### Пример результата

```
Imported 1 products. Total sum: 1253250
```

---

## Расширяемость

Сервис построен так, чтобы легко добавлять новые источники данных для импорта.
Новые адаптеры можно подключать через соответствующие слои архитектуры, не меняя существующий код.
