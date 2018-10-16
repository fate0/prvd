<?php


function prvd_init_user_call_handler($funcname) {
    prvd_check_dynamic_call($funcname, prvd_translate("Remote Code Execute"));
}


xregister_opcode_callback(XMARK_INIT_USER_CALL, 'prvd_init_user_call_handler');
