#!/bin/bash

# Surat-In Docker Image Builder
# Usage: ./build-image.sh [tag] [registry]

set -e

# Default values
TAG=${1:-latest}
REGISTRY=${2:-surat-in}
IMAGE_NAME="${REGISTRY}:${TAG}"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Project root
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

print_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Docker is installed
check_docker() {
    if ! command -v docker &> /dev/null; then
        print_error "Docker is not installed. Please install Docker first."
        echo "Visit: https://docs.docker.com/get-docker/"
        exit 1
    fi

    print_info "Docker is installed: $(docker --version)"
}

# Build the image
build_image() {
    print_info "Building Docker image: $IMAGE_NAME"
    print_info "Project root: $PROJECT_ROOT"

    # Build the image
    docker build -t "$IMAGE_NAME" "$PROJECT_ROOT"

    if [ $? -eq 0 ]; then
        print_success "Docker image built successfully: $IMAGE_NAME"
    else
        print_error "Failed to build Docker image"
        exit 1
    fi
}

# Show image info
show_info() {
    print_info "Image information:"
    docker images "$IMAGE_NAME"

    print_info "Image size:"
    docker images "$IMAGE_NAME" --format "table {{.Repository}}\t{{.Tag}}\t{{.Size}}"
}

# Test the image
test_image() {
    print_info "Testing Docker image..."

    # Run container for testing
    CONTAINER_NAME="surat-in-test-${TAG}"
    TEST_PORT=8080

    # Stop and remove existing test container
    docker stop "$CONTAINER_NAME" 2>/dev/null || true
    docker rm "$CONTAINER_NAME" 2>/dev/null || true

    # Run test container
    print_info "Starting test container on port $TEST_PORT..."
    docker run -d \
        --name "$CONTAINER_NAME" \
        -p "$TEST_PORT:80" \
        "$IMAGE_NAME"

    if [ $? -eq 0 ]; then
        print_success "Test container started successfully"

        # Wait for container to be ready
        sleep 5

        # Test health check
        if curl -f http://localhost:"$TEST_PORT"/health >/dev/null 2>&1; then
            print_success "Health check passed"
        else
            print_warning "Health check failed - container may still be starting"
        fi

        print_info "Test URLs:"
        echo "  - Application: http://localhost:$TEST_PORT"
        echo "  - Health check: http://localhost:$TEST_PORT/health"

        print_info "To stop test container:"
        echo "  docker stop $CONTAINER_NAME && docker rm $CONTAINER_NAME"

    else
        print_error "Failed to start test container"
    fi
}

# Push to registry (optional)
push_image() {
    if [ -n "$REGISTRY" ] && [[ "$REGISTRY" == *"/"* ]]; then
        print_info "Pushing image to registry..."

        # Login to registry if needed
        if [[ "$REGISTRY" == "docker.io"* ]] || [[ "$REGISTRY" == *"/"* ]]; then
            echo "Please login to registry if required:"
            echo "  docker login $REGISTRY"
        fi

        docker push "$IMAGE_NAME"

        if [ $? -eq 0 ]; then
            print_success "Image pushed successfully to registry"
        else
            print_error "Failed to push image to registry"
        fi
    fi
}

# Save image as tar file
save_image() {
    TAR_FILE="${REGISTRY//\//-}-${TAG}.tar"
    print_info "Saving image as tar file: $TAR_FILE"

    docker save "$IMAGE_NAME" > "$TAR_FILE"

    if [ $? -eq 0 ]; then
        print_success "Image saved as: $TAR_FILE"
        print_info "File size: $(ls -lh "$TAR_FILE" | awk '{print $5}')"
        print_info "To load image: docker load < $TAR_FILE"
    else
        print_error "Failed to save image"
    fi
}

# Main execution
main() {
    echo "=== Surat-In Docker Image Builder ==="
    echo ""

    check_docker

    build_image
    show_info

    # Ask user what to do next
    echo ""
    echo "What would you like to do next?"
    echo "1) Test the image"
    echo "2) Push to registry"
    echo "3) Save as tar file"
    echo "4) All of the above"
    echo "5) Nothing (just build)"
    read -p "Choose option (1-5): " choice

    case $choice in
        1)
            test_image
            ;;
        2)
            push_image
            ;;
        3)
            save_image
            ;;
        4)
            test_image
            push_image
            save_image
            ;;
        5|"")
            print_info "Image built successfully. Use 'docker run $IMAGE_NAME' to start container."
            ;;
        *)
            print_error "Invalid option"
            ;;
    esac

    echo ""
    print_success "Docker image build process completed!"
    print_info "Image: $IMAGE_NAME"
}

# Show usage if help requested
if [ "$1" = "--help" ] || [ "$1" = "-h" ]; then
    echo "Surat-In Docker Image Builder"
    echo ""
    echo "Usage: $0 [tag] [registry]"
    echo ""
    echo "Arguments:"
    echo "  tag      Image tag (default: latest)"
    echo "  registry Registry and name (default: surat-in)"
    echo ""
    echo "Examples:"
    echo "  $0                    # Build surat-in:latest"
    echo "  $0 v1.0.0            # Build surat-in:v1.0.0"
    echo "  $0 latest myregistry/surat-in  # Build myregistry/surat-in:latest"
    echo ""
    echo "Interactive options after build:"
    echo "  1) Test the image"
    echo "  2) Push to registry"
    echo "  3) Save as tar file"
    echo "  4) All of the above"
    echo "  5) Nothing"
    exit 0
fi

# Run main function
main "$@"
