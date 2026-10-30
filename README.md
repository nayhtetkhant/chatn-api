# chatN Backend

Laravel API for chatN: friends, chats, messages, and call coordination with LiveKit. Authentication uses Sanctum bearer tokens; real-time events use Laravel Reverb. This repository includes the API and Laravel's welcome page, not a complete chat client.

## Requirements

- PHP 8.3+ and Composer 2, with extensions required by `composer.lock` (including PDO MySQL).
- MySQL and an empty database for local development (`chatn` by default).
- Node.js 20.19+ or 22.12+ and npm for the bundled Vite assets.
- Redis and the PHP Redis extension for call room state and the busy-user endpoint. Room state uses Redis directly, even when `CACHE_STORE=database`.
- A LiveKit server or account for calls; Reverb for WebSocket delivery.

Run `composer check-platform-reqs` after installation to check PHP requirements.

## Local setup

From the repository root:

```bash
composer install
cp .env.example .env
php artisan key:generate
npm ci --ignore-scripts
```

If `.env` already exists, keep it instead of overwriting it. Edit these values for your local MySQL instance and create the database before migrating:

```dotenv
APP_NAME=chatN
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chatn
DB_USERNAME=your_local_user
DB_PASSWORD=your_local_password
```

Then build the assets and create the tables:

```bash
php artisan migrate
npm run build
composer run dev
```

`composer run dev` starts the HTTP server at `http://localhost:8000`, a database queue listener, log streaming, and Vite. It does **not** start MySQL, Redis, Reverb, or LiveKit.

Verify that Laravel responds:

```bash
curl -i http://localhost:8000/up
```

For an API-only session with assets already built, run `php artisan serve` and, in a separate terminal, `php artisan queue:work`.

The default environment uses database-backed sessions, cache, and queues. Migrations create the required tables. `composer run setup` is another setup script, but it generates a key, migrates with `--force`, installs npm packages, and builds assets; configure the database first and use it only for initial setup.

## Try the API

The base URL is `http://localhost:8000/api/v1`. Send `Accept: application/json` on API requests and `Content-Type: application/json` for JSON bodies.

### Create a local account

There is no implemented public registration endpoint. Create a development account through Tinker:

```bash
php artisan tinker
```

```php
\App\Models\User::factory()->create([
    'name' => 'Local Developer',
    'email' => 'developer@example.test',
    'user_name' => '@user_demo',
    'password' => 'local-development-password',
]);
```

Exit Tinker before continuing. Alternatively, `php artisan db:seed` adds sample users and related chat data when `APP_ENV=local`. Seeded usernames are random; inspect them in Tinker with `\App\Models\User::pluck('user_name');`. Factory-generated users have the password `password`. Use these accounts only locally; seeders add data and are not an idempotent reset.

### Log in and use a token

Login requires `userName` (camel case), beginning with `@user_`:

```bash
curl -sS http://localhost:8000/api/v1/auth/login \
  -H 'Accept: application/json' \
  -H 'Content-Type: application/json' \
  -d '{"userName":"@user_demo","password":"local-development-password"}'
```

A successful login returns:

```json
{
  "success": true,
  "code": "success",
  "message": "Success",
  "data": {
    "isSuccess": true,
    "id": 1,
    "token": "<bearer-token>"
  }
}
```

The ID varies. Copy `data.token` and use it for authenticated requests:

```bash
curl -sS http://localhost:8000/api/v1/auth/me \
  -H 'Accept: application/json' \
  -H 'Authorization: Bearer <bearer-token>'
```

Check `data.isSuccess` when logging in: invalid credentials currently return `false` inside the success envelope rather than an HTTP authentication error.

### API map

All paths below are relative to `/api/v1`. Everything except login requires `auth:sanctum`.

| Area | Implemented controller actions |
| --- | --- |
| Authentication | `POST /auth/login`, `GET /auth/me` |
| Users | `GET /users/{user}`, `GET /users/busy` (requires Redis) |
| Friends | `GET /friends` |
| Chats | `GET /chats`, `POST /chats`, `GET /chats/{chat}`, `PUT/PATCH /chats/{chat}` |
| Messages | `GET /chats/{chat}/messages`, `POST /chats/{chat}/messages` |
| Calls | `POST /chats/{chat}/calls` |
| Call lifecycle | `POST /chats/{chat}/calls/{call}/accept`, `/success`, `/onHangUp`, `/invite`, `/inviteAccept` |

For payload fields and allowed enum values, see [request validators](app/Http/Requests/api/v1) and [enums](app/Enums). Response fields are defined in [API resources](app/Http/Resources/api/v1). Most actions use the success envelope above; the message list returns a Laravel resource collection directly.

`routes/api.php` also registers resource routes whose handlers are empty or missing, including user writes, friend writes, chat deletion, several message/call actions, and logout. A listed route does not guarantee working behavior. Inspect the controller before integrating an action outside the map above. List registered routes with:

```bash
php artisan route:list --path=api
```

## Real-time events and calls

### Reverb

The example environment uses `BROADCAST_CONNECTION=log`, so it will not deliver WebSocket events. Add the following to `.env` for a local Reverb server, replacing the placeholder credentials with your own matching values:

```dotenv
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=chatn-local
REVERB_APP_KEY=your-local-key
REVERB_APP_SECRET=your-local-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

Run `php artisan config:clear`, restart the development processes, and start Reverb in another terminal:

```bash
php artisan reverb:start
```

Keep the queue listener running for queued broadcasts. Rebuild assets after changing `VITE_*` settings when using a static build.

Events use private `user.{id}` and `chat.{chatId}` channels, plus public `chat-public`. The `global` presence channel is also defined. Private/presence subscriptions authenticate at `POST /api/broadcasting/auth` with the Sanctum bearer token. See [events](app/Events), [channel authorization](routes/channels.php), and [Echo configuration](resources/js/echo.js) for the client integration. The current chat channel authorization callback permits any authenticated user; review access control before exposing sensitive chat data.

### LiveKit and Redis

Add credentials for your LiveKit instance (these settings are absent from `.env.example`):

```dotenv
LIVEKIT_HOST=https://your-livekit-host
LIVEKIT_API_KEY=your-api-key
LIVEKIT_SECRET_KEY=your-secret-key
MAX_ROOM_MEMBERS=20

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null
```

`LIVEKIT_HOST` is the server API URL used by the PHP room client. The calling client also needs its LiveKit connection configuration. The backend creates rooms and participant tokens; Redis tracks rooms, participants, and busy users. Start both services before exercising call endpoints. Configuration lives in [services.php](config/services.php); implementation lives in [Livekit services](app/Services/Livekit) and [Room services](app/Services/Room).

## Contributing

1. Set up the project and reproduce the behavior you want to change.
2. Keep HTTP validation in Form Requests, response serialization in API Resources, and application logic in services. Follow nearby naming and formatting conventions.
3. Add focused Pest coverage for changed behavior, especially authentication, authorization, and call state transitions.
4. Run the relevant checks before opening a pull request:

```bash
composer test
vendor/bin/pint --test
npm run build
```

Use `vendor/bin/pint` to format edited PHP files (pass file paths to limit its scope). The current tests are starter examples; passing them does not verify chat or call workflows. Tests use in-memory SQLite, an array cache/session driver, and a synchronous queue as configured in `phpunit.xml`; install PDO SQLite to run them.

Describe the problem, resulting behavior, validation performed, and any migration or configuration changes in your pull request. Keep credentials and `.env` files out of commits.

### Code map

| Path | Purpose |
| --- | --- |
| `routes/api.php`, `bootstrap/app.php` | API routes, authentication wiring, health endpoint |
| `app/Http/Controllers/api/v1` | HTTP entry points |
| `app/Http/Requests/api/v1`, `app/Http/Resources/api/v1` | Request validation and response serialization |
| `app/Services/User` | Authentication, friends, chats, messages, and call workflows |
| `app/Services/Room`, `app/Services/Livekit` | Redis room state and LiveKit integration |
| `app/Models`, `app/Enums` | Persistence relationships and domain values |
| `app/Events`, `routes/channels.php` | Broadcast events and channel authorization |
| `database/migrations`, `database/seeders`, `database/factories` | Schema and development data |
| `tests/Feature`, `tests/Unit` | Pest tests |

## Troubleshooting and deployment notes

- **Database connection errors or missing tables:** check `DB_*`, create the database, and run `php artisan migrate`.
- **Missing application key:** run `php artisan key:generate` on a new installation.
- **Vite manifest missing:** run `npm ci --ignore-scripts` and `npm run build`.
- **Changes to environment settings have no effect:** run `php artisan config:clear` and restart long-running workers/Reverb.
- **Events do not arrive:** check the broadcast connection, matching Reverb credentials, queue listener, and subscription authentication.
- **Calls or busy-user lookup fail:** check Redis connectivity, the PHP Redis extension, and LiveKit credentials/connectivity.
- **Storage permission errors:** ensure the runtime user can write to `storage/` and `bootstrap/cache/`.

[dockerfile.laravel](dockerfile.laravel) builds a PHP 8.4 FPM image listening on port 9000. It requires an external FastCGI web server, database, Redis, runtime environment, and separately managed workers/Reverb. It does not build Vite assets or run migrations. No Compose stack is included. [startLaravel.sh](startLaravel.sh) optionally installs a mounted Caddy root certificate before starting FPM.

For deployment, serve `public/`, build assets before release, provide secrets through the environment, set `APP_DEBUG=false`, and run migrations as a controlled release step. The included Dockerfile is a starting point, not a complete deployment stack.
