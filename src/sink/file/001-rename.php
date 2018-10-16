<?php


function rename($oldname, $newname, ...$args) {
    prvd_check_path($oldname, prvd_translate("Arbitrary File Access"));
    prvd_check_path($newname, prvd_translate("Arbitrary File Access"));
    return call_user_func(PRVD_RENAME_PREFIX."rename", $oldname, $newname, ...$args);
}
