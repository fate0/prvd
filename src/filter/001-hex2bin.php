<?php


function hex2bin($data) {
    $result = call_user_func(PRVD_RENAME_PREFIX."hex2bin", $data);
    if (PRVD_TAINT_ENABLE && prvd_xcheck($data)) {
        prvd_xmark($result);
    }

    return $result;
}
