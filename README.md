# Starter Kit for DDD on Laravel

A robust starter kit for Laravel 13 (PHP 8.5) featuring Hexagonal Architecture, Domain-Driven Design (DDD), Command Query Responsibility Segregation (CQRS), and the Action-Domain-Responder pattern. This kit provides a clean and extendable base for complex applications, following modern design best practices.

> I’m pausing this project because I’m shifting focus to other technologies and priorities. PHP and Laravel remain great tools for building web applications, but I’m moving on to other stacks and won’t continue development here.

## Features

- **Laravel 13** with PHP 8.5 support
- **Hexagonal (Ports & Adapters) Architecture** for better modularity
- **Domain-Driven Design (DDD)**
- **CQRS** (Command and Query Responsibility Segregation)
- **Action-Domain-Responder** pattern for clear code responsibility separation
- **Doctrine ORM** integrated for powerful object-relational mapping

## Quick Start

### Prerequisites

- Docker & Docker Compose installed
- Bash (for cert script)
- Optional: g++ (if not available, an alternative method is provided)

### 1. Generate SSL Certificates

```
$ bash ./certgen.sh
```

### 2. Build and Start the Project

Use the provided `Makefile` to orchestrate containers:

```
$ make build
```

If you **don't have g++** installed, use these Docker commands manually:

```
$ docker network create app-network
$ docker compose --env-file ./src/.env \
    -f docker-compose.yml \
    -f vendor/docker-compose.traefik.yml \
    -f vendor/docker-compose.postgres.yml \
    -f vendor/docker-compose.redis.yml \
    -f vendor/docker-compose.rabbitmq.yml \
    -f vendor/docker-compose.queue.yml \
    -f vendor/docker-compose.schedule.yml \
    -f vendor/docker-compose.swagger.yml \
    -f vendor/docker-compose.trivy.yml \
    -f vendor/docker-compose.watchtower.yml \
    up --profile security up --build -d --remove-orphans
```

### 3. Install Composer Dependencies

```
$ docker compose exec app composer install --prefer-dist --optimize-autoloader
```

### 4. Prepare Environment File

```
$ docker compose exec app cp .env.example .env
```

### 5. Generate App Key

```
$ docker compose exec app php artisan key:generate
```

### 6. Generate JWT Secret

```
$ docker compose exec app php artisan jwt:secret
```

### 7. Run Database Migrations and Seeders

```
$ docker compose exec app php artisan migrate:fresh --seed
```

### 8. Helper Commands

For help with available Makefile commands:

```
$ make help
```

## Useful Makefile Commands

| Command             | Description                                      |
| ------------------- | ------------------------------------------------ |
| make build          | Build and start containers                       |
| make start          | Start containers                                 |
| make stop           | Stop and remove containers and volumes           |
| make restart        | Restart all containers                           |

## License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).
