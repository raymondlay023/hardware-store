# 🔐 Credential Rotation Guide - BangunanPro

## Quick Reference

This guide helps you rotate all credentials identified in the security audit.

---

## 1. APP_KEY (Laravel)

### Generate New Key
```bash
# Option 1: Generate and show new key
docker-compose exec app php artisan key:generate --show

# Option 2: Generate and automatically update .env
docker-compose exec app php artisan key:generate
```

### Manual Update
If using Option 1, copy the generated key to `.env`:
```env
APP_KEY=base64:YOUR_NEW_KEY_HERE
```

### Restart Application
```bash
docker-compose restart app nginx queue scheduler
```

⚠️ **WARNING**: Rotating APP_KEY will:
- Log out all users
- Invalidate all existing sessions
- Require users to log in again

---

## 2. Redis Password

### Update .env File
```env
REDIS_PASSWORD=redis_dev_secure_2026
```

Or generate a stronger password:
```bash
# Generate random 32-character password
openssl rand -base64 32
```

### Apply Changes
```bash
docker-compose down
docker-compose up -d
```

✅ **Already Updated**: docker-compose.yml now uses `redis_dev_secure_2026` as default

---

## 3. FONNTE_TOKEN (WhatsApp API)

### Steps to Rotate
1. Go to https://fonnte.com
2. Log in to your account
3. Navigate to Dashboard → API Settings
4. Click "Generate New Token" or "Regenerate"
5. Copy the new token

### Update .env
```env
FONNTE_TOKEN=your_new_token_here
```

### Test
```bash
# Test WhatsApp notification
docker-compose exec app php artisan tinker
>>> \App\Services\WhatsAppService::sendMessage('+1234567890', 'Test message');
```

---

## 4. FLARE_KEY (Error Tracking)

### Steps to Rotate
1. Go to https://flareapp.io
2. Log in to your account
3. Navigate to Project Settings
4. Find "API Key" or "Project Key" section
5. Click "Regenerate Key"
6. Copy the new key

### Update .env
```env
FLARE_KEY=flare_live_your_new_key_here
```

### Test
```bash
# Trigger a test error to verify
docker-compose exec app php artisan flare:test
```

---

## 5. Mailtrap Credentials

### Steps to Rotate
1. Go to https://mailtrap.io
2. Log in to your account
3. Navigate to Email Testing → Inboxes
4. Click on your inbox
5. Go to SMTP Settings tab
6. Click "Reset Password" or regenerate credentials

### Update .env
```env
MAIL_USERNAME=your_new_username
MAIL_PASSWORD=your_new_password
```

### Test
```bash
# Send test email
docker-compose exec app php artisan tinker
>>> Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

---

## 6. Database Password (Optional but Recommended)

### For Development
Current password: `secret` (default)

To change:

1. **Update .env**:
```env
DB_PASSWORD=your_new_secure_password
```

2. **Update docker-compose.yml** environment:
```yaml
MYSQL_ROOT_PASSWORD: ${DB_PASSWORD:-secret}
```

3. **Recreate database container**:
```bash
docker-compose down -v  # ⚠️ This deletes all data!
docker-compose up -d
docker-compose exec app php artisan migrate:fresh --seed
```

---

## Complete Rotation Checklist

### Before You Start
- [ ] Backup current `.env` file
- [ ] Ensure you have access to all service dashboards
- [ ] Schedule maintenance window if needed

### Rotation Steps
- [ ] Generate new APP_KEY
- [ ] Update Redis password
- [ ] Regenerate FONNTE_TOKEN
- [ ] Regenerate FLARE_KEY  
- [ ] Reset Mailtrap credentials
- [ ] (Optional) Change database password

### After Rotation
- [ ] Update `.env` with all new credentials
- [ ] Restart all services
- [ ] Test each service:
  - [ ] Application loads
  - [ ] Redis connection works
  - [ ] WhatsApp notifications work
  - [ ] Error tracking works
  - [ ] Email sending works
- [ ] Clear browser cache and test login
- [ ] Monitor logs for errors
- [ ] Update documentation

---

## Testing All Services

### Quick Test Script
```bash
# 1. Check application
curl http://localhost:8000/health

# 2. Check Redis
docker exec bangunanpro_redis redis-cli -a redis_dev_secure_2026 ping

# 3. Check MySQL
docker exec bangunanpro_mysql mysqladmin ping -h localhost --silent

# 4. Check Laravel connection
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();  # Should not error
>>> Cache::put('test', 'works', 10);  # Should not error
>>> Cache::get('test');  # Should return 'works'
```

---

## Troubleshooting

### APP_KEY Issues
**Error**: "No application encryption key has been specified"
```bash
docker-compose exec app php artisan key:generate
docker-compose restart app
```

### Redis Connection Refused
**Error**: "Connection refused [tcp://redis:6379]"
```bash
# Check Redis is running
docker-compose ps redis

# Check password matches in .env
grep REDIS_PASSWORD .env

# Restart Redis
docker-compose restart redis
```

### Database Connection Failed  
**Error**: "Access denied for user 'root'@'...' (using password: YES)"
```bash
# Check password matches
grep DB_PASSWORD .env

# If needed, recreate database
docker-compose down -v
docker-compose up -d mysql
# Wait 30 seconds for MySQL to initialize
docker-compose exec app php artisan migrate:fresh --seed
```

---

## Security Best Practices

1. **Never commit .env files**
   - Already protected by .gitignore ✅
   
2. **Use different credentials per environment**
   - Development: Use .env
   - Production: Use .env.production (separate credentials)

3. **Rotate credentials regularly**
   - APP_KEY: Every 6 months
   - API tokens: Every 3 months
   - Database: Every 6 months
   
4. **Store production secrets securely**
   - Use environment variables on server
   - Consider AWS Secrets Manager or Laravel Secrets
   - Never store in version control

5. **Monitor for unauthorized access**
   - Check Fonnte usage dashboard
   - Review Flare error logs
   - Monitor Mailtrap sent emails

---

## Emergency: Credentials Compromised

If you believe credentials have been compromised:

1. **Immediately rotate ALL credentials**
2. **Force logout all users**:
   ```bash
   docker-compose exec app php artisan cache:clear
   docker-compose exec app php artisan session:flush  # If you have this
   ```
3. **Review recent activity**:
   - Check Fonnte sent messages
   - Review Flare error logs
   - Check Mailtrap sent emails
   - Review application activity logs
4. **Consider notifying users** if data may have been accessed

---

## Need Help?

- Laravel documentation: https://laravel.com/docs/encryption
- Fonnte support: https://fonnte.com/support
- Flare docs: https://flareapp.io/docs
- Mailtrap support: https://mailtrap.io/support

**Last Updated**: 2026-01-16  
**Version**: 1.0
