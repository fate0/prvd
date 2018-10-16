<?php


function unlink($filename, ...$args) {
    prvd_check_path($filename, prvd_translate("Arbitrary File Delete"));
    return call_user_func(PRVD_RENAME_PREFIX."unlink", $filename, ...$args);
}