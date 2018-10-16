<?php


function prvd_do_ucall($call, $params) {
    prvd_check_callback($call, $params, prvd_translate("Remote Code Execute"));
}


xregister_opcode_callback(XMARK_DO_UCALL, 'prvd_do_ucall');
