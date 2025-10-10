<?php

use Illuminate\Support\Str;

if (! function_exists('str_uuid')) {
    function str_uuid(): string
    {
        return (string) Str::uuid();
    }
}
