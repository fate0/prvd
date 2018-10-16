<?php


function pg_prepare(...$args) {
    if (is_string($args[0])) {
        $query = $args[1];
    } else {
        $query = $args[2];
    }

    prvd_check_sqli($query, prvd_translate("Sql Injection"));
    return call_user_func(PRVD_RENAME_PREFIX."pg_prepare", ...$args);
}