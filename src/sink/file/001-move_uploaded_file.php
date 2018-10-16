<?php


function move_uploaded_file($filename, $destination) {
    prvd_check_path($destination, prvd_translate("Arbitrary File Access"));
    return call_user_func(PRVD_RENAME_PREFIX."move_uploaded_file", $filename, $destination);
}
