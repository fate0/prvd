<?php


function prvd_echo_handler($string) {
    prvd_check_xss($string, prvd_translate("Cross Site Scripting"));
}

xregister_opcode_callback(XMARK_ECHO, "prvd_echo_handler");

