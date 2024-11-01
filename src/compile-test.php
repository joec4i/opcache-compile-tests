<?php
var_dump(opcache_compile_file(__DIR__ . '/releases/v1/functions.php'));

// expecting `bool(false)` since opcache_compile_file() shouldn't have executed the compiled file.
var_dump(in_array('foo', get_defined_functions()['user']));

// expecting successful compilation: `bool(true)`
var_dump(opcache_compile_file(__DIR__ . '/releases/v2/functions.php'));
