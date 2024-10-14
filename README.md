<p align="center">
    <img src="https://raw.githubusercontent.com/panphp/pan/main/docs/banner.png" height="300" alt="Pan">
    <p align="center">
        <a href="https://github.com/panphp/pan/actions"><img alt="GitHub Workflow Status (main)" src="https://github.com/panphp/pan/actions/workflows/tests.yml/badge.svg"></a>
        <a href="https://packagist.org/packages/panphp/pan"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/panphp/pan"></a>
        <a href="https://packagist.org/packages/panphp/pan"><img alt="Latest Version" src="https://img.shields.io/packagist/v/panphp/pan"></a>
        <a href="https://packagist.org/packages/panphp/pan"><img alt="License" src="https://img.shields.io/packagist/l/panphp/pan"></a>
    </p>
</p>

---

**Pan is a lightweight and privacy-focused PHP analytics library**. It’s designed as a very simple package that you can install via `composer require` and start tracking your pages or components with **a simple `data-pan` attribute**.

At the time of writing, Pan tracks only the following events: impressions, hovers, and clicks. It does not collect any personal information, such as IP addresses, user agents, or any data that could be used to identify a user.

<p align="center">
    <img src="https://raw.githubusercontent.com/panphp/pan/main/docs/banner-command.png" height="300" alt="Pan">
</p>

**Use cases:**
- you have **different tabs** within a page with the same URL, and you want to know **which one is the most viewed**. By adding the `data-pan` attribute to your tabs, you can track this information.
- you have **different register buttons** in your application, and you want to know **which one is the most clicked**. By adding the `data-pan` attribute to your buttons, you can track this information.
- you have different "help" pop-hovers in your application, and you want to know **which one is the most hovered**. By adding the `data-pan` attribute to your pop-hovers, you can track this information.
- and so on...

It works out-of-the-box with your favorite Laravel stack; updating a button color in your "react" won't trigger a new impression, but seeing that same button in a different [Inertia](https://inertiajs.com) page will. Using [Livewire](https://livewire.laravel.com)? No problem, Pan works seamlessly with it too.

Visualize your analytics is as simple as typing `php artisan pan` in your terminal. This command will show you a table with the different analytics you've been tracking, and hopefully, you can use this information to improve your application.

## Get Started

> **Requires [PHP 8.3+](https://php.net/releases/), and [Laravel 11.0+](https://laravel.com)**.

You may use [Composer](https://getcomposer.org) to require Pan into your PHP project:

```bash
composer require panphp/pan
```

After, you may install Pan into your Laravel project using the following command:

```bash
php artisan install:pan
```

Finally, you may start tracking your pages or components adding the `data-pan` attribute to your HTML elements:

```diff
<div>
-    <button>Tab 1</button>
+    <button data-pan="tab-1">Tab 1</button>
-    <button>Tab 2</button>
+    <button data-pan="tab-2">Tab 2</button>
</div>
```

## Visualize your analytics

To visualize your analytics, you may use the `pan` Artisan command:

```bash
php artisan pan
```

## Flush your analytics

To flush your analytics, you may use the `pan:flush` Artisan command:

```bash
php artisan pan:flush
```

## How does it work?

Via middleware, Pan injects a simple JavaScript library into your HTML pages. This library listens to events like `viewed`, `clicked`, or `hovered` and sends the data to your Laravel application. Note that this library does not collect any personal information; such as IP addresses, user agents, or any information that could be used to identify a user.

Also on the client-side, these events are collected in a very performant way and batched together to reduce the number of requests to your server.

On the server-side, Pan only stores: the analytic name, an a counter of how many times the different events were triggered. Via the `pan` Artisan command, you may visualize this data, and hopefully use this information to improve your application.

### Considerations

- By default, Pan only allows 50 or fewer analytics to be stored. This prevents any potential abuse of the system, as the analytics "name" are controlled on the client-side. Open to suggestions on how to improve this.

## License

Pan is open-sourced software licensed under the [MIT license](LICENSE.md).
