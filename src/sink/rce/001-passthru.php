<?php


function passthru($command, &$return_var) {
    prvd_check_rce($command, prvd_translate("Remote Command Execute"));
    return call_user_func_array(PRVD_RENAME_PREFIX."passthru", array($command, &$return_var));
}
