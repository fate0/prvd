<?php


function prvd_exit_handler($string) {
    prvd_check_xss($string, prvd_translate("Cross Site Scripting"));
}

xregister_opcode_callback(XMARK_EXIT, "prvd_exit_handler");
