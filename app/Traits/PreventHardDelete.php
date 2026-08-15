<?php

namespace App\Traits;

use Exception;

trait PreventHardDelete
{
    public static function bootPreventHardDelete()
    {
        static::deleting(function ($model) {
            throw new Exception("Financial integrity violation: Permanent deletion of " . class_basename($model) . " records is strictly prohibited.");
        });
    }
}
