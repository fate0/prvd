<?php


function file($filename, ...$args) {
    prvd_check_path($filename, prvd_translate("Arbitrary File Access"));
    return call_user_func(PRVD_RENAME_PREFIX."file", $filename, ...$args);
}
