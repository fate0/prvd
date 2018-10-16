<?php


function prvd_include_or_eval_handler($param) {
    global $prvd_sentry_client;

    $reported = false;

    if (stripos($param,  PRVD_TANZI) !== false) {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        if ($backtrace[1]['function'] == 'eval') {
            $prvd_sentry_client->captureVuln(prvd_translate("Remote Code Execute"));
            $reported = true;
        } elseif (stripos($param, "../". PRVD_TANZI) !== false || stripos($param, "..\\". PRVD_TANZI) !== false) {
            $prvd_sentry_client->captureVuln(prvd_translate("Remote Code Execute"));
            $reported = true;
        }
    }

    if (PRVD_TAINT_ENABLE && !$reported && prvd_xcheck($param)) {
        $prvd_sentry_client->captureVuln(prvd_translate("Remote Code Execute"), "debug");
    }
}

xregister_opcode_callback(XMARK_INCLUDE_OR_EVAL, "prvd_include_or_eval_handler");
