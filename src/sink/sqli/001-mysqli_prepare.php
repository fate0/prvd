<?php


function mysqli_prepare($link, $query) {
    prvd_check_sqli($query, prvd_translate("Sql Injection"));
    return call_user_func(PRVD_RENAME_PREFIX."mysqli_prepare", $link, $query);
}