<p align="center">
    <img src="https://raw.githubusercontent.com/panphp/pan/main/docs/banner.png" height="300" alt="Skeleton Php">
    <p align="center">
        <a href="https://github.com/panphp/pan/actions"><img alt="GitHub Workflow Status (main)" src="https://github.com/panphp/pan/actions/workflows/tests.yml/badge.svg"></a>
        <a href="https://packagist.org/packages/panphp/pan"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/panphp/pan"></a>
        <a href="https://packagist.org/packages/panphp/pan"><img alt="Latest Version" src="https://img.shields.io/packagist/v/panphp/pan"></a>
        <a href="https://packagist.org/packages/panphp/pan"><img alt="License" src="https://img.shields.io/packagist/l/panphp/pan"></a>
    </p>
</p>

---

Pan is a lightweight and privacy-focused PHP analytics library. It is a simple and easy-to-use library that allows you to track your most "used" pages or components.

## Get Started

> **Requires [PHP 8.3+](https://php.net/releases/)**, and [Laravel 11.0+](https://laravel.com).

You may use [Composer](https://getcomposer.org) to require Pan into your PHP project:

```bash
composer require panphp/pan
```

After, you may install Pan into your Laravel project using the following command:

```bash
php artisan install:pan
```

Finally, you may start tracking your pages or components adding the `data-pan` attribute to your HTML elements:

```html
<button data-pan="my-button">Click me</button>
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

On the server-side, Pan only stores: the analytic name, an a counter of how many times the different events were triggered. Via the `pan` Artisan command, you may visualize this data, and hopefully use this information to improve your application.

## License

Pan is open-sourced software licensed under the [MIT license](LICENSE.md).
