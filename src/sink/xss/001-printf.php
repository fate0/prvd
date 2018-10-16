<?php


function printf($format, ...$args) {
    $str = sprintf($format, ...$args);
    prvd_check_xss($str, prvd_translate("Cross Site Scripting"));
    return call_user_func(PRVD_RENAME_PREFIX."printf", $format, ...$args);
}
