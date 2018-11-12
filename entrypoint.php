<?php

$PRVD_FUZZER_DSN = getenv("PRVD_FUZZER_DSN");
$PRVD_SENTRY_DSN = getenv("PRVD_SENTRY_DSN");
$PRVD_TAINT_ENABLE = getenv("PRVD_TAINT_ENABLE");
$PRVD_TANZI = getenv("PRVD_TANZI");
$PRVD_LOG_FILE = getenv("PRVD_LOG_FILE");

$config_file = '/data/prvd/src/Config.php';

$content = file_get_contents($config_file);

if ($PRVD_FUZZER_DSN)
    $content = str_replace('define("PRVD_FUZZER_DSN", "")',
        'define("PRVD_FUZZER_DSN", "'.addslashes($PRVD_FUZZER_DSN).'")', $content);

if ($PRVD_SENTRY_DSN)
    $content = str_replace('define("PRVD_SENTRY_DSN", "")',
        'define("PRVD_SENTRY_DSN", "'.addslashes($PRVD_SENTRY_DSN).'")', $content);

if ($PRVD_TAINT_ENABLE)
    $content = str_replace('define("PRVD_TAINT_ENABLE", true)',
        'define("PRVD_TAINT_ENABLE", '.$PRVD_TAINT_ENABLE.')', $content);

if ($PRVD_TANZI)
    $content = str_replace('define("PRVD_TANZI", "xtanzi")',
        'define("PRVD_TANZI", "'.addslashes($PRVD_TANZI).'")', $content);

if ($PRVD_LOG_FILE)
    $content = str_replace('define("PRVD_LOG_FILE", "/tmp/prvd.log")',
        'define("PRVD_LOG_FILE", "'.addslashes($PRVD_LOG_FILE).'")', $content);

file_put_contents($config_file, $content);

system("apache2-foreground");