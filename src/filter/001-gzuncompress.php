<?php


function gzuncompress($data, ...$args) {
    $result = call_user_func(PRVD_RENAME_PREFIX."gzuncompress", $data, ...$args);
    if (PRVD_TAINT_ENABLE && prvd_xcheck($data)) {
        prvd_xmark($result);
    }

    return $result;
}