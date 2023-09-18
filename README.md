# Развертывание проекта users-api

Эта инструкция позволит вам развернуть проект users-api на локальной машине.

## Предварительные требования

Убедитесь, что на вашем компьютере установлены следующие инструменты:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Шаг 1: Клонирование репозитория

Клонируйте данный репозиторий на свой компьютер:

```bash
git clone https://github.com/NikitaShamin/users-api.git
cd users-api
```

## Шаг 2: Установка зависимостей Symfony

Загрузите зависимые пакеты для работы сервиса

```bash
composer install
```

## Шаг 3: Создание и запуск контейнера с БД

Создайте и запустите контейнер с PostgreSQL

```bash
docker-compose build
docker-compose up -d
```

## Шаг 5: Применение миграций в БД

Создайте структуру базы данных в контейнере

```bash
docker-compose exec container_name php bin/console doctrine:migrations:migrate
```

## Шаг 6: Запуск приложения

Запустите приложение командой. Доступно по адресу localhost:8000.

```bash
symfony server:start
```