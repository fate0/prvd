<?php

function get_headers($url, ...$args) {
    prvd_check_ssrf($url, prvd_translate("Server Side Request Forgery"));
    return call_user_func(PRVD_RENAME_PREFIX."get_headers", $url, ...$args);
}