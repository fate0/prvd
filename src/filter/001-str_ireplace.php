<?php


function str_ireplace($search, $replace, $subject, ...$args) {
    $result = call_user_func(PRVD_RENAME_PREFIX."str_ireplace", $search, $replace, $subject, ...$args);

    if (PRVD_TAINT_ENABLE) {
        if (prvd_xcheck($replace)) {
            prvd_xmark($result);
        } elseif (prvd_xcheck($subject)) {
            prvd_xmark($result);
        }
    }

    return $result;
}
