#!/bin/bash

# Docker Helper Script for Surat-In
# Usage: ./docker-helper.sh [command] [environment]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Default environment
ENV=${2:-development}

# Project root
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DOCKER_DIR="$PROJECT_ROOT/docker"

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

# Change to docker directory
cd "$DOCKER_DIR"

# Command functions
start() {
    print_info "Starting Surat-In ($ENV environment)..."

    if [ "$ENV" = "production" ]; then
        docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
    elif [ "$ENV" = "testing" ]; then
        docker-compose -f docker-compose.yml -f docker-compose.test.yml up -d
    else
        docker-compose up -d
    fi

    print_status "Services started successfully!"
    print_info "Access URLs:"
    echo "  - Application: http://localhost:8080"
    echo "  - phpMyAdmin: http://localhost:8081"
    echo "  - MailHog: http://localhost:8025"
}

stop() {
    print_info "Stopping Surat-In services..."

    if [ "$ENV" = "production" ]; then
        docker-compose -f docker-compose.yml -f docker-compose.prod.yml down
    elif [ "$ENV" = "testing" ]; then
        docker-compose -f docker-compose.yml -f docker-compose.test.yml down
    else
        docker-compose down
    fi

    print_status "Services stopped successfully!"
}

restart() {
    stop
    sleep 2
    start
}

build() {
    print_info "Building Docker images..."

    if [ "$ENV" = "production" ]; then
        docker-compose -f docker-compose.yml -f docker-compose.prod.yml build --no-cache
    else
        docker-compose build --no-cache
    fi

    print_status "Images built successfully!"
}

logs() {
    service=${3:-app}
    print_info "Showing logs for service: $service"

    if [ "$ENV" = "production" ]; then
        docker-compose -f docker-compose.yml -f docker-compose.prod.yml logs -f "$service"
    elif [ "$ENV" = "testing" ]; then
        docker-compose -f docker-compose.yml -f docker-compose.test.yml logs -f "$service"
    else
        docker-compose logs -f "$service"
    fi
}

status() {
    print_info "Container status:"
    if [ "$ENV" = "production" ]; then
        docker-compose -f docker-compose.yml -f docker-compose.prod.yml ps
    elif [ "$ENV" = "testing" ]; then
        docker-compose -f docker-compose.yml -f docker-compose.test.yml ps
    else
        docker-compose ps
    fi
}

shell() {
    service=${3:-app}
    print_info "Opening shell in container: $service"

    if [ "$ENV" = "production" ]; then
        docker-compose -f docker-compose.yml -f docker-compose.prod.yml exec "$service" bash
    else
        docker-compose exec "$service" bash
    fi
}

backup() {
    timestamp=$(date +%Y%m%d_%H%M%S)
    backup_dir="$PROJECT_ROOT/backups"

    mkdir -p "$backup_dir"

    print_info "Creating database backup..."
    docker-compose exec db mysqldump -u surat_user -p surat_in_db > "$backup_dir/db_backup_$timestamp.sql"

    print_info "Creating uploads backup..."
    docker run --rm -v surat-in_uploads:/data -v "$backup_dir":/backup alpine tar czf "/backup/uploads_backup_$timestamp.tar.gz" -C /data .

    print_status "Backup completed: $backup_dir"
}

restore() {
    backup_file=$3

    if [ -z "$backup_file" ]; then
        print_error "Please specify backup file to restore"
        echo "Usage: $0 restore <environment> <backup_file>"
        exit 1
    fi

    if [[ $backup_file == *db_backup* ]]; then
        print_info "Restoring database from: $backup_file"
        docker-compose exec -T db mysql -u surat_user -p surat_in_db < "$backup_file"
    elif [[ $backup_file == *uploads_backup* ]]; then
        print_info "Restoring uploads from: $backup_file"
        docker run --rm -v surat-in_uploads:/data -v "$PROJECT_ROOT/backups":/backup alpine tar xzf "/backup/$backup_file" -C /data
    else
        print_error "Unknown backup file type"
        exit 1
    fi

    print_status "Restore completed!"
}

cleanup() {
    print_warning "This will remove all containers, volumes, and images!"
    read -p "Are you sure? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        print_info "Cleaning up Docker resources..."
        docker-compose down -v --rmi all
        docker system prune -f
        print_status "Cleanup completed!"
    fi
}

# Main command handler
case "${1:-help}" in
    start)
        start
        ;;
    stop)
        stop
        ;;
    restart)
        restart
        ;;
    build)
        build
        ;;
    logs)
        logs "$@"
        ;;
    status)
        status
        ;;
    shell)
        shell "$@"
        ;;
    backup)
        backup
        ;;
    restore)
        restore "$@"
        ;;
    cleanup)
        cleanup
        ;;
    help|*)
        echo "Docker Helper Script for Surat-In"
        echo ""
        echo "Usage: $0 <command> [environment] [options]"
        echo ""
        echo "Commands:"
        echo "  start     Start services (default: development)"
        echo "  stop      Stop services"
        echo "  restart   Restart services"
        echo "  build     Build Docker images"
        echo "  logs      Show service logs (default: app)"
        echo "  status    Show container status"
        echo "  shell     Open shell in container (default: app)"
        echo "  backup    Create database and uploads backup"
        echo "  restore   Restore from backup file"
        echo "  cleanup   Remove all containers and volumes"
        echo ""
        echo "Environments:"
        echo "  development  Development environment (default)"
        echo "  production   Production environment"
        echo "  testing      Testing/CI environment"
        echo ""
        echo "Examples:"
        echo "  $0 start                    # Start development"
        echo "  $0 start production         # Start production"
        echo "  $0 logs db                  # Show database logs"
        echo "  $0 shell app                # Open app shell"
        echo "  $0 backup                   # Create backup"
        echo "  $0 restore development db_backup_20231217.sql"
        ;;
esac
