<?php


function copy($source, $dest, ...$args) {
    prvd_check_path($source, prvd_translate("Arbitrary File Access"));
    prvd_check_path($dest, prvd_translate("Arbitrary File Access"));

    return call_user_func(PRVD_RENAME_PREFIX."copy", $source, $dest, ...$args);
}