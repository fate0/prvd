<?php


function rawurlencode($str) {
    $result = call_user_func(PRVD_RENAME_PREFIX."rawurlencode", $str);
    if (PRVD_TAINT_ENABLE && prvd_xcheck($str)) {
        prvd_xmark($result);
    }

    return $result;
}
