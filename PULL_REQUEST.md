# Fix Content-Type matching, add Livewire navigate support, and `@pan` Blade directive

## Summary

This PR fixes two bugs and adds a new feature to improve the Pan developer experience:

### 1. Content-Type header case sensitivity (Bug Fix)

The `InjectJavascriptLibrary` middleware performed a strict equality check against `text/html; charset=UTF-8` (uppercase). However, Laravel's response objects return `text/html; charset=utf-8` (lowercase) in many scenarios — particularly when using Blade views and Livewire full-page components. This caused the Pan JavaScript to never be injected into the page, silently breaking all analytics tracking.

**Before:**
```php
if ($response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
```

**After:**
```php
if (str_starts_with((string) $response->headers->get('Content-Type'), 'text/html')) {
```

The fix uses `str_starts_with` to match any `text/html` Content-Type regardless of charset casing or additional parameters, which is consistent with how browsers interpret the Content-Type header.

### 2. Livewire `wire:navigate` support (Enhancement)

Pan's client-side JavaScript listened for Inertia's `inertia:start` event to reset impression tracking on page navigation, but had no equivalent listener for Livewire's `wire:navigate` SPA-style transitions. This meant that when using `wire:navigate`, navigating between pages would not re-track impressions for `data-pan` elements on the new page.

The fix adds a `livewire:navigated` event listener that resets the impression, hover, and click tracking arrays and re-scans for visible `data-pan` elements — matching the existing behavior for Inertia navigation. If Livewire is not installed, the listener simply never fires (same pattern as the existing Inertia listener).

### 3. `@pan` Blade directive (Feature)

Adds a `@pan` Blade directive as a cleaner alternative to writing `data-pan="..."` manually:

```blade
{{-- Before --}}
<button data-pan="tab-1">Tab 1</button>

{{-- After --}}
<button @pan('tab-1')>Tab 1</button>
```

Supports variables and expressions:

```blade
<button @pan('tab-' . $tab->slug)>{{ $tab->name }}</button>
<button @pan($analyticsName)>Click me</button>
```

## Changes

- **`src/Adapters/Laravel/Providers/PanServiceProvider.php`** — Register `@pan` Blade directive
- **`src/Adapters/Laravel/Http/Middleware/InjectJavascriptLibrary.php`** — Use `str_starts_with` for case-insensitive Content-Type matching
- **`resources/js/src/main.ts`** — Add `livewire:navigated` event listener for SPA navigation support
- **`resources/js/src/types.ts`** — Add `livewireNavigatedListener` to `GlobalState` type
- **`resources/js/dist/pan.iife.js`** — Rebuilt compiled JavaScript
- **`tests/.../InjectJavascriptLibraryTest.php`** — Added test for lowercase charset Content-Type
- **`tests/.../BladeDirectiveTest.php`** — Added tests for `@pan` directive (static string, variable, expression)
- **`README.md`** — Added `@pan` directive documentation and updated Livewire compatibility note

## Test Plan

- [x] All existing tests pass
- [x] New test verifies JS injection with lowercase `charset=utf-8`
- [x] Existing tests confirm JS injection with uppercase charset (returned by plain string routes)
- [x] Existing test confirms no injection for `text/plain` Content-Type
- [x] `@pan` directive compiles to correct `data-pan` attribute with static strings, variables, and expressions
- [x] Verified in a real Laravel 12 + Livewire 4 application with `wire:navigate`
