<?php


function opendir($path, ...$args) {
    prvd_check_path($path, prvd_translate("Arbitrary File Access"));
    return call_user_func(PRVD_RENAME_PREFIX."opendir", $path, ...$args);
}