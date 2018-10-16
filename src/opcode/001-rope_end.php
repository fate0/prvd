<?php


function prvd_rope_end_handler($params) {
    $result = implode($params);
    if (prvd_xcheck($params)) {
        prvd_xmark($result);
    }
    return $result;
}


if (PRVD_TAINT_ENABLE)
    xregister_opcode_callback(XMARK_ROPE_END, 'prvd_rope_end_handler');