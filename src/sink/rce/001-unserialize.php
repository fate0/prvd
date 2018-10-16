<?php

function unserialize($str, ...$args) {
    prvd_check_unserialize($str, prvd_translate("Remote Command Execute"));
    return call_user_func(PRVD_RENAME_PREFIX.'unserialize', $str, ...$args);
}