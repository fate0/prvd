<?php


function glob($pattern, ...$args) {
    prvd_check_path($pattern, prvd_translate("Arbitrary File Access"));
    return call_user_func(PRVD_RENAME_PREFIX."glob", $pattern, ...$args);
}