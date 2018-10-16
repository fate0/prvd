<?php


function exec($command, &$output=null, &$return_var=null) {
    prvd_check_rce($command, prvd_translate("Remote Command Execute"));
    return call_user_func_array(PRVD_RENAME_PREFIX."exec", array($command, &$output, &$return_var));
}
