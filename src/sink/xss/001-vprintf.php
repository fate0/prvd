<?php


function vprintf($format, $args) {
    $str = vsprintf($format, $args);
    prvd_check_xss($str, prvd_translate("Cross Site Scripting"));
    return call_user_func(PRVD_RENAME_PREFIX."vprintf", $format, ...$args);
}
