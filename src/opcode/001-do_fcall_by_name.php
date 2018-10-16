<?php


function prvd_do_fcall_by_name($call, $params) {
    prvd_check_callback($call, $params, prvd_translate("Remote Code Execute"));
}


xregister_opcode_callback(XMARK_DO_FCALL_BY_NAME, 'prvd_do_fcall_by_name');
