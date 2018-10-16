<?php


function strtolower($str) {
    $result = call_user_func(PRVD_RENAME_PREFIX."strtolower", $str);
    if (PRVD_TAINT_ENABLE && prvd_xcheck($str)) {
        prvd_xmark($result);
    }

    return $result;
}
