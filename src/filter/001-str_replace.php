<?php


function str_replace($search, $replace, $subject, &$count=NULL) {
    // reference params
    $result = call_user_func_array(PRVD_RENAME_PREFIX."str_replace", array($search, $replace, $subject, &$count));

    if (PRVD_TAINT_ENABLE) {
        if (prvd_xcheck($replace)) {
            prvd_xmark($result);
        } elseif (prvd_xcheck($subject)) {
            prvd_xmark($result);
        }
    }

    return $result;
}
