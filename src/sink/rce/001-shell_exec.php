<?php

// `` 反引号 就是 shell_exec
function shell_exec($command) {
    prvd_check_rce($command, prvd_translate("Remote Command Execute"));
    return call_user_func(PRVD_RENAME_PREFIX."shell_exec", $command);
}
