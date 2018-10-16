<?php


function scandir($directory, ...$args) {
    prvd_check_path($directory, prvd_translate("Directory Traversal"));
    return call_user_func(PRVD_RENAME_PREFIX."scandir", $directory, ...$args);
}