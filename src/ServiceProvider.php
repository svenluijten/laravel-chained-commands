<?php

namespace Sven\LaravelChainedCommands;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as LaravelProvider;

class ServiceProvider extends LaravelProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/chained-commands.php' => config_path('chained-commands.php'),
        ], 'chained-commands-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/chained-commands.php', 'chained-commands');

        Event::listen(CommandFinished::class, FireChainedCommands::class);
    }
}
