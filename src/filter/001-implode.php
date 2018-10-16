<?php


function implode($glue, $pieces=NULL) {
    $result = call_user_func(PRVD_RENAME_PREFIX."implode", $glue, $pieces);

    if (PRVD_TAINT_ENABLE) {
        if (prvd_xcheck($glue)) {
            prvd_xmark($result);
        } else if (prvd_xcheck($pieces)) {
            prvd_xmark($result);
        }
    }

    return $result;
}
