<?php


function json_decode($string, ...$args) {
    $result = call_user_func(PRVD_RENAME_PREFIX."json_decode", $string, ...$args);
    if (PRVD_TAINT_ENABLE && prvd_xcheck($string)) {
        prvd_xmark($result);
    }

    return $result;
}