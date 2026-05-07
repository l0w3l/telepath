# Agent Instructions: Telepath

Compact, high-signal guidance for agents working on the Telepath Laravel Telegram SDK.

## 🛠 Developer Commands

- **Test**: `composer test` (Runs Pest)
- **Static Analysis**: `composer analyse` (Runs PHPStan at level 5)
- **Format**: `composer format` (Runs Laravel Pint)
- **Run Bot**: `php artisan telepath:run` (Long polling)
- **Set Webhook**: `php artisan telepath:hook:set {url}`

## 🏗 Architecture & Entrypoints

- **Logic Entrypoint**: Users define bot logic in `routes/telegram.php` using the `Telepath` facade.
- **Service Provider**: `Lowel\Telepath\TelepathServiceProvider` handles package boot, route loading, and command registration.
- **Router**: `Lowel\Telepath\Core\Router\TelegramRouter` is the heart of the package, resolving updates to handlers.
- **Generators**: Custom artisan commands for handlers, middlewares, keyboards, and conversations are in `src/Commands`.
- **Drivers**: Supports both `LongPoolingDriverTelegram` and `WebhookDriverTelegram`.

## 🧪 Testing Quirks

- **Framework**: Uses **Pest** with **Orchestra Testbench**.
- **Mocking**: `tests/TestCase.php` binds `TelegramAppFactoryMock` to intercept API calls.
- **Update Simulation**: Use `$this->updatesMockBuilder` in tests to generate mock Telegram updates.
- **Database**: Migrations are loaded manually in `TestCase::getEnvironmentSetUp` from `database/migrations`.

## 📝 Conventions

- **Namespace**: `Lowel\Telepath\` maps to `src/`.
- **Strict Types**: Enforced by `ArchTest.php`.
- **Facades**: 
  - `Telepath`: Main router/bot interface.
  - `Extrasense`: Profile and config access.
  - `Paranormal`: Exception handling wrapper.
- **Config**: Default config is in `config/telepath.php`. Users publish it to their app.

## ⚠️ Gotchas

- **PHP Version**: Requires PHP ^8.3.
- **Dependencies**: Heavily relies on `phptg/bot-api` for Telegram types and API methods.
- **Async**: Supports async update handling via Laravel Jobs (`HandleTelegramUpdateRequestJob`).
- **Conversations**: State-based multi-step interactions are stored via `ConversationStorage`.
