<?php

system("service mysql start");

$PRVD_FUZZ_DSN = getenv("PRVD_FUZZ_DSN");
$PRVD_SENTRY_DSN = getenv("PRVD_SENTRY_DSN");
$PRVD_TAINT_ENABLE = getenv("PRVD_TAINT_ENABLE");
$PRVD_TANZI = getenv("PRVD_TANZI");

$config_file = '/data/prvd/src/Config.php';

$content = file_get_contents($config_file);

if ($PRVD_FUZZ_DSN)
    $content = str_replace('define("PRVD_FUZZ_DSN", "")',
        'define("PRVD_FUZZ_DSN", "'.addslashes($PRVD_FUZZ_DSN).'")', $content);

if ($PRVD_SENTRY_DSN)
    $content = str_replace('define("PRVD_SENTRY_DSN", "")',
        'define("PRVD_SENTRY_DSN", "'.addslashes($PRVD_SENTRY_DSN).'")', $content);

if ($PRVD_TAINT_ENABLE)
    $content = str_replace('define("PRVD_TAINT_ENABLE", true)',
        'define("PRVD_TAINT_ENABLE", '.$PRVD_TAINT_ENABLE.')', $content);

if ($PRVD_TANZI)
    $content = str_replace('define("PRVD_TANZI", "xtanzi")',
        'define("PRVD_TANZI", "'.addslashes($PRVD_TANZI).'")', $content);

file_put_contents($config_file, $content);

system("apache2-foreground");