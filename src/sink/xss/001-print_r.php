<?php


function print_r($expression, ...$args) {
    prvd_check_xss($expression, prvd_translate("Cross Site Scripting"));
    return call_user_func(PRVD_RENAME_PREFIX."print_r", $expression, ...$args);
}
