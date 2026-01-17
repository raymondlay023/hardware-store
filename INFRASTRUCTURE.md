# BangunanPro Infrastructure Files - Purpose & Documentation

## Docker Configuration Files

### `Dockerfile` (Production)
**Purpose**: Multi-stage build for optimized production deployment  
**What it does**:
- Stage 1: Builds application with all dependencies
- Stage 2: Builds frontend assets with Node.js
- Stage 3: Creates minimal production image (~376MB)
- Includes: PHP 8.2, Nginx, Supervisor, Redis extension, OPcache
- **Optimizations**: Removes dev packages, enables OPcache, layer caching
- **When to use**: Building production images for deployment

### `Dockerfile.dev` (Development)
**Purpose**: Simplified Docker image for local development  
**What it does**:
- Single-stage build for faster iteration
- Includes development tools (Node.js, npm)
- No build optimizations (easier debugging)
- Composer dependencies installed but can be overridden by volume mount
- **When to use**: Local development with docker-compose

### `docker-compose.yml`
**Purpose**: Orchestrates all services for local development  
**What it does**:
- Defines 6 services: app, nginx, mysql, redis, queue, scheduler
- Sets up networking, volumes, health checks
- Configures resource limits, logging, dependencies
- **Services**:
  - `app`: Laravel PHP application
  - `nginx`: Web server (port 8000)
  - `mysql`: Database (port 3307)
  - `redis`: Cache & sessions (port 6379)
  - `queue`: Background job processor
  - `scheduler`: Cron-like task scheduler

### `.dockerignore`
**Purpose**: Excludes files from Docker build context  
**What it does**:
- Reduces build time and image size
- Prevents sensitive files from being copied
- Excludes: node_modules, vendor, .git, .env files
- **Impact**: Faster builds, smaller images

---

## Docker Configuration Subdirectories

### `docker/nginx/`
**nginx.conf**: Main nginx configuration  
- Worker processes, gzip compression, mime types
  
**default.conf**: Virtual host configuration  
- PHP-FPM proxy, security headers (CSP, XSS, HSTS)
- Request limits, FastCGI optimizations
- Health check endpoint

### `docker/php/`
**local.ini**: Development PHP settings  
- Upload limits (40MB), execution time (300s)
- Timezone (Asia/Jakarta)

**production.ini**: Production PHP optimizations  
- OPcache enabled (2-3x performance)
- Security hardening (expose_php=Off, display_errors=Off)
- Session security, realpath cache

### `docker/supervisor/`
**supervisord.conf**: Process manager for production  
- Manages PHP-FPM, Nginx, Queue workers, Scheduler
- Auto-restart, log rotation, process priorities
- Runs multiple processes in single container

### `docker/scripts/`
**healthcheck.sh**: Application health validation  
- Tests: PHP, Laravel boot, database, Redis cache
- Used by Docker health checks

---

## Environment Files

### `.env.example`
**Purpose**: Template for local development  
**Contains**: Basic required variables with safe defaults

### `.env.docker.example` ⭐ NEW
**Purpose**: Template for Docker development  
**Contains**: Docker-specific settings (service names: mysql, redis)
- **Why separate**: Docker uses service names vs localhost
- **Security**: Sanitized version with placeholder secrets

### `.env.production.example`
**Purpose**: Template for production deployment  
**Contains**: Production-optimized settings
- APP_DEBUG=false, session encryption, cache drivers
- Monitoring, backup, rate limiting configs

### `.env.validation.sh` ⭐ NEW
**Purpose**: Validates environment variables before deployment  
**What it does**:
- Checks required variables are set
- Validates APP_KEY format
- Tests database and Redis connections
- **When to run**: Before every deployment

---

## Deployment Scripts

### `docker-setup.bat` (Windows)
**Purpose**: Initial Docker setup with full application installation  
**What it does**:
- Builds images, starts containers
- Installs Composer & NPM dependencies
- Runs migrations and seeders
- Sets permissions, caches config
- **When to use**: First-time project setup

### `docker-setup.sh` (Linux/Mac)
**Purpose**: Same as `.bat` but for Unix systems  
**When to use**: First-time setup on Linux/Mac

### `docker-start.bat` ⭐ RECOMMENDED (Windows)
**Purpose**: Quick start for daily development  
**What it does**:
- Stops XAMPP (avoids port conflicts)
- Builds and starts Docker containers
- Runs migrations
- **When to use**: Starting work each day

### `deploy.sh`
**Purpose**: Production deployment script  
**What it does**:
- Enables maintenance mode
- Pulls latest code
- Installs dependencies, builds assets
- Runs migrations, clears/caches configs
- Restarts queue workers
- **When to use**: Deploying to production server

### `verify-docker.sh`
**Purpose**: Verifies Docker installation is working  
**What it does**:
- Checks Docker/Docker Compose installed
- Tests container creation
- Validates network connectivity

---

## Documentation Files

### `DEPLOYMENT.md`
**Purpose**: Complete deployment guide  
**Contains**:
- Laravel Forge + DigitalOcean setup
- Manual VPS deployment steps
- Docker on VPS instructions
- Environment configuration
- Scaling checklist, troubleshooting

### `PRODUCTION_CHECKLIST.md`
**Purpose**: Pre-launch security & performance checklist  
**Use before**: Going live to production

### `SECURITY.md`
**Purpose**: Security policies and best practices  
**Contains**:
- Vulnerability reporting process
- Security features implemented
- Production security checklist

### `ARCHITECTURE.md`
**Purpose**: System architecture documentation  
**Contains**:
- Technology stack
- Database schema
- Service structure

---

## Files to KEEP vs REMOVE

### ✅ KEEP - Essential Infrastructure Files
```
Dockerfile                      # Production builds
Dockerfile.dev                  # Development
docker-compose.yml              # Service orchestration
.dockerignore                   # Build optimization
docker/nginx/*                  # Web server config
docker/php/*                    # PHP configuration
docker/supervisor/*             # Process management
docker/scripts/healthcheck.sh   # Health checks
.env.docker.example             # Docker template
.env.validation.sh              # Deployment validation
docker-start.bat                # Quick start (Windows)
docker-setup.bat                # Full setup (Windows)
deploy.sh                       # Production deployment
```

### ❌ REMOVE - Redundant/Unused Files
```
docker-setup.sh                 # Duplicate of .bat (if Windows-only)
verify-docker.sh                # Optional validation script
.env.docker.backup              # Temporary backup (can delete)
```

---

## Quick Reference - Which File to Use When

### Starting Development
1. **First time**: Run `docker-setup.bat`
2. **Daily use**: Run `docker-start.bat`

### Making Changes
- **PHP config**: Edit `docker/php/local.ini`
- **Nginx config**: Edit `docker/nginx/default.conf`
- **Services**: Edit `docker-compose.yml`
- **Production image**: Edit `Dockerfile`

### Before Deployment
1. Run `.env.validation.sh`
2. Review `PRODUCTION_CHECKLIST.md`
3. Update `.env` from `.env.production.example`

### Deploying
- **Production**: Run `deploy.sh`
- **Staging**: Run `docker-compose up -d` with production .env

---

## File Size Impact

| File | Size | Impact |
|------|------|--------|
| Dockerfile | 3KB | Critical - builds production image |
| docker-compose.yml | 6KB | Critical - defines all services |
| docker/nginx/*.conf | 2KB | Medium - web server performance |
| docker/php/*.ini | 1KB | Medium - PHP performance |
| .dockerignore | 1KB | High - reduces build time |

---

## Summary

**Total Infrastructure Files**: 20+  
**Essential Files**: 12  
**Optional Files**: 5  
**Documentation**: 5  

**All files serve a purpose - no bloat!** Each configuration file optimizes performance, security, or developer experience.
