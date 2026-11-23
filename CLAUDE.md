# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Tutu's Foods — a Laravel 12 e-commerce site for a bakery (cakes, cupcakes, seasonal "Shop Christmas" products) with a Filament 4 admin panel. Guest checkout only (no customer accounts); payments via Square and PayPal.

## Commands

```bash
composer dev                 # serve + queue:listen + pail logs + vite, concurrently
npm run build                # production asset build (Vite + Tailwind 3)
composer test                # config:clear then php artisan test
php artisan test tests/Feature/CheckoutPickupOnlyTest.php      # one file
php artisan test --filter seasonal_products_offer_delivery     # one test
vendor/bin/pint              # code style (Laravel Pint)
php artisan paypal:recover --dry-run   # find PayPal orders paid but not completed locally
```

- Tests run against **MySQL** database `tutus_foods_test` (root, no password, 127.0.0.1) as set in `phpunit.xml` — not SQLite. That database must exist locally. Feature tests use `RefreshDatabase`.
- The stock Breeze tests (`tests/Feature/Auth/*`, `ProfileTest`, `ExampleTest`) fail with `RouteNotFoundException` because this app has no `dashboard`/`profile` routes for customers — a known pre-existing state, not a regression.
- Development is on Windows; Python is not installed, so don't rely on it for scripted edits.

## Architecture

**Two separate front ends:**
- Public storefront: `app/Http/Controllers/Web/*` + `resources/views/web/**`, routes in `routes/web.php`. Pages extend `layouts.products` (products/cart/checkout) or `layouts.app` (home, about, blog, etc.). Both layouts contain the cart sidebar JS (`clearCart()`, `/cart/summary`, `/cart/count`) — duplicated, so change both.
- Admin: Filament panel at `/dashboard` (`app/Providers/Filament/AdminPanelProvider.php`), resources auto-discovered from `app/Filament/Resources/<Name>/` with the Filament 4 layout: `Schemas/<Name>Form.php`, `Schemas/<Name>Infolist.php`, `Tables/<Name>Table.php`, `Pages/`. Roles via spatie/laravel-permission.

**Editable site content = single-row "settings" models.** `HomepageSetting`, `AboutUsSettings`, `PrivacyPolicySetting`, `RefundPolicySetting`, `CheckoutPageSetting` each hold one row edited through their Filament resource and read by the storefront (`::firstOrFail()` in `PagesController`, `CheckoutPageSetting::instance()` which `firstOrCreate`s with defaults). To make storefront copy admin-editable: add a nullable column via migration, add to `$fillable`, add the field to the resource's Form + Infolist, keep a code default for when it's empty.

**Cart is session-only** (`session('cart')`, keyed by product id or `id_md5(options)`). `CartController::add` snapshots product fields (price, `pickup_only`, `seasonal`, season dates, `pre_order`, weight) into the session. `CheckoutController::refreshCartFromProducts()` re-reads those from the DB at checkout and before any payment — always use it rather than trusting the session or the request-supplied `cart`. Some cart routes (remove, clear, count, summary) are closures in `routes/web.php`, not controller methods; `/cart/clear` returns JSON only when `expectsJson()` (sidebar fetch) and redirects otherwise (cart page form).

**Checkout / payments** (`app/Http/Controllers/Web/CheckoutController.php`):
- Square: `createSquarePayment` charges, then `createOrderAfterPayment` creates the Order + OrderItems. Post-charge failures must never report failure to the customer (risk of double charge) — they log critical and email admins.
- PayPal: `createPayPalOrder` creates a *pending* local Order with a `cart_snapshot` JSON; `capturePayPalOrder` captures and rebuilds OrderItems from that snapshot. `paypal:recover` reconciles stuck ones.
- Totals and delivery fees are always recomputed server-side (`calculateTotal`, `calculateDeliveryFee` — flat rate or `DeliveryWeightRange` by cart weight with fallback to the option's rate). Never trust client-sent `total`/`delivery_fee`.
- Business rules enforced server-side **before** charging: pickup-only products block delivery (`cartRequiresPickup`). The checkout view (`resources/views/web/checkout/index.blade.php`) enforces the same in JS plus: seasonal products can't be mixed with non-seasonal ones; the seasonal notice comes from the product's `pre_order_expectation`, else `CheckoutPageSetting::seasonalNoticeFor()` (`{date}` placeholder).
- Order emails (`app/Mail/`) are sent in `DB::afterCommit` via Resend.

**Products:** categories `cupcakes`, `loaf-cakes`, `floral-cupcakes` are "stacked" and rendered with `web/products/cupcakes.blade.php`; other categories use `web/products/index.blade.php`. `shop-christmas` is excluded from normal listings and served by `HomeController::shopChristmas` (`web/products/shop-christmas.blade.php`). Product detail opens in a modal (`?product=<slug>` auto-opens it). The mobile "Filter & Categories" toggle script lives once in `layouts/products.blade.php` and binds to `#toggleSidebar` / `#filterSidebar`.

## Gotchas

- **Assets:** both layouts use `@vite` unconditionally (manifest in production). `public/build` is gitignored, so new Tailwind classes only reach production after `npm run build` on the server — use inline styles for small one-off changes if a rebuild isn't planned. Blade executes directives even inside `<!-- -->` HTML comments; use `{{-- --}}` to disable one.
- **Every `@section('x')` that isn't the one-line form must be closed with `@endsection`.** An unclosed section makes Blade print it before `<!DOCTYPE html>`, causing a flash of unstyled content (and "did not close its own output buffers" risky tests). `CartClearAndProductsFilterTest` guards the products and checkout pages.
- Many stale copies sit next to live files (`* copy.php`, `*-old*.php`, `* old.php`, `index_4_12.blade.php`, `index.blade backup.php`). Only the canonically named file is routed/rendered; don't edit the copies, and exclude them when grepping.
- Debug routes (`/admin-test`, `/test-auth`, `/login-test`, `/test-mail`) are only registered when `APP_ENV=local`. `/login-test` logs the visitor in as the first user, so production must never run with `APP_ENV=local`.
- After deploying view changes run `php artisan view:clear`; schema changes need `php artisan migrate --force` on the server.
