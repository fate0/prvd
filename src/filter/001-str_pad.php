<?php

function str_pad($input, ...$args) {
    $result = call_user_func(PRVD_RENAME_PREFIX."str_pad", $input, ...$args);
    if (PRVD_TAINT_ENABLE && prvd_xcheck($input)) {
        prvd_xmark($result);
    }

    return $result;
}

