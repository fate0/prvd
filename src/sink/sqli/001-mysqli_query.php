<?php


function mysqli_query($link, $query, $resultmode=MYSQLI_STORE_RESULT) {
    prvd_check_sqli($query, prvd_translate("Sql Injection"));
    return call_user_func(PRVD_RENAME_PREFIX."mysqli_query", $link, $query, $resultmode);
}
