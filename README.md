# Laravel Chained Commands

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-build]][link-build]
[![StyleCI][ico-styleci]][link-styleci]
[![PhpStan][ico-phpstan]][link-phpstan]

> [!NOTE]
> I found this unfinished code on my computer. Figured I'd publish it in case anyone would find it useful. Don't expect
> a lot. I also won't be merging PRs or checking issues. **You're on your own, kid**.

## Installation
You can install this package via [Composer](http://getcomposer.org):

```bash
composer require sven/laravel-chained-commands
```

### Registering the service provider
Next, add the `ServiceProvider` to your `providers` array in `config/app.php`:

```php
'providers' => [
    ...
    Sven\LaravelChainedCommands\ServiceProvider::class,
];
```

If you would like to load this package in certain environments only, take a look
at [`sven/env-providers`](https://github.com/svenluijten/env-providers).

## Usage
Publish the configuration file:

```sh
php artisan vendor:publish --provider="Sven\\LaravelChainedCommands\\ServiceProvider"
```

You should now have a new file at `config/chained-commands.php` where you can define your command chains:

```php
return [
    RootCommand::class => [
        FirstCommand::class => ['argument' => 'value'],
        SecondCommand::class => ['--option' => 'option-value', '--boolean-option' => false],
        ThirdCommand::class,
    ],
];
```

> [!NOTE]
> Be wary of your command chains "going in circles", where commands `A` and `B` both call each other. If this happens,
> an exception will be thrown.

## Contributing
All contributions (pull requests, issues and feature requests) are
welcome. Make sure to read through the [CONTRIBUTING.md](CONTRIBUTING.md) first,
though. See the [contributors page](../../graphs/contributors) for all contributors.

## License
`sven/laravel-chained-commands` is licensed under the MIT License (MIT). Please see the
[license file](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/sven/laravel-chained-commands.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-green.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sven/laravel-chained-commands.svg?style=flat-square
[ico-build]: https://img.shields.io/github/actions/workflow/status/svenluijten/laravel-chained-commands/run-tests.yml?branch=main&style=flat-square
[ico-styleci]: https://styleci.io/repos/680535861/shield
[ico-phpstan]: https://img.shields.io/badge/phpstan-enabled-blue.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/sven/laravel-chained-commands
[link-downloads]: https://packagist.org/packages/sven/laravel-chained-commands/stats
[link-build]: https://github.com/svenluijten/laravel-chained-commands/actions/workflows/run-tests.yml
[link-styleci]: https://styleci.io/repos/680535861
[link-phpstan]: https://github.com/phpstan/phpstan
