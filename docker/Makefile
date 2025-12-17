# Makefile for Surat-In Docker Development

.PHONY: help start stop restart build logs status shell backup restore clean dev prod test

# Default target
help: ## Show this help message
	@echo "Surat-In Docker Development Commands"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  %-15s %s\n", $$1, $$2}'

# Development Environment
dev: ## Start development environment
	./docker-helper.sh start development

dev-build: ## Build and start development environment
	./docker-helper.sh build development
	./docker-helper.sh start development

dev-stop: ## Stop development environment
	./docker-helper.sh stop development

dev-logs: ## Show development logs
	./docker-helper.sh logs development

dev-shell: ## Open shell in development app container
	./docker-helper.sh shell development app

# Production Environment
prod: ## Start production environment
	./docker-helper.sh start production

prod-build: ## Build and start production environment
	./docker-helper.sh build production
	./docker-helper.sh start production

prod-stop: ## Stop production environment
	./docker-helper.sh stop production

prod-logs: ## Show production logs
	./docker-helper.sh logs production

# Testing Environment
test: ## Start testing environment
	./docker-helper.sh start testing

test-build: ## Build and start testing environment
	./docker-helper.sh build testing
	./docker-helper.sh start testing

test-stop: ## Stop testing environment
	./docker-helper.sh stop testing

# General Commands
build: ## Build all environments
	@echo "Building development environment..."
	./docker-helper.sh build development
	@echo "Building production environment..."
	./docker-helper.sh build production

logs: ## Show all service logs
	./docker-helper.sh logs development

status: ## Show container status
	./docker-helper.sh status development

shell: ## Open shell in app container
	./docker-helper.sh shell development app

shell-db: ## Open shell in database container
	./docker-helper.sh shell development db

backup: ## Create backup of database and uploads
	./docker-helper.sh backup development

restore: ## Restore from backup (usage: make restore FILE=backup.sql)
	@if [ -z "$(FILE)" ]; then \
		echo "Usage: make restore FILE=backup_file"; \
		exit 1; \
	fi
	./docker-helper.sh restore development $(FILE)

clean: ## Remove all containers, volumes, and images
	./docker-helper.sh cleanup development

# Utility Commands
install: ## Initial setup - build and start development
	@echo "Setting up Surat-In development environment..."
	./docker-helper.sh build development
	./docker-helper.sh start development
	@echo ""
	@echo "Setup complete! Access your application at:"
	@echo "  - Application: http://localhost:8080"
	@echo "  - phpMyAdmin: http://localhost:8081"
	@echo "  - MailHog: http://localhost:8025"

update: ## Update application and restart containers
	@echo "Updating application..."
	git pull
	./docker-helper.sh build development
	./docker-helper.sh restart development

migrate: ## Run database migrations
	./docker-helper.sh shell development app
	# Inside container: php artisan migrate (if using Laravel)
	# Or run custom migration scripts

test: ## Run application tests
	./docker-helper.sh shell development app
	# Inside container: run your test commands

deploy: ## Deploy to production (configure your server first)
	@echo "Deploying to production..."
	@echo "Make sure to:"
	@echo "1. Configure your production server"
	@echo "2. Set up environment variables"
	@echo "3. Configure reverse proxy"
	@echo "4. Set up SSL certificates"
	./docker-helper.sh build production
	./docker-helper.sh start production

# Development shortcuts
up: dev ## Alias for dev
down: dev-stop ## Alias for dev-stop
restart: ## Restart all services
	./docker-helper.sh restart development
