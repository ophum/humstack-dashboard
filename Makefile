UID := $(shell id -u)
GID := $(shell id -g)
USER := $(UID):$(GID)
DOCKER_COMPOSE := user=$(USER) docker-compose

.PHONY: up
up:
	$(DOCKER_COMPOSE) up -d

.PHONY: down
down:
	$(DOCKER_COMPOSE) down

.PHONY: logs
logs:
	$(DOCKER_COMPOSE) logs -f

.PHONY: shPHP
shPHP:
	$(DOCKER_COMPOSE) run php bash
