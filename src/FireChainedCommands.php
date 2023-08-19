<?php

namespace Sven\LaravelChainedCommands;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Sven\LaravelChainedCommands\Exceptions\CircularChainException;

class FireChainedCommands
{
    /** @var array<string> */
    protected static array $history = [];

    public function __construct(
        protected Repository $config,
        protected Application $laravel
    ) {
    }

    public function __invoke(CommandFinished $event): void
    {
        /** @var array<class-string, array<int|string, string|class-string>> $chainConfig */
        $chainConfig = $this->config->get('chained-commands');

        /** @var Collection<string, array<int|string, string|class-string>> $chainedCommands */
        $chainedCommands = Collection::make($chainConfig)
            ->mapWithKeys(function ($chain, $command) {
                /** @var \Illuminate\Console\Command $cmd */
                $cmd = $this->laravel->make($command);

                return [$cmd->getName() => $chain];
            });

        // Checks if any commands should be chained off of this one.
        if (!$chainedCommands->has($event->command)) {
            return;
        }

        /** @var Collection<class-string|string|int, string|array<int|class-string|string, null|array<string, string>>> $commands */
        $commands = Collection::make($chainedCommands->get($event->command));

        if (in_array(needle: $event->command, haystack: self::$history, strict: true)) {
            throw CircularChainException::build($event->command);
        }

        if (!$event->output?->isQuiet()) {
            $event->output?->writeln('<info>Executing registered "chained" commands for ['.$event->command.'].</info>');
        }

        // Save the "root" command that fired this event in history.
        self::$history[] = $event->command;

        $commands->each(function ($arguments, $command) use ($event) {
            // If the current command we're iterating over was not an associative
            // value in the config, we'll assume it was a `class-string` with
            // no options and arguments given.
            if (is_int($command) && is_string($arguments)) {
                [$command, $arguments] = [$arguments, []];
            }

            if (!$event->output?->isQuiet()) {
                $event->output?->writeln('  <info>Running ['.$command.'].</info>');
            }

            /**
             * @var string $command
             * @var array<string, mixed> $arguments
             */
            Artisan::call($command, $arguments, $event->output);
        });
    }
}
