#!/bin/bash

# ABIS Test Script
# Menjalankan berbagai test untuk memastikan aplikasi berjalan dengan baik

set -e

echo "🧪 ABIS - Test Suite"
echo "==================="

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() {
    echo -e "${BLUE}[TEST]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[PASS]${NC} $1"
}

print_fail() {
    echo -e "${RED}[FAIL]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

# Test PHP syntax
test_php_syntax() {
    print_status "Testing PHP syntax..."

    local php_files=$(find . -name "*.php" -type f | grep -v vendor/ | grep -v node_modules/)
    local errors=0
    local total=0

    for file in $php_files; do
        total=$((total + 1))
        if php -l "$file" > /dev/null 2>&1; then
            echo -n "."
        else
            errors=$((errors + 1))
            print_fail "Syntax error in $file"
        fi
    done

    echo ""
    if [ $errors -eq 0 ]; then
        print_success "PHP syntax: $total files OK"
    else
        print_fail "PHP syntax: $errors/$total files have errors"
        return 1
    fi
}

# Test database connection
test_database() {
    print_status "Testing database connection..."

    php -r "
    try {
        \$pdo = new PDO('mysql:host=localhost;dbname=abis_desa_digital', 'root', '');
        echo 'Database connection: SUCCESS\n';

        \$tables = \$pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
        echo 'Tables found: ' . count(\$tables) . '\n';

        \$users = \$pdo->query('SELECT COUNT(*) as count FROM users')->fetch()['count'];
        echo 'Users count: ' . \$users . '\n';

        \$admin = \$pdo->query('SELECT username FROM users WHERE role = \"admin\" LIMIT 1')->fetch();
        if (\$admin) {
            echo 'Admin user: ' . \$admin['username'] . '\n';
        }

    } catch(Exception \$e) {
        echo 'FAILED: ' . \$e->getMessage() . '\n';
        exit(1);
    }
    "

    if [ $? -eq 0 ]; then
        print_success "Database connection OK"
    else
        print_fail "Database connection FAILED"
        return 1
    fi
}

# Test application routes
test_routes() {
    print_status "Testing application routes..."

    # Test homepage
    if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/ | grep -q "200"; then
        print_success "Homepage (/) - 200 OK"
    else
        print_fail "Homepage (/) - FAILED"
        return 1
    fi

    # Test login page
    if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/auth/login | grep -q "200"; then
        print_success "Login page (/auth/login) - 200 OK"
    else
        print_fail "Login page (/auth/login) - FAILED"
        return 1
    fi

    # Test admin dashboard (should redirect if not logged in)
    status=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/admin/dashboard)
    if [ "$status" = "302" ] || [ "$status" = "200" ]; then
        print_success "Admin dashboard (/admin/dashboard) - $status (expected)"
    else
        print_fail "Admin dashboard (/admin/dashboard) - FAILED ($status)"
        return 1
    fi
}

# Test file permissions
test_permissions() {
    print_status "Testing file permissions..."

    local issues=0

    # Check if uploads directory is writable
    if [ -w "public/uploads" ]; then
        print_success "Uploads directory writable"
    else
        print_fail "Uploads directory not writable"
        issues=$((issues + 1))
    fi

    # Check if CSS directory is writable
    if [ -w "public/assets/css" ]; then
        print_success "CSS directory writable"
    else
        print_fail "CSS directory not writable"
        issues=$((issues + 1))
    fi

    # Check if JS directory is writable
    if [ -w "public/assets/js" ]; then
        print_success "JS directory writable"
        issues=$((issues + 1))
    fi

    return $issues
}

# Test dependencies
test_dependencies() {
    print_status "Testing dependencies..."

    # Check Node.js
    if command -v node &> /dev/null; then
        print_success "Node.js available: $(node --version)"
    else
        print_fail "Node.js not found"
        return 1
    fi

    # Check npm
    if command -v npm &> /dev/null; then
        print_success "npm available: $(npm --version)"
    else
        print_fail "npm not found"
        return 1
    fi

    # Check if node_modules exists
    if [ -d "node_modules" ]; then
        print_success "node_modules directory exists"
    else
        print_fail "node_modules directory missing"
        return 1
    fi

    # Check if TailwindCSS is installed
    if npx tailwindcss --version &> /dev/null; then
        print_success "TailwindCSS available: $(npx tailwindcss --version)"
    else
        print_fail "TailwindCSS not available"
        return 1
    fi

    # Check if CSS is built
    if [ -f "public/assets/css/style.css" ]; then
        css_size=$(stat -f%z "public/assets/css/style.css" 2>/dev/null || stat -c%s "public/assets/css/style.css" 2>/dev/null || echo "0")
        if [ "$css_size" -gt 1000 ]; then
            print_success "CSS file built ($css_size bytes)"
        else
            print_warning "CSS file seems too small ($css_size bytes)"
        fi
    else
        print_fail "CSS file not found"
        return 1
    fi
}

# Test environment configuration
test_environment() {
    print_status "Testing environment configuration..."

    # Check if .env exists
    if [ -f ".env" ]; then
        print_success ".env file exists"
    else
        print_warning ".env file missing (using defaults)"
    fi

    # Check config files
    config_files=("config/database.php" "config/constants.php" "config/session.php" "config/config.php")
    for config in "${config_files[@]}"; do
        if [ -f "$config" ]; then
            print_success "Config file exists: $config"
        else
            print_fail "Config file missing: $config"
            return 1
        fi
    done
}

# Run all tests
main() {
    local failed_tests=0

    echo ""

    # Run individual tests
    test_php_syntax || failed_tests=$((failed_tests + 1))
    echo ""

    test_database || failed_tests=$((failed_tests + 1))
    echo ""

    test_dependencies || failed_tests=$((failed_tests + 1))
    echo ""

    test_environment || failed_tests=$((failed_tests + 1))
    echo ""

    test_permissions || failed_tests=$((failed_tests + 1))
    echo ""

    # Only test routes if servers are running
    if pgrep -f "php -S localhost:8000" > /dev/null; then
        test_routes || failed_tests=$((failed_tests + 1))
        echo ""
    else
        print_warning "PHP server not running, skipping route tests"
        echo "💡 Start server first: php -S localhost:8000 index.php"
        echo ""
    fi

    # Summary
    if [ $failed_tests -eq 0 ]; then
        print_success "🎉 All tests passed!"
        echo ""
        echo "✅ ABIS is ready for development!"
        echo "🚀 Run: ./start.sh"
    else
        print_fail "❌ $failed_tests test(s) failed"
        echo ""
        echo "🔧 Please fix the issues above before proceeding."
        echo "📖 Check: SETUP_GUIDE.md#troubleshooting"
        exit 1
    fi
}

# Run main function
main "$@"
