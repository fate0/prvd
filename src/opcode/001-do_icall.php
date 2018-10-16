<?php


function prvd_do_icall($call, $params) {
    prvd_check_callback($call, $params, prvd_translate("Remote Code Execute"));
}


xregister_opcode_callback(XMARK_DO_ICALL, 'prvd_do_icall');
