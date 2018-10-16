<?php


if (!extension_loaded('xmark')) {
    trigger_error("xmark not installed", E_USER_WARNING);
    return;
}


define("PRVD_FUZZ_DSN", "");
define("PRVD_SENTRY_DSN", "");
define("PRVD_TAINT_ENABLE", true);
define("PRVD_RENAME_PREFIX", "prvd_");
define("PRVD_TANZI", "xtanzi");
define("PRVD_LOG_FILE", "/tmp/xmark.log");


$prvd_sentry_client = null;
$prvd_fuzz_client = null;


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
require(PRVD_ABSPATH."Utils.php");


// mark 输入变量
prvd_xmark($_GET, true);
prvd_xmark($_POST, true);
prvd_xmark($_COOKIE, true);
prvd_xmark($_FILES, true);

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
$prvd_fuzz_client = new PRVD_Fuzz_Client(PRVD_FUZZ_DSN);


// 如果 header 信息不是来源于 fuzzer，那就发送相关信息给 fuzzer
if (PRVD_FUZZ_DSN && !isset($_SERVER['HTTP_PRVD_FUZZ'])) {
    $prvd_fuzz_client->captureRequest();
}
