<?php


function file_get_contents($filename, ...$args) {
    prvd_check_path($filename, prvd_translate("Arbitrary File Access"));
    prvd_check_ssrf($filename, prvd_translate("Server Side Request Forgery"));
    return call_user_func(PRVD_RENAME_PREFIX."file_get_contents", $filename, ...$args);
}
