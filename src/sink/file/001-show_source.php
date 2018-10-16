<?php


function show_source($filename, ...$args) {
    prvd_check_path($filename, prvd_translate("Arbitrary File Access"));
    return call_user_func(PRVD_RENAME_PREFIX."show_source", $filename, ...$args);
}