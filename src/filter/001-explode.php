<?php


function explode($delimiter, $string, ...$args) {
    $result = call_user_func(PRVD_RENAME_PREFIX."explode", $delimiter, $string, ...$args);
    if (PRVD_TAINT_ENABLE && prvd_xcheck($string)) {
        prvd_xmark($result);
    }

    return $result;
}
