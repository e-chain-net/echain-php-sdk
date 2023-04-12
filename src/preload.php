<?php
FFI::load("fisco-c-api.h");

// opcache_compile_file(__DIR__ . "/dummy.php");

function get_lib_fisco() : FFI{
    return FFI::scope("libFisco");
}