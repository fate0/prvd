<?php


function prvd_init_dynamic_call_handler($funcname) {
    prvd_check_dynamic_call($funcname, prvd_translate("Remote Code Execute"));
}


xregister_opcode_callback(XMARK_INIT_DYNAMIC_CALL, 'prvd_init_dynamic_call_handler');
