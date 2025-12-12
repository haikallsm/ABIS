#!/bin/bash

# ABIS Setup Script
# Otomatis setup project ABIS dari awal

set -e

echo "🚀 ABIS - Aplikasi Desa Digital Setup Script"
echo "=============================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
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

# Check prerequisites
check_prerequisites() {
    print_status "Checking prerequisites..."

    # Check PHP
    if ! command -v php &> /dev/null; then
        print_error "PHP is not installed. Please install PHP 8.1+ first."
        exit 1
    fi

    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    if [[ $(php -r "echo version_compare('$PHP_VERSION', '8.1', '>=');") != "1" ]]; then
        print_error "PHP version $PHP_VERSION is not supported. Please upgrade to PHP 8.1+"
        exit 1
    fi
    print_success "PHP $PHP_VERSION ✓"

    # Check Node.js
    if ! command -v node &> /dev/null; then
        print_error "Node.js is not installed. Please install Node.js 16+ first."
        exit 1
    fi

    NODE_VERSION=$(node -r "console.log(process.version.slice(1));")
    print_success "Node.js $NODE_VERSION ✓"

    # Check npm
    if ! command -v npm &> /dev/null; then
        print_error "npm is not installed."
        exit 1
    fi
    print_success "npm $(npm -v) ✓"

    # Check MySQL
    if ! command -v mysql &> /dev/null; then
        print_error "MySQL client is not installed."
        exit 1
    fi
    print_success "MySQL client ✓"
}

# Setup database
setup_database() {
    print_status "Setting up database..."

    # Database configuration
    DB_HOST="localhost"
    DB_NAME="abis_desa_digital"
    DB_USER="root"
    DB_PASS=""

    # Test MySQL connection
    if ! mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e "SELECT 1;" &> /dev/null; then
        print_error "Cannot connect to MySQL. Please check your MySQL configuration."
        exit 1
    fi

    # Create database
    mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e "
        CREATE DATABASE IF NOT EXISTS $DB_NAME
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_unicode_ci;
    "

    print_success "Database '$DB_NAME' created ✓"

    # Import schema
    if [ ! -f "database/schema.sql" ]; then
        print_error "Schema file not found: database/schema.sql"
        exit 1
    fi

    mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < database/schema.sql
    print_success "Database schema imported ✓"

    # Verify setup
    TABLES_COUNT=$(mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW TABLES;" | wc -l)
    TABLES_COUNT=$((TABLES_COUNT - 1)) # Subtract header row

    if [ "$TABLES_COUNT" -ge 4 ]; then
        print_success "Database setup completed! $TABLES_COUNT tables created."
    else
        print_error "Database setup failed. Only $TABLES_COUNT tables created."
        exit 1
    fi
}

# Install dependencies
install_dependencies() {
    print_status "Installing dependencies..."

    # Install npm packages
    if [ -f "package.json" ]; then
        npm install
        print_success "npm dependencies installed ✓"

        # Build CSS
        npm run build
        print_success "CSS built for production ✓"
    else
        print_error "package.json not found"
        exit 1
    fi
}

# Setup environment
setup_environment() {
    print_status "Setting up environment..."

    # Create necessary directories
    mkdir -p public/uploads
    mkdir -p public/assets/css
    mkdir -p public/assets/js

    # Set permissions
    chmod 755 public/uploads 2>/dev/null || true
    chmod 755 public/assets/css 2>/dev/null || true
    chmod 755 public/assets/js 2>/dev/null || true

    print_success "Directories and permissions set ✓"

    # Create .env file if it doesn't exist
    if [ ! -f ".env" ]; then
        cat > .env << EOF
# ABIS Environment Configuration
APP_ENV=development
APP_DEBUG=true

# Database Configuration
DB_HOST=localhost
DB_NAME=abis_desa_digital
DB_USER=root
DB_PASS=

# Application Configuration
BASE_URL=http://localhost:8000
APP_NAME=ABIS - Aplikasi Desa Digital

# File Upload
MAX_FILE_SIZE=5242880
ALLOWED_FILE_TYPES=pdf,doc,docx

# Telegram Bot (optional)
TELEGRAM_BOT_TOKEN=
TELEGRAM_CHAT_ID=
EOF
        print_success ".env file created ✓"
    else
        print_warning ".env file already exists"
    fi
}

# Test application
test_application() {
    print_status "Testing application..."

    # Test PHP syntax
    if find . -name "*.php" -type f | head -5 | xargs -I {} php -l {} 2>/dev/null; then
        print_success "PHP syntax check passed ✓"
    else
        print_warning "Some PHP files have syntax errors"
    fi

    # Test database connection
    php -r "
    try {
        \$pdo = new PDO('mysql:host=localhost;dbname=abis_desa_digital', 'root', '');
        echo 'Database connection: SUCCESS\n';
        \$tables = \$pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
        echo 'Tables found: ' . count(\$tables) . '\n';
    } catch(Exception \$e) {
        echo 'Database connection: FAILED - ' . \$e->getMessage() . '\n';
        exit(1);
    }
    "

    if [ $? -eq 0 ]; then
        print_success "Database connection test passed ✓"
    else
        print_error "Database connection test failed"
        exit 1
    fi
}

# Create startup script
create_startup_script() {
    print_status "Creating startup script..."

    cat > start.sh << 'EOF'
#!/bin/bash

# ABIS Development Startup Script

echo "🚀 Starting ABIS Development Environment"

# Function to cleanup background processes
cleanup() {
    echo ""
    echo "🛑 Stopping servers..."
    kill $(jobs -p) 2>/dev/null || true
    exit
}

# Set trap for cleanup
trap cleanup SIGINT SIGTERM

# Start TailwindCSS in background
echo "🎨 Starting TailwindCSS..."
npm run dev &
TAILWIND_PID=$!

# Wait a moment for CSS to build
sleep 3

# Start PHP server
echo "🐘 Starting PHP Server..."
php -S localhost:8000 index.php &
PHP_PID=$!

echo ""
echo "✅ ABIS Development Server Started!"
echo "🌐 Frontend: http://localhost:8000"
echo "🎨 CSS Watch: Running in background"
echo ""
echo "📝 Press Ctrl+C to stop all servers"

# Wait for processes
wait
EOF

    chmod +x start.sh
    print_success "Startup script created: ./start.sh ✓"
}

# Main execution
main() {
    echo ""

    check_prerequisites
    echo ""

    setup_database
    echo ""

    install_dependencies
    echo ""

    setup_environment
    echo ""

    test_application
    echo ""

    create_startup_script
    echo ""

    print_success "🎉 ABIS Setup Completed Successfully!"
    echo ""
    echo "📋 Next Steps:"
    echo "   1. Start development: ./start.sh"
    echo "   2. Or manually:"
    echo "      - Terminal 1: npm run dev"
    echo "      - Terminal 2: php -S localhost:8000 index.php"
    echo "   3. Open browser: http://localhost:8000"
    echo ""
    echo "👤 Default Admin Account:"
    echo "   - Username: admin"
    echo "   - Password: password"
    echo "   - Email: admin@abisdesa.id"
    echo ""
    echo "📚 Documentation: SETUP_GUIDE.md"
    echo "🐛 Issues? Check: SETUP_GUIDE.md#troubleshooting"
}

# Run main function
main "$@"
