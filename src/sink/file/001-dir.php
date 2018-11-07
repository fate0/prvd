<?php

function dir($directory, ...$args) {
    prvd_check_path($directory, prvd_translate("Arbitrary File Access"));
    return call_user_func(PRVD_RENAME_PREFIX."dir", $directory, ...$args);
}