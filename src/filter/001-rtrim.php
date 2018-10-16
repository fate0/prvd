<?php


function rtrim($str, ...$args) {
    $result = call_user_func(PRVD_RENAME_PREFIX."rtrim", $str, ...$args);
    if (PRVD_TAINT_ENABLE && prvd_xcheck($str)) {
        prvd_xmark($result);
    }

    return $result;
}