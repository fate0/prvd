<?php


function rmdir($dirname, ...$args){
    prvd_check_path($dirname, prvd_translate("Arbitrary File Delete"));
    return call_user_func(PRVD_RENAME_PREFIX."rmdir", $dirname, ...$args);
}