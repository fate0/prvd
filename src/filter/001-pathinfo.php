<?php


function pathinfo($path, $options=PATHINFO_DIRNAME|PATHINFO_BASENAME|PATHINFO_EXTENSION|PATHINFO_FILENAME) {
    $result = call_user_func(PRVD_RENAME_PREFIX."pathinfo", $path, $options);
    if (PRVD_TAINT_ENABLE && prvd_xcheck($path)) {
        prvd_xmark($result);
    }

    return $result;
}