<?php


function sprintf($format, ...$args) {
    $result = call_user_func(PRVD_RENAME_PREFIX."sprintf", $format, ...$args);

    if (PRVD_TAINT_ENABLE) {
        if (prvd_xcheck($format)) {
            prvd_xmark($result);
        } else {
            foreach ($args as &$arg) {
                if (prvd_xcheck($arg)) {
                    prvd_xmark($result);
                    break;
                }
            }
        }
    }

    return $result;
}