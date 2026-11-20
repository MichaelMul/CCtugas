<p align="  enter"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## Cloud Computing 2025 Registration Portal

### Local setup

Requirements: PHP 8.4 with `pdo_sqlite`, Composer, and Node.js/npm.

```bash
composer install
npm install
```

Create a local environment file (`Copy-Item .env.example .env` in PowerShell, or `cp .env.example .env` on Linux/macOS), then generate the application key and prepare SQLite:

```bash
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

The application uses `database/database.sqlite`; the file is ignored by Git. Keep the database directory and file writable by the PHP process. Do not run `key:generate` on an existing production installation because it invalidates encrypted data and sessions.

### SMTP configuration

Set `MAIL_USERNAME`, `MAIL_PASSWORD`, and `MAIL_FROM_ADDRESS` to credentials and a sender address you control. The example uses Purelymail SMTP on port 587 with STARTTLS. Set `APP_URL` to the application's public HTTPS origin so signed confirmation links use the correct host. `REGISTRATION_NOTIFY_EMAILS` is a comma-separated recipient list; it defaults to the two program contacts in `.env.example`.

No real message is sent during automated tests. Until valid SMTP credentials are provided, email delivery is not verified; registrations are retained if delivery fails, and the success page explains that the email could not be delivered. For local UI work, set `MAIL_MAILER=log` to keep messages out of external inboxes.

### Tests

```bash
php artisan test
```

The suite uses an in-memory SQLite database, an isolated array cache, and Laravel's mail fake. It does not send actual email.

### Production deployment checklist

- Provision PHP 8.4 with `pdo_sqlite`, Git, and `tar`. Node.js/npm and Composer are used by GitHub Actions, not required on the CloudPanel server.
- Set production `APP_KEY`, `APP_URL` (HTTPS), `APP_ENV=production`, and `APP_DEBUG=false` in the hosting environment. Supply SMTP credentials through the host's secret store or an untracked `.env`; never commit credentials.
- Ensure `storage/`, `bootstrap/cache/`, and the SQLite database directory/file are writable by the application process. Back up the SQLite database regularly and keep the database outside the public web root.
- Run `php artisan migrate --force` during deployment. Do not commit the production SQLite file, `.env`, `vendor/`, or `node_modules/`.
- Verify SMTP delivery using a test address controlled by the operator before opening registration. Do not send test messages to program recipients without authorization.

### GitHub Actions CI/CD to CloudPanel

The `.github/workflows/ci-cd.yml` workflow follows the professor's test/build then deploy structure, adapted to this Laravel 13 app's PHP 8.4 and SQLite stack. Pull requests and pushes to `main` run Pest and build the Vite assets. A successful push to `main` packages production Composer dependencies and assets in GitHub Actions, uploads the package over SSH, then asks CloudPanel to pull `main`, install the package, run migrations, and refresh Laravel's production caches. This workflow deploys to `mail.r0xyy.my.id`; a different CloudPanel site needs its own deployment path and URL.

Before enabling automatic deployment:

1. In GitHub, open **Settings → Secrets and variables → Actions** and add these repository secrets:
   - `CLOUDPANEL_HOST`: server hostname or IPv4 address.
   - `CLOUDPANEL_PORT`: SSH port (usually `22`; optional if using that default).
   - `CLOUDPANEL_USER`: the CloudPanel site user (for example, `r0xyy-mail`).
   - `CLOUDPANEL_PATH`: absolute Laravel project path (for example, `/home/r0xyy-mail/htdocs/mail.r0xyy.my.id`).
   - `CLOUDPANEL_SSH_KEY`: the private key for a dedicated Actions-to-CloudPanel SSH key.
   - `CLOUDPANEL_KNOWN_HOSTS`: verified SSH host-key line(s) for the server and port.
2. Generate a dedicated Ed25519 key pair for Actions to SSH into CloudPanel. Add only the `.pub` key to the CloudPanel site's SSH authorized keys; store the private key as `CLOUDPANEL_SSH_KEY`.
3. Give the CloudPanel site user a separate read-only GitHub deploy key so the server can run `git pull` on this private repository. In the CloudPanel terminal as the site user, generate the key and display only its public half:
   ```bash
   mkdir -p ~/.ssh
   ssh-keygen -t ed25519 -N '' -C 'cloudpanel-readonly-CCtugas' -f ~/.ssh/cc_tugas_github
   cat ~/.ssh/cc_tugas_github.pub
   ```
   Add that public key under the GitHub repository's **Settings → Deploy keys** with write access disabled. Keep the private key only on CloudPanel; it is not a GitHub Actions secret. Configure the site user's SSH client and switch the checkout's remote to SSH:
   ```bash
   cat >> ~/.ssh/config <<'EOF'
   Host github.com
     HostName github.com
     User git
     IdentityFile ~/.ssh/cc_tugas_github
     IdentitiesOnly yes
   EOF
   chmod 600 ~/.ssh/config ~/.ssh/cc_tugas_github
   git -C /home/r0xyy-mail/htdocs/mail.r0xyy.my.id remote set-url origin git@github.com:MichaelMul/CCtugas.git
   ```
   Verify GitHub's SSH host-key fingerprint against its published fingerprints before adding GitHub to `~/.ssh/known_hosts`, then confirm access with `git -C /home/r0xyy-mail/htdocs/mail.r0xyy.my.id ls-remote origin refs/heads/main`.
4. Verify the CloudPanel server's SSH host-key fingerprint through a trusted provider/server console before saving the matching `ssh-keyscan -H <host>` output as `CLOUDPANEL_KNOWN_HOSTS`. GitHub publishes its SSH fingerprints in its [SSH key fingerprints documentation](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/githubs-ssh-key-fingerprints). Strict host-key checking is enabled for Actions-to-CloudPanel SSH.
5. Confirm the deployment user can write to the project path and run `git`, `tar`, and `php` over SSH. The public site document root must point to `<project path>/public`; keep `.env` in the project root. The workflow leaves `.env`, SQLite files, and `storage/` untouched and does not generate or replace the production `APP_KEY`.
6. Push a small change to `main` and verify the **Actions** run completes. Pull requests run tests/builds but do not deploy.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
