<?php

namespace App\Traits;

trait IsSingleton
{
    private static ?self $instance = null;

    private function __construct() {}

    public static function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}
