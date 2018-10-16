<?php


function base64_decode($data, ...$args) {
    $result = call_user_func(PRVD_RENAME_PREFIX."base64_decode", $data, ...$args);
    if (PRVD_TAINT_ENABLE && prvd_xcheck($data)) {
        prvd_xmark($result);
    }

    return $result;
}
