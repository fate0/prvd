<?php


function mysqli_real_query($link, $query) {
    prvd_check_sqli($query, prvd_translate("Sql Injection"));
    return call_user_func(PRVD_RENAME_PREFIX."mysqli_real_query", $link, $query);
}