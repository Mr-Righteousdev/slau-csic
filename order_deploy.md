**Excellent! Let me create a comprehensive VPS setup guide for your Contabo server.** 🚀

------

```markdown
# Contabo VPS Setup Guide - Ubuntu Server Configuration
**Purpose:** Production-ready Laravel hosting environment  
**Server:** Contabo VPS (Ubuntu 22.04/24.04 LTS)  
**Last Updated:** April 2026  
**Estimated Time:** 2-3 hours

---

## 📋 TABLE OF CONTENTS

1. [Initial Connection & Security](#1-initial-connection--security)
2. [System Updates & Essential Packages](#2-system-updates--essential-packages)
3. [User Management & SSH Security](#3-user-management--ssh-security)
4. [Firewall Configuration](#4-firewall-configuration)
5. [Install Nginx Web Server](#5-install-nginx-web-server)
6. [Install PHP 8.3 & Extensions](#6-install-php-83--extensions)
7. [Install Composer](#7-install-composer)
8. [Install MySQL 8.0](#8-install-mysql-80)
9. [Install Redis](#9-install-redis)
10. [Install Node.js & NPM](#10-install-nodejs--npm)
11. [Install Supervisor (Queue Manager)](#11-install-supervisor)
12. [SSL Certificate Setup (Let's Encrypt)](#12-ssl-certificate-setup)
13. [Performance Optimization](#13-performance-optimization)
14. [Monitoring & Maintenance](#14-monitoring--maintenance)
15. [Backup Configuration](#15-backup-configuration)
16. [Security Hardening](#16-security-hardening)
17. [Troubleshooting](#17-troubleshooting)

---

## 1. INITIAL CONNECTION & SECURITY

### Step 1.1: First Login

You should have received an email from Contabo with:
```

IP Address: xxx.xxx.xxx.xxx Root Password: xxxxxxxxxxxxxxx

```
**Connect via SSH:**

**Windows (PowerShell/CMD):**
```bash
ssh root@xxx.xxx.xxx.xxx
# Enter password when prompted
```

**Mac/Linux (Terminal):**

```bash
ssh root@xxx.xxx.xxx.xxx
# Enter password when prompted
```

**If you get a warning about host authenticity:**

```
Type: yes
Press Enter
```

### Step 1.2: Change Root Password (IMPORTANT!)

```bash
passwd
# Enter new secure password twice
# Use a strong password: mix of uppercase, lowercase, numbers, symbols
# Example: MyV3ryS3cur3P@ssw0rd!2026
```

**Save this password securely!**

------

## 2. SYSTEM UPDATES & ESSENTIAL PACKAGES

### Step 2.1: Update System Packages

```bash
# Update package lists
apt update

# Upgrade all packages
apt upgrade -y

# Install essential tools
apt install -y curl wget git unzip zip software-properties-common \
    apt-transport-https ca-certificates gnupg lsb-release \
    build-essential htop ncdu vim nano
```

**This will take 5-10 minutes. Wait for completion.**

### Step 2.2: Set Timezone (Uganda)

```bash
# Set timezone to Africa/Kampala
timedatectl set-timezone Africa/Kampala

# Verify
timedatectl
```

### Step 2.3: Set Hostname

```bash
# Set a memorable hostname
hostnamectl set-hostname production-server

# Verify
hostname
```

------

## 3. USER MANAGEMENT & SSH SECURITY

### Step 3.1: Create Deploy User

**Never use root for daily operations!**

```bash
# Create new user
adduser deploy
# Enter password: DeployUser2026!@#
# Fill in details (or press Enter to skip)

# Add to sudo group
usermod -aG sudo deploy

# Verify
groups deploy
# Should show: deploy sudo
```

### Step 3.2: Set Up SSH Key Authentication (HIGHLY RECOMMENDED)

**On your LOCAL machine (not the server):**

**Windows (PowerShell):**

```powershell
# Check if you have an SSH key
Get-Content ~/.ssh/id_rsa.pub

# If not, generate one:
ssh-keygen -t rsa -b 4096 -C "your-email@example.com"
# Press Enter for all prompts (default location, no passphrase)

# Display your public key
Get-Content ~/.ssh/id_rsa.pub
```

**Mac/Linux (Terminal):**

```bash
# Check if you have an SSH key
cat ~/.ssh/id_rsa.pub

# If not, generate one:
ssh-keygen -t rsa -b 4096 -C "your-email@example.com"
# Press Enter for all prompts

# Display your public key
cat ~/.ssh/id_rsa.pub
```

**Copy the entire output (starts with `ssh-rsa ...`)**

**Back on the VPS (as root):**

```bash
# Switch to deploy user
su - deploy

# Create .ssh directory
mkdir -p ~/.ssh
chmod 700 ~/.ssh

# Create authorized_keys file
nano ~/.ssh/authorized_keys

# Paste your public key (right-click to paste)
# Save: Ctrl+X, then Y, then Enter

# Set permissions
chmod 600 ~/.ssh/authorized_keys

# Exit back to root
exit
```

### Step 3.3: Test SSH Key Login

**Open a NEW terminal (don't close the current one yet!):**

```bash
ssh deploy@xxx.xxx.xxx.xxx
```

**Should login WITHOUT asking for password!**

If it works, continue. If not, check Step 3.2 again.

### Step 3.4: Disable Root SSH Login & Password Authentication

**Back on VPS (as root):**

```bash
# Backup SSH config
cp /etc/ssh/sshd_config /etc/ssh/sshd_config.backup

# Edit SSH config
nano /etc/ssh/sshd_config
```

**Find and change these lines:**

```conf
# Find this line:
PermitRootLogin yes
# Change to:
PermitRootLogin no

# Find this line:
PasswordAuthentication yes
# Change to:
PasswordAuthentication no

# Find this line (might be commented):
PubkeyAuthentication yes
# Make sure it says:
PubkeyAuthentication yes
```

**Save:** Ctrl+X, then Y, then Enter

**Restart SSH:**

```bash
systemctl restart sshd

# Verify SSH is still running
systemctl status sshd
```

**⚠️ IMPORTANT: Keep your current SSH session open until you verify you can login with the deploy user!**

------

## 4. FIREWALL CONFIGURATION

### Step 4.1: Install & Configure UFW

```bash
# Check UFW status
ufw status

# Allow SSH (CRITICAL - do this first!)
ufw allow 22/tcp

# Allow HTTP
ufw allow 80/tcp

# Allow HTTPS
ufw allow 443/tcp

# Enable firewall
ufw enable
# Type: y and press Enter

# Verify rules
ufw status numbered
```

**Expected output:**

```
Status: active

     To                         Action      From
     --                         ------      ----
[ 1] 22/tcp                     ALLOW IN    Anywhere
[ 2] 80/tcp                     ALLOW IN    Anywhere
[ 3] 443/tcp                    ALLOW IN    Anywhere
```

### Step 4.2: Rate Limiting (Prevent Brute Force)

```bash
# Limit SSH connections
ufw limit 22/tcp
```

------

## 5. INSTALL NGINX WEB SERVER

### Step 5.1: Install Nginx

```bash
# Switch to deploy user
su - deploy

# Install Nginx
sudo apt install nginx -y

# Start Nginx
sudo systemctl start nginx

# Enable on boot
sudo systemctl enable nginx

# Check status
sudo systemctl status nginx
```

### Step 5.2: Test Nginx

**Open browser and visit:**

```
http://xxx.xxx.xxx.xxx
```

**Should see:** "Welcome to nginx!" page

### Step 5.3: Configure Nginx Basics

```bash
# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Create directory for sites
sudo mkdir -p /var/www

# Set ownership
sudo chown -R deploy:deploy /var/www
```

------

## 6. INSTALL PHP 8.3 & EXTENSIONS

### Step 6.1: Add PHP Repository

```bash
# Add Ondřej Surý's PPA (trusted source for latest PHP)
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
```

### Step 6.2: Install PHP 8.3 & All Laravel Extensions

```bash
sudo apt install -y php8.3-fpm php8.3-cli php8.3-common \
    php8.3-mysql php8.3-pgsql php8.3-sqlite3 \
    php8.3-zip php8.3-gd php8.3-mbstring \
    php8.3-curl php8.3-xml php8.3-bcmath \
    php8.3-redis php8.3-intl php8.3-soap \
    php8.3-imagick php8.3-opcache
```

### Step 6.3: Verify PHP Installation

```bash
php -v
# Should show: PHP 8.3.x

# Check loaded modules
php -m | grep -E "mysql|redis|gd|curl"
```

### Step 6.4: Configure PHP for Production

**Edit PHP-FPM config:**

```bash
sudo nano /etc/php/8.3/fpm/php.ini
```

**Find and update these values:**

```ini
; Line ~409
memory_limit = 512M

; Line ~694
post_max_size = 50M

; Line ~846
upload_max_filesize = 50M

; Line ~383
max_execution_time = 300

; Line ~403
max_input_time = 300

; Line ~936 (timezone)
date.timezone = Africa/Kampala

; Line ~409 (error reporting - production)
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT

; Line ~479
display_errors = Off

; Line ~524
log_errors = On
```

**Save:** Ctrl+X, then Y, then Enter

**Edit PHP CLI config (same changes):**

```bash
sudo nano /etc/php/8.3/cli/php.ini
# Make the same changes as above
```

**Restart PHP-FPM:**

```bash
sudo systemctl restart php8.3-fpm
sudo systemctl enable php8.3-fpm
sudo systemctl status php8.3-fpm
```

------

## 7. INSTALL COMPOSER

```bash
# Download installer
curl -sS https://getcomposer.org/installer -o composer-setup.php

# Verify installer (optional)
HASH="$(wget -q -O - https://composer.github.io/installer.sig)"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

# Install globally
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Clean up
rm composer-setup.php

# Verify
composer --version
# Should show: Composer version 2.x.x
```

------

## 8. INSTALL MYSQL 8.0

### Step 8.1: Install MySQL Server

```bash
sudo apt install mysql-server -y

# Start MySQL
sudo systemctl start mysql
sudo systemctl enable mysql
```

### Step 8.2: Secure MySQL Installation

```bash
sudo mysql_secure_installation
```

**Follow prompts:**

```
1. Would you like to setup VALIDATE PASSWORD component?
   → Press Y (yes)

2. Please enter 0 = LOW, 1 = MEDIUM, 2 = STRONG
   → Enter 2 (STRONG)

3. New password:
   → Enter strong password: MySQL2026!SecurePass

4. Re-enter password:
   → Re-enter same password

5. Remove anonymous users?
   → Y (yes)

6. Disallow root login remotely?
   → Y (yes)

7. Remove test database?
   → Y (yes)

8. Reload privilege tables now?
   → Y (yes)
```

### Step 8.3: Configure MySQL for Production

```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

**Add these lines under `[mysqld]` section:**

```ini
# Performance tuning (for VPS M - 8GB RAM)
max_connections = 200
innodb_buffer_pool_size = 2G
innodb_log_file_size = 256M
query_cache_size = 0
query_cache_type = 0

# Character set
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

# Timezone
default-time-zone = '+03:00'
```

**Restart MySQL:**

```bash
sudo systemctl restart mysql
```

### Step 8.4: Create Database User for Laravel Apps

```bash
sudo mysql -u root -p
# Enter the password you set in Step 8.2
```

**Inside MySQL:**

```sql
-- Create a database user for Laravel apps
CREATE USER 'laraveladmin'@'localhost' IDENTIFIED BY 'Laravel2026!SecureDB';

-- Grant all privileges on all databases (for multi-tenant apps)
GRANT ALL PRIVILEGES ON *.* TO 'laraveladmin'@'localhost' WITH GRANT OPTION;

-- Flush privileges
FLUSH PRIVILEGES;

-- Test by creating a test database
CREATE DATABASE test_connection CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Show databases
SHOW DATABASES;

-- Exit
EXIT;
```

**Test connection:**

```bash
mysql -u laraveladmin -p
# Enter password: Laravel2026!SecureDB

# If successful:
EXIT;
```

------

## 9. INSTALL REDIS

```bash
# Install Redis
sudo apt install redis-server -y

# Configure Redis for production
sudo nano /etc/redis/redis.conf
```

**Find and update:**

```conf
# Line ~69 - Listen on localhost only
bind 127.0.0.1 ::1

# Line ~88 - Change to yes
protected-mode yes

# Line ~242 - Set max memory
maxmemory 256mb

# Line ~266 - Set eviction policy
maxmemory-policy allkeys-lru

# Line ~428 - Disable RDB snapshots (optional, saves disk)
# Comment out these lines by adding # at the start:
# save 900 1
# save 300 10
# save 60 10000
```

**Save and restart:**

```bash
sudo systemctl restart redis-server
sudo systemctl enable redis-server

# Test Redis
redis-cli ping
# Should return: PONG
```

------

## 10. INSTALL NODE.js & NPM

### Step 10.1: Install Node.js 20 (LTS)

```bash
# Add NodeSource repository
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -

# Install Node.js
sudo apt install nodejs -y

# Verify
node --version
# Should show: v20.x.x

npm --version
# Should show: 10.x.x
```

### Step 10.2: Install Yarn (Optional, but recommended)

```bash
sudo npm install -g yarn

# Verify
yarn --version
```

------

## 11. INSTALL SUPERVISOR

**Supervisor manages Laravel queue workers.**

```bash
# Install Supervisor
sudo apt install supervisor -y

# Start and enable
sudo systemctl start supervisor
sudo systemctl enable supervisor

# Check status
sudo systemctl status supervisor
```

**Create logs directory:**

```bash
sudo mkdir -p /var/log/supervisor
sudo chown deploy:deploy /var/log/supervisor
```

------

## 12. SSL CERTIFICATE SETUP

### Step 12.1: Install Certbot

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Verify installation
certbot --version
```

### Step 12.2: Obtain SSL Certificate (when you have a domain)

**Note:** You need a domain pointing to your server first!

```bash
# For single domain
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# For wildcard (multi-tenant apps)
sudo certbot --nginx -d yourdomain.com -d *.yourdomain.com

# Follow prompts:
# 1. Enter email: your-email@example.com
# 2. Agree to terms: Y
# 3. Share email with EFF: N (optional)
# 4. Redirect HTTP to HTTPS: 2 (Yes)
```

### Step 12.3: Auto-Renewal Setup

**Certbot auto-configures renewal. Test it:**

```bash
sudo certbot renew --dry-run
```

**Should see:** "Congratulations, all simulated renewals succeeded"

------

## 13. PERFORMANCE OPTIMIZATION

### Step 13.1: Enable PHP OpCache

```bash
sudo nano /etc/php/8.3/fpm/php.ini
```

**Find `[opcache]` section (around line 1800) and update:**

```ini
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

**Restart PHP-FPM:**

```bash
sudo systemctl restart php8.3-fpm
```

### Step 13.2: Optimize Nginx

```bash
sudo nano /etc/nginx/nginx.conf
```

**Update `http` block:**

```nginx
http {
    ##
    # Basic Settings
    ##
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    types_hash_max_size 2048;
    server_tokens off;  # Hide Nginx version

    # Increase buffer sizes
    client_body_buffer_size 10K;
    client_header_buffer_size 1k;
    client_max_body_size 50M;
    large_client_header_buffers 4 16k;

    ##
    # Gzip Settings
    ##
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript 
               application/json application/javascript application/xml+rss 
               application/rss+xml font/truetype font/opentype 
               application/vnd.ms-fontobject image/svg+xml;
    gzip_disable "msie6";

    # Rest of config...
}
```

**Test and reload:**

```bash
sudo nginx -t
sudo systemctl reload nginx
```

### Step 13.3: Tune Kernel Parameters

```bash
sudo nano /etc/sysctl.conf
```

**Add at the end:**

```conf
# Increase system file descriptor limit
fs.file-max = 100000

# Increase network buffer sizes
net.core.rmem_max = 16777216
net.core.wmem_max = 16777216
net.ipv4.tcp_rmem = 4096 87380 16777216
net.ipv4.tcp_wmem = 4096 65536 16777216

# Enable TCP fast open
net.ipv4.tcp_fastopen = 3

# Increase max connections
net.core.somaxconn = 4096
net.ipv4.tcp_max_syn_backlog = 8192

# Reuse TIME_WAIT sockets
net.ipv4.tcp_tw_reuse = 1
```

**Apply changes:**

```bash
sudo sysctl -p
```

------

## 14. MONITORING & MAINTENANCE

### Step 14.1: Install Monitoring Tools

```bash
# Install htop (process monitor)
sudo apt install htop -y

# Install ncdu (disk usage)
sudo apt install ncdu -y

# Install iotop (I/O monitor)
sudo apt install iotop -y
```

**Usage:**

```bash
htop          # View processes (press F10 to exit)
ncdu /var     # Check disk usage
sudo iotop    # Monitor I/O (press q to exit)
```

### Step 14.2: Set Up Log Rotation

**Laravel logs:**

```bash
sudo nano /etc/logrotate.d/laravel
```

**Add:**

```
/var/www/*/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 deploy deploy
    sharedscripts
}
```

### Step 14.3: Create Maintenance Scripts

**System health check script:**

```bash
nano ~/health-check.sh
```

**Add:**

```bash
#!/bin/bash
echo "=== System Health Check ==="
echo ""
echo "--- Disk Usage ---"
df -h | grep -E "/$|/var"
echo ""
echo "--- Memory Usage ---"
free -h
echo ""
echo "--- CPU Load ---"
uptime
echo ""
echo "--- Services Status ---"
systemctl is-active nginx mysql php8.3-fpm redis-server supervisor
echo ""
echo "--- Failed Login Attempts (last 20) ---"
sudo grep "Failed password" /var/log/auth.log | tail -20
```

**Make executable:**

```bash
chmod +x ~/health-check.sh
```

**Run it:**

```bash
./health-check.sh
```

------

## 15. BACKUP CONFIGURATION

### Step 15.1: Create Backup Script

```bash
sudo mkdir -p /backups
sudo chown deploy:deploy /backups

nano ~/backup-databases.sh
```

**Add:**

```bash
#!/bin/bash

# Configuration
BACKUP_DIR="/backups/mysql"
MYSQL_USER="laraveladmin"
MYSQL_PASS="Laravel2026!SecureDB"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Get all databases except system ones
DATABASES=$(mysql -u $MYSQL_USER -p$MYSQL_PASS -e "SHOW DATABASES;" | grep -Ev "Database|information_schema|performance_schema|mysql|sys")

# Backup each database
for DB in $DATABASES; do
    echo "Backing up database: $DB"
    mysqldump -u $MYSQL_USER -p$MYSQL_PASS $DB | gzip > "$BACKUP_DIR/${DB}_${DATE}.sql.gz"
done

# Delete backups older than 7 days
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $(date)"
```

**Make executable:**

```bash
chmod +x ~/backup-databases.sh
```

### Step 15.2: Schedule Daily Backups

```bash
crontab -e
# Choose nano (option 1)
```

**Add:**

```cron
# Daily database backup at 2 AM
0 2 * * * /home/deploy/backup-databases.sh >> /var/log/backup.log 2>&1

# Weekly system health check
0 8 * * 1 /home/deploy/health-check.sh | mail -s "Weekly Server Health" your-email@example.com
```

------

## 16. SECURITY HARDENING

### Step 16.1: Install Fail2Ban (Prevent Brute Force)

```bash
sudo apt install fail2ban -y

# Create local config
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
sudo nano /etc/fail2ban/jail.local
```

**Find and update:**

```ini
[DEFAULT]
bantime = 1h
findtime = 10m
maxretry = 5

[sshd]
enabled = true
port = ssh
logpath = %(sshd_log)s
backend = %(sshd_backend)s

[nginx-http-auth]
enabled = true
```

**Restart Fail2Ban:**

```bash
sudo systemctl restart fail2ban
sudo systemctl enable fail2ban

# Check status
sudo fail2ban-client status
```

### Step 16.2: Disable Unused Services

```bash
# List all running services
systemctl list-unit-files --state=enabled

# Example: Disable Bluetooth if not needed
sudo systemctl disable bluetooth
```

### Step 16.3: Regular Security Updates

```bash
# Enable automatic security updates
sudo apt install unattended-upgrades -y
sudo dpkg-reconfigure --priority=low unattended-upgrades
# Select: Yes
```

------

## 17. TROUBLESHOOTING

### Issue: Can't Connect via SSH

```bash
# Check firewall on server
sudo ufw status

# Ensure port 22 is allowed
sudo ufw allow 22/tcp
```

### Issue: Nginx 502 Bad Gateway

```bash
# Check PHP-FPM status
sudo systemctl status php8.3-fpm

# Restart services
sudo systemctl restart php8.3-fpm nginx
```

### Issue: MySQL Connection Refused

```bash
# Check MySQL status
sudo systemctl status mysql

# Check if listening
sudo netstat -tlnp | grep mysql

# Restart MySQL
sudo systemctl restart mysql
```

### Issue: Out of Disk Space

```bash
# Check disk usage
df -h

# Find large directories
sudo ncdu /var

# Clean package cache
sudo apt clean
sudo apt autoremove -y

# Clean old logs
sudo journalctl --vacuum-time=7d
```

### Issue: High Memory Usage

```bash
# Check memory
free -h

# Find memory hogs
ps aux --sort=-%mem | head

# Restart services
sudo systemctl restart php8.3-fpm nginx mysql
```

------

## 📋 POST-SETUP CHECKLIST

- [ ] Root password changed
- [ ] Deploy user created
- [ ] SSH key authentication working
- [ ] Root SSH login disabled
- [ ] Firewall enabled (ports 22, 80, 443)
- [ ] Nginx installed and running
- [ ] PHP 8.3 installed with all extensions
- [ ] Composer installed
- [ ] MySQL installed and secured
- [ ] Redis installed and running
- [ ] Node.js & NPM installed
- [ ] Supervisor installed
- [ ] Timezone set correctly
- [ ] OpCache enabled
- [ ] Fail2Ban configured
- [ ] Backup script created
- [ ] Monitoring tools installed
- [ ] SSL certificate setup (when domain ready)

------

## 🎯 NEXT STEPS

### 1. Deploy Your First Laravel App

```bash
# Create project directory
cd /var/www
mkdir myapp
cd myapp

# Clone your repository
git clone https://github.com/yourusername/myapp.git .

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Configure environment
cp .env.example .env
nano .env

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Set permissions
sudo chown -R deploy:www-data /var/www/myapp
sudo chmod -R 775 storage bootstrap/cache
```

### 2. Configure Nginx for Your App

```bash
sudo nano /etc/nginx/sites-available/myapp
```

**Add:**

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/myapp/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Enable site:**

```bash
sudo ln -s /etc/nginx/sites-available/myapp /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 3. Configure Queue Workers

```bash
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

**Add:**

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/myapp/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=deploy
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/myapp/storage/logs/worker.log
```

**Update Supervisor:**

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### 4. Set Up Laravel Scheduler

```bash
crontab -e
```

**Add:**

```cron
* * * * * cd /var/www/myapp && php artisan schedule:run >> /dev/null 2>&1
```

------

## 🔒 SECURITY BEST PRACTICES

1. **Never commit `.env` files to Git**

2. **Use strong, unique passwords for all services**

3. **Keep all software updated regularly:**

   ```bash
   sudo apt update && sudo apt upgrade -y
   ```

4. **Monitor server logs regularly:**

   ```bash
   sudo tail -f /var/log/nginx/error.logsudo tail -f /var/log/auth.log
   ```

5. **Use SSH keys only, disable password auth**

6. **Enable automatic security updates**

7. **Regular backups (daily databases, weekly full system)**

8. **Use environment variables for sensitive data**

------

## 📞 USEFUL COMMANDS REFERENCE

### Service Management

```bash
sudo systemctl status [service]    # Check status
sudo systemctl start [service]     # Start service
sudo systemctl stop [service]      # Stop service
sudo systemctl restart [service]   # Restart service
sudo systemctl enable [service]    # Enable on boot
sudo systemctl disable [service]   # Disable on boot
```

### Log Viewing

```bash
sudo tail -f /var/log/nginx/access.log        # Nginx access
sudo tail -f /var/log/nginx/error.log         # Nginx errors
sudo tail -f /var/log/mysql/error.log         # MySQL errors
sudo journalctl -u nginx -f                   # Nginx journal
sudo journalctl -u php8.3-fpm -f             # PHP-FPM journal
```

### Disk & Memory

```bash
df -h              # Disk usage
free -h            # Memory usage
du -sh /var/www/*  # Directory sizes
ncdu /var          # Interactive disk usage
htop               # Process monitor
```

### Networking

```bash
netstat -tlnp      # Open ports
ss -tlnp           # Open ports (modern)
ufw status         # Firewall status
fail2ban-client status  # Fail2Ban status
```

------

## 📚 ADDITIONAL RESOURCES

- **Nginx Docs:** https://nginx.org/en/docs/
- **PHP Manual:** https://www.php.net/manual/en/
- **Laravel Deployment:** https://laravel.com/docs/deployment
- **MySQL Docs:** https://dev.mysql.com/doc/
- **Ubuntu Server Guide:** https://ubuntu.com/server/docs

------

## 🆘 GETTING HELP

If you encounter issues:

1. **Check service logs** (see commands above)
2. **Google the exact error message**
3. **Stack Overflow:** https://stackoverflow.com
4. **Laravel Community:** https://laracasts.com/discuss
5. **Contabo Support:** support@contabo.com

------

**Server Setup Complete!** 🎉

Your VPS is now production-ready for Laravel applications.

------

**Last Updated:** April 2026
 **Maintained By:** Mr-Righteousdev
 **Version:** 1.0

```
---

**This guide covers everything you need! Save it and follow step-by-step. Good luck with your deployment!** 🚀
```