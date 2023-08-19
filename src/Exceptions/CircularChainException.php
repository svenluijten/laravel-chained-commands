<?php

namespace Sven\LaravelChainedCommands\Exceptions;

use InvalidArgumentException;

class CircularChainException extends InvalidArgumentException
{
    public static function build(string $command): CircularChainException
    {
        $message = sprintf('[%s] is a circular dependency in the command chain. Make sure each command is only called once.', $command);

        return new self($message);
    }
}
