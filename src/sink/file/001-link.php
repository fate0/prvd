<?php


function link($target, $link) {
    prvd_check_path($target, prvd_translate("Arbitrary File Access"));
    prvd_check_path($link, prvd_translate("Arbitrary File Access"));
    return call_user_func(PRVD_RENAME_PREFIX."link", $target, $link);
}
