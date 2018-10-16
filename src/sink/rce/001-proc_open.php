<?php


function proc_open($cmd, $descriptorspec, &$pipes, ...$args) {
    prvd_check_rce($cmd, prvd_translate("Remote Command Execute"));
    $params = array($cmd, $descriptorspec, &$pipes) + $args;
    return call_user_func_array(PRVD_RENAME_PREFIX."proc_open", $params);
}
