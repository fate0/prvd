<?php


if (!extension_loaded('xmark')) {
    trigger_error("xmark not installed", E_USER_WARNING);
    return;
}


define("PRVD_RENAME_PREFIX", "prvd_");

$prvd_sentry_client = null;
$prvd_fuzzer_client = null;


function prvd_get_function($funcname) {
    if (function_exists(PRVD_RENAME_PREFIX.$funcname)) {
        return PRVD_RENAME_PREFIX.$funcname;
    } else if (function_exists($funcname)) {
        return $funcname;
    } else {
        exit("error: function ".$funcname." does not exists");
    }
}


$prvd_dirname = prvd_get_function("dirname");
define('PRVD_ABSPATH', $prvd_dirname( __FILE__ ) . '/' );
require(PRVD_ABSPATH."Config.php");
require(PRVD_ABSPATH."Utils.php");


// mark 输入变量
prvd_xmark($_GET, true);
prvd_xmark($_POST, true);
prvd_xmark($_COOKIE, true);
prvd_xmark($_FILES, true);
prvd_xmark($_REQUEST, true);

foreach ($_SERVER as $key => &$value) {
    if (stripos($key, 'HTTP_') === 0) {
        prvd_xmark($value);
    }
}


// 1. 加载 sink
prvd_load_file(PRVD_ABSPATH."sink/*/*.php");

// 2. 加载 filter
prvd_load_file(PRVD_ABSPATH."filter/*.php");

// 3. 加载 opcode
prvd_load_opcode(PRVD_ABSPATH."opcode/*.php");


// delay require
require(PRVD_ABSPATH."../vendor/autoload.php");
require(PRVD_ABSPATH."Client.php");

$prvd_sentry_client = new PRVD_Sentry_Client(PRVD_SENTRY_DSN);
$prvd_fuzzer_client = new PRVD_Fuzzer_Client(PRVD_FUZZER_DSN);


// 如果 header 信息不是来源于 fuzzer，那就发送相关信息给 fuzzer
if (PRVD_FUZZER_DSN && !isset($_SERVER['HTTP_PRVD_FUZZER'])) {
    $prvd_fuzzer_client->captureRequest();
}
