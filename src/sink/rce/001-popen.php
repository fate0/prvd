<?php


function popen($command, ...$args) {
    prvd_check_rce($command, prvd_translate("Remote Command Execute"));
    return call_user_func(PRVD_RENAME_PREFIX."popen", $command, ...$args);
}