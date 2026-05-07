#!/usr/bin/env bash
# ============================================================
# setup-env.sh — Generate .env from .env.example
#
# Usage:
#   ./setup-env.sh              Interactive mode (prompts for values)
#   ./setup-env.sh --defaults   Non-interactive mode (uses secure defaults)
#   ./setup-env.sh --force      Overwrite existing .env
# ============================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ENV_EXAMPLE="$SCRIPT_DIR/.env.example"
ENV_FILE="$SCRIPT_DIR/.env"

# --- Colors ---
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# --- Flags ---
USE_DEFAULTS=false
FORCE=false

for arg in "$@"; do
    case "$arg" in
        --defaults) USE_DEFAULTS=true ;;
        --force)    FORCE=true ;;
        --help|-h)
            echo "Usage: $0 [--defaults] [--force]"
            echo ""
            echo "  --defaults   Use secure generated defaults (non-interactive)"
            echo "  --force      Overwrite existing .env without prompting"
            echo "  --help       Show this help message"
            exit 0
            ;;
        *)
            echo -e "${RED}Unknown option: $arg${NC}"
            exit 1
            ;;
    esac
done

# --- Pre-flight checks ---
if [ ! -f "$ENV_EXAMPLE" ]; then
    echo -e "${RED}Error: .env.example not found at $ENV_EXAMPLE${NC}"
    exit 1
fi

if [ -f "$ENV_FILE" ] && [ "$FORCE" = false ]; then
    echo -e "${YELLOW}⚠  .env already exists at $ENV_FILE${NC}"
    if [ "$USE_DEFAULTS" = true ]; then
        echo -e "${RED}Aborting. Use --force to overwrite.${NC}"
        exit 1
    fi
    read -rp "Overwrite? (y/N): " confirm
    if [[ ! "$confirm" =~ ^[Yy]$ ]]; then
        echo -e "${CYAN}Aborted. Existing .env was not modified.${NC}"
        exit 0
    fi
fi

# --- Generate secure random password ---
generate_password() {
    # 24-char alphanumeric password (safe for shell/SQL, no special chars)
    head -c 18 /dev/urandom | base64 | tr -dc 'a-zA-Z0-9' | head -c 24
}

# --- Defaults ---
DEFAULT_APP_VERSION="latest"
DEFAULT_GITHUB_REPOSITORY="ph4n10m1808/basketballstore-php"
DEFAULT_WEB_PORT="80"
DEFAULT_ROOT_PASSWORD="$(generate_password)"
DEFAULT_DATABASE="basketball_store"
DEFAULT_USER="db_user"
DEFAULT_PASSWORD="$(generate_password)"
DEFAULT_HOSTNAME="db"

# --- Collect values ---
if [ "$USE_DEFAULTS" = true ]; then
    APP_VERSION="$DEFAULT_APP_VERSION"
    GITHUB_REPOSITORY="$DEFAULT_GITHUB_REPOSITORY"
    WEB_PORT="$DEFAULT_WEB_PORT"
    MYSQL_ROOT_PASSWORD="$DEFAULT_ROOT_PASSWORD"
    MYSQL_DATABASE="$DEFAULT_DATABASE"
    MYSQL_USER="$DEFAULT_USER"
    MYSQL_PASSWORD="$DEFAULT_PASSWORD"
    MYSQL_HOSTNAME="$DEFAULT_HOSTNAME"
else
    echo -e "${CYAN}╔══════════════════════════════════════════════╗${NC}"
    echo -e "${CYAN}║   BasketBallStore-PHP — Environment Setup   ║${NC}"
    echo -e "${CYAN}╚══════════════════════════════════════════════╝${NC}"
    echo ""
    echo -e "Press ${GREEN}Enter${NC} to accept the [default] value."
    echo ""

    read -rp "  APP_VERSION [$DEFAULT_APP_VERSION]: " APP_VERSION
    APP_VERSION="${APP_VERSION:-$DEFAULT_APP_VERSION}"

    read -rp "  GITHUB_REPOSITORY [$DEFAULT_GITHUB_REPOSITORY]: " GITHUB_REPOSITORY
    GITHUB_REPOSITORY="${GITHUB_REPOSITORY:-$DEFAULT_GITHUB_REPOSITORY}"

    read -rp "  WEB_PORT [$DEFAULT_WEB_PORT]: " WEB_PORT
    WEB_PORT="${WEB_PORT:-$DEFAULT_WEB_PORT}"

    read -rp "  MYSQL_ROOT_PASSWORD [$DEFAULT_ROOT_PASSWORD]: " MYSQL_ROOT_PASSWORD
    MYSQL_ROOT_PASSWORD="${MYSQL_ROOT_PASSWORD:-$DEFAULT_ROOT_PASSWORD}"

    read -rp "  MYSQL_DATABASE [$DEFAULT_DATABASE]: " MYSQL_DATABASE
    MYSQL_DATABASE="${MYSQL_DATABASE:-$DEFAULT_DATABASE}"

    read -rp "  MYSQL_USER [$DEFAULT_USER]: " MYSQL_USER
    MYSQL_USER="${MYSQL_USER:-$DEFAULT_USER}"

    read -rp "  MYSQL_PASSWORD [$DEFAULT_PASSWORD]: " MYSQL_PASSWORD
    MYSQL_PASSWORD="${MYSQL_PASSWORD:-$DEFAULT_PASSWORD}"

    read -rp "  MYSQL_HOSTNAME [$DEFAULT_HOSTNAME]: " MYSQL_HOSTNAME
    MYSQL_HOSTNAME="${MYSQL_HOSTNAME:-$DEFAULT_HOSTNAME}"
fi

# --- Write .env ---
cat > "$ENV_FILE" <<EOF
# Docker Image Configuration
APP_VERSION=${APP_VERSION}
GITHUB_REPOSITORY=${GITHUB_REPOSITORY}
WEB_PORT=${WEB_PORT}

# Database Configuration
MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}
MYSQL_DATABASE=${MYSQL_DATABASE}
MYSQL_USER=${MYSQL_USER}
MYSQL_PASSWORD=${MYSQL_PASSWORD}
MYSQL_HOSTNAME=${MYSQL_HOSTNAME}
EOF

chmod 640 "$ENV_FILE"

echo ""
echo -e "${GREEN}✅ .env created successfully at ${ENV_FILE}${NC}"
echo ""
echo -e "  ${CYAN}APP_VERSION${NC}         = ${APP_VERSION}"
echo -e "  ${CYAN}GITHUB_REPOSITORY${NC}   = ${GITHUB_REPOSITORY}"
echo -e "  ${CYAN}WEB_PORT${NC}            = ${WEB_PORT}"
echo -e "  ${CYAN}MYSQL_ROOT_PASSWORD${NC} = ${MYSQL_ROOT_PASSWORD}"
echo -e "  ${CYAN}MYSQL_DATABASE${NC}      = ${MYSQL_DATABASE}"
echo -e "  ${CYAN}MYSQL_USER${NC}          = ${MYSQL_USER}"
echo -e "  ${CYAN}MYSQL_PASSWORD${NC}      = ${MYSQL_PASSWORD}"
echo -e "  ${CYAN}MYSQL_HOSTNAME${NC}      = ${MYSQL_HOSTNAME}"
echo ""
echo -e "Run ${GREEN}docker compose up -d${NC} to start the application."
