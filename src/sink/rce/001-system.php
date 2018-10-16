<?php


function system($command, &$return_var = null) {
    prvd_check_rce($command, prvd_translate("Remote Command Execute"));
    return call_user_func_array(PRVD_RENAME_PREFIX."system", array($command, &$return_var));
}
