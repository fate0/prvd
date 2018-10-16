<?php


function pg_send_query_params(...$args) {
    if (is_string($args[0])) {
        $query = $args[0];
    } else {
        $query = $args[1];
    }

    prvd_check_sqli($query, prvd_translate("Sql Injection"));
    return call_user_func(PRVD_RENAME_PREFIX."pg_send_query_params", ...$args);
}