<?php


function curl_exec($ch) {
    // $url 并没有被 mark 上
    $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    prvd_check_ssrf($url, prvd_translate("Server Side Request Forgery"));
    return call_user_func(PRVD_RENAME_PREFIX."curl_exec", $ch);
}