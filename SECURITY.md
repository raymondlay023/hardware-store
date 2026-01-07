# Security Policy

## Supported Versions

We release patches for security vulnerabilities for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 0.1.x   | :white_check_mark: |

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

If you discover a security vulnerability within BangunanPro, please send an email to security@bangunanpro.com. All security vulnerabilities will be promptly addressed.

### What to Include

Please include the following information in your report:

- Description of the vulnerability
- Steps to reproduce the vulnerability
- Possible impact
- Suggested fix (if any)

### Response Timeline

- **Initial Response:** Within 48 hours
- **Status Update:** Within 7 days
- **Fix Timeline:** Depends on severity
  - Critical: Within 24-72 hours
  - High: Within 1 week
  - Medium: Within 2 weeks
  - Low: Next release cycle

### Disclosure Policy

- Please allow us reasonable time to fix the vulnerability before public disclosure
- We will credit you for the discovery (unless you prefer to remain anonymous)
- We will notify you when the fix is released

## Security Best Practices

When using BangunanPro, we recommend:

1. **Keep Updated**
   - Always run the latest version
   - Subscribe to security updates

2. **Server Security**
   - Use HTTPS (SSL/TLS) in production
   - Keep PHP and dependencies updated
   - Use strong database passwords
   - Enable firewall on your server

3. **Application Security**
   - Change default credentials immediately
   - Use strong passwords (minimum 12 characters)
   - Enable two-factor authentication (when available)
   - Regularly audit user access

4. **Data Protection**
   - Regular database backups
   - Encrypt sensitive data
   - Limit file upload sizes and types
   - Implement rate limiting

5. **Access Control**
   - Follow principle of least privilege
   - Regularly review user permissions
   - Disable inactive accounts
   - Monitor admin actions

## Known Security Features

BangunanPro includes the following security features:

- ✅ CSRF protection on all forms
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade templating)
- ✅ Password hashing (bcrypt)
- ✅ Rate limiting on authentication
- ✅ Audit logging for sensitive actions
- ✅ Role-based access control
- ✅ Secure session management

## Security Checklist for Production

Before deploying to production:

- [ ] `APP_DEBUG=false` in `.env`
- [ ] Set strong `APP_KEY`
- [ ] Use `HTTPS` with valid SSL certificate
- [ ] Set appropriate file permissions (755 for directories, 644 for files)
- [ ] Restrict database access to localhost or specific IPs
- [ ] Enable firewall (UFW, iptables, etc.)
- [ ] Set up regular automated backups
- [ ] Configure error logging to file (not display)
- [ ] Review and restrict API access (if applicable)
- [ ] Enable rate limiting on public routes
- [ ] Set up monitoring and alerting
- [ ] Implement web application firewall (WAF) - optional but recommended

## Contact

For security-related questions or concerns:
- Email: security@bangunanpro.com
- Create security advisory: [GitHub Security Advisories](https://github.com/your-repo/security/advisories)

Thank you for helping keep BangunanPro and our users safe!
