<?php declare(strict_types=1);

use Nui\Support\Collection;

if (!function_exists('dump'))
{
    function dump($value)
    {
        var_dump($value);
    }
}


if (!function_exists('collect'))
{
    function collect($values)
    {
        return new Collection($values);
    }
}

if (!function_exists('throw_if'))
{
    function throw_if($condition, $exception, ...$parameters)
    {
        if ($condition) {
            throw (is_string($exception) ? new $exception(...$parameters) : $exception);
        }

        return $condition;
    }
}

if (! function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}


