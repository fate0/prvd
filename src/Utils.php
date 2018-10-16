<?php


if (!function_exists('xmark')) {
    function xmark($str) { return false; }
}

if (!function_exists('xcheck')) {
    function xcheck($str) { return false; }
}

if (!function_exists('xregister_opcode_callback')) {
    function xregister_opcode_callback($opcode, $funcname) {}
}


/**
 * 检测变量是否被打上标记
 * @param $var
 * @param bool $recursive
 * @return int
 */
function prvd_xcheck($var, $recursive=true) {
    if (!PRVD_TAINT_ENABLE) return false;

    if (is_string($var)) {
        return xcheck($var);
    } elseif (is_array($var) && $recursive) {
        foreach ($var as $key => &$value) {
            if (prvd_xcheck($value, $recursive)) return true;
        }
    }

    return false;
}


/**
 * 给变量打上标记
 * @param $var
 * @param bool $recursive
 */
function prvd_xmark(&$var, $recursive=true) {
    if (!PRVD_TAINT_ENABLE) return;

    if (is_string($var)) {
        xmark($var);
    } elseif (is_array($var) && $recursive) {
        foreach ($var as $key => &$value) {
            prvd_xmark($value, $recursive);
        }
    }
}


/**
 * 检测 callback 相关变量
 * @param $call
 * @param $params
 * @param $message
 */
function prvd_check_callback($call, $params, $message) {
    global $prvd_sentry_client;
    if (!$prvd_sentry_client) return;

    if (empty($params)) return;

    $calls = array(
        "array_diff_uassoc" => -1,
        "array_diff_ukey" => -1,
        "array_filter" => 1,
        "array_intersect_uassoc" => -1,
        "array_intersect_ukey" => -1,
        "array_map" => 0,
        "array_reduce" => 1,
        "array_udiff" => -1,
        "array_udiff_assoc" => -1,
        "array_udiff_uassoc" => -1,
        "array_uintersect" => -1,
        "array_uintersect_assoc" => -1,
        "array_uintersect_uassoc" => -1,
        "array_walk" => 1,
        "array_walk_recursive" => 1,
        "iterator_apply" => 1,
        "ob_start" => 0,
        "preg_replace_callback" => 1,
        "register_shutdown_function" => 0,
        "register_tick_function" => 0,
        "set_error_handler" => 0,
        "set_exception_handler" => 0,
        "spl_autoload_register" => 0,
        "uasort" => 1,
        "uksort" => 1,
        "usort" => 1,
    );

    $reported = false;

    if (!is_string($call) && !is_array($call)) return;
    if (is_array($call)) $call = $call[0].'::'.$call[1];
    if (!isset($calls[$call])) return;

    $callback_index = $calls[$call];

    if ($callback_index >= 0 && isset($params[$callback_index]))
        $callback = $params[$callback_index];
    elseif ($callback_index < 0 && isset($params[count($params)+$callback_index]))
        $callback = $params[count($params)+$callback_index];
    else
        return;

    if (is_string($callback)) {
        if (stripos($callback,  PRVD_TANZI) !== false) {
            $prvd_sentry_client->captureVuln($message);
            $reported = true;
        }
    } elseif (is_array($callback)) {
        if (is_string($callback[0]) && stripos($callback[0],  PRVD_TANZI) !== false) {
            $prvd_sentry_client->captureVuln($message);
            $reported = true;
        }
        if (is_string($callback[1]) && stripos($callback[1],  PRVD_TANZI) !== false) {
            $prvd_sentry_client->captureVuln($message);
            $reported = true;
        }
    }

    if (PRVD_TAINT_ENABLE && !$reported && prvd_xcheck($callback)) {
        $prvd_sentry_client->captureVuln($message, "debug");
    }
}


/**
 * 检测动态调用函数名是否可控
 * @param $funcname
 * @param $message
 */
function prvd_check_dynamic_call(&$funcname, $message) {
    global $prvd_sentry_client;
    if (!$prvd_sentry_client) return;

    $reported = false;

    if (is_string($funcname)) {
        if (stripos($funcname,  PRVD_TANZI) !== false) {
            $prvd_sentry_client->captureVuln($message);
            $reported = true;
        }
    } else if (is_array($funcname)) {
        if (is_string($funcname[0]) && (stripos($funcname[0],  PRVD_TANZI) !== false)) {
            $prvd_sentry_client->captureVuln($message);
            $reported = true;
        }

        if (is_string($funcname[1]) && (stripos($funcname[1],  PRVD_TANZI) !== false)) {
            $prvd_sentry_client->captureVuln($message);
            $reported = true;
        }
    }

    if (PRVD_TAINT_ENABLE && !$reported && prvd_xcheck($funcname)) {
        $prvd_sentry_client->captureVuln($message, "debug");
    }
}


/**
 * 检测 path 相关变量
 * @param $path
 * @param $message
 */
function prvd_check_path(&$path, $message) {
    global $prvd_sentry_client;
    if (!$prvd_sentry_client) return;

    $reported = false;

    if (stripos($path, "../". PRVD_TANZI) !== false || stripos($path, "..\\". PRVD_TANZI) !== false) {
        $prvd_sentry_client->captureVuln($message);
        $reported = true;
    }

    if (PRVD_TAINT_ENABLE && !$reported && prvd_xcheck($path)) {
        $prvd_sentry_client->captureVuln($message, "debug");
    }
}


/**
 * 检测是否存在 SQL 注入
 * @param $query
 * @param $message
 * @param $error
 */
function prvd_check_sqli(&$query, $message) {
    global $prvd_sentry_client;
    if (!$prvd_sentry_client) return;

    if (prvd_detect_sql_injection($query)) {
        $prvd_sentry_client->captureVuln($message);
    } else if (PRVD_TAINT_ENABLE && prvd_xcheck($query)) {
        $prvd_sentry_client->captureVuln($message, "debug");
    }
}


/**
 * 检测是否存在命令注入
 * @param $command
 * @param $message
 */
function prvd_check_rce(&$command, $message) {
    global $prvd_sentry_client;
    if (!$prvd_sentry_client) return;

    if (prvd_detect_cmd_injection($command)) {
        $prvd_sentry_client->captureVuln($message);
    } else if (PRVD_TAINT_ENABLE && prvd_xcheck($command)) {
        $prvd_sentry_client->captureVuln($message, "debug");
    }
}


/**
 * 检测是否存在 XSS
 * @param $str
 * @param $message
 */
function prvd_check_xss(&$str, $message) {
    global $prvd_sentry_client;
    if (!$prvd_sentry_client) return;

    if (PRVD_TAINT_ENABLE && prvd_xcheck($str)) {
        $prvd_sentry_client->captureVuln($message, "debug");
    }
}


/**
 * 检测是否存在反序列化
 * @param $str
 * @param $message
 */
function prvd_check_unserialize(&$str, $message) {
    global $prvd_sentry_client;
    if (!$prvd_sentry_client) return;

    if (prvd_detect_unserialize($str)) {
        $prvd_sentry_client->captureVuln($message);
    }else if (PRVD_TAINT_ENABLE && prvd_xcheck($str)) {
        $prvd_sentry_client->captureVuln($message, "debug");
    }
}


/**
 * 检测是否存在 SSRF
 * @param $url
 * @param $message
 */
function prvd_check_ssrf(&$url, $message) {
    global $prvd_sentry_client;
    if (!$prvd_sentry_client) return;

    $host = parse_url($url, PHP_URL_HOST);
    if (!$host) $host = $url;

    if (stripos($host,  PRVD_TANZI) !== false) {
        $prvd_sentry_client->captureVuln($message);
    } else if (PRVD_TAINT_ENABLE && prvd_xcheck($url)) {
        $prvd_sentry_client->captureVuln($message, "debug");
    }
}


function prvd_log($content) {
    $file_put_contents = prvd_get_function('file_put_contents');
    $file_put_contents(PRVD_LOG_FILE, $content."\n", FILE_APPEND);
}


/**
 * 加载文件
 * @param $pattern
 */
function prvd_load_file($pattern) {
    $glob = prvd_get_function("glob");
    $ksort = prvd_get_function("ksort");
    $basename = prvd_get_function("basename");

    $file_list = $glob($pattern);

    $result_list = array();
    foreach ($file_list as $absfilename) {
        if (in_array($basename($absfilename), $result_list)) {
            prvd_log("error: function ".$basename($absfilename)." already exists in ".$file_list[$basename($absfilename)]);
            continue;
        }

        $result_list[$basename($absfilename)] = $absfilename;
    }

    $ksort($result_list);
    foreach ($result_list as $filename => $absfilename) {
        $funcname = preg_replace("/\d{3}\-/", "", $filename);
        $funcname = preg_replace("/.php$/", "", $funcname);

        if (!function_exists(PRVD_RENAME_PREFIX.$funcname) && !class_exists(PRVD_RENAME_PREFIX.$funcname)) {
            prvd_log("error: function/class ".PRVD_RENAME_PREFIX.$funcname." not exists");
            continue;
        }

        if (function_exists($funcname) || class_exists($funcname)) {
            prvd_log("error: function/class ".$funcname." already exists");
            continue;
        }

        require($absfilename);
    }
}


/**
 * 加载 opcode 相关文件
 * @param $pattern
 */
function prvd_load_opcode($pattern) {
    $glob = prvd_get_function("glob");
    $ksort = prvd_get_function("ksort");
    $basename = prvd_get_function("basename");

    $file_list = $glob($pattern);

    $result_list = array();
    foreach ($file_list as $absfilename) {
        $result_list[$basename($absfilename)] = $absfilename;
    }

    $ksort($result_list);
    foreach ($result_list as $filename => $absfilename) {
        require($absfilename);
    }
}


$prvd_translate_lang = array(
    "Remote Code Execute" => "远程代码执行",
    "Remote Command Execute" => "远程命令执行",
    "Sql Injection" => "SQL 注入",
    "Cross Site Scripting" => "跨站脚本攻击",
    "File Inclusion" => "文件包含",
    "Arbitrary File Access" => "任意文件读取",
    "Arbitrary File Write" => "任意文件写入",
    "Arbitrary File Delete" => "任意文件删除",
    "Directory Traversal" => "目录遍历",
    "Server Side Request Forgery" => "服务器端请求伪造",
);


/**
 * @param $str
 * @return string
 */
function prvd_translate($str) {
    global $prvd_translate_lang;
    if (isset($prvd_translate_lang[$str])) {
        return $prvd_translate_lang[$str];
    } else {
        return $str;
    }
}


const PRVD_KEYWORD_ALLOW_CHARS = 'abcdefghijklmnopqrstuvwxyz0123456789$_';
const PRVD_WHITESPACE = " \t\n\r\v\f";
/**
 * 检测 SQL 语句是否异常
 * @param $sql_string
 * @return bool
 */
function prvd_detect_sql_injection($sql_string) {
    $strlen = prvd_get_function('strlen');
    $stripos = prvd_get_function('stripos');
    $substr = prvd_get_function('substr');
    $in_array = prvd_get_function('in_array');

    $cur_pos = 0;
    $sql_string_len = $strlen($sql_string);


    while ($cur_pos < $sql_string_len) {
        while ($stripos(PRVD_WHITESPACE, $substr($sql_string, $cur_pos, 1)) !== FALSE) $cur_pos ++;

        if ($stripos('\'"`', $substr($sql_string, $cur_pos, 1)) !== FALSE) {
            // handle literal
            $quote = $substr($sql_string, $cur_pos, 1);
            $cur_pos ++;
            while ($cur_pos < $sql_string_len) {
                if ($quote === $substr($sql_string, $cur_pos, 1))
                    break;
                elseif ($in_array($substr($sql_string, $cur_pos, 2), array('\\\\', '\\\'', '\\"')))
                    $cur_pos += 1;

                $cur_pos ++;
            }

            // broken sql statement
            if ($cur_pos == $sql_string_len) return TRUE;

            $cur_pos ++;

        } elseif ('/*' === $substr($sql_string, $cur_pos, 2)) {
            // handle comment
            $cur_pos += 2;
            $comment_start = $cur_pos;
            while ($cur_pos < $sql_string_len) {
                if ('*/' === $substr($sql_string, $cur_pos, 2)) break;
                $cur_pos ++;
            }
            if ($stripos($substr($sql_string, $comment_start, $cur_pos-$comment_start),  PRVD_TANZI) !== FALSE)
                return TRUE;
            $cur_pos += 2;

        } elseif ('--' === $substr($sql_string, $cur_pos, 2)) {
            // handle inline comment
            $cur_pos = $sql_string_len;

        } elseif ($stripos(PRVD_KEYWORD_ALLOW_CHARS, $substr($sql_string, $cur_pos, 1)) === FALSE) {
            // handle op
            $cur_pos ++;

        } else {
            // handle keyword
            $keyword_start = $cur_pos;
            while ($cur_pos < $sql_string_len) {
                if ($stripos(PRVD_KEYWORD_ALLOW_CHARS, $substr($sql_string, $cur_pos, 1)) === FALSE) break;
                $cur_pos ++;
            }
            if ($stripos($substr($sql_string, $keyword_start, $cur_pos-$keyword_start),  PRVD_TANZI) !== FALSE)
                return TRUE;
        }
    }

    return FALSE;
}


/**
 * 检测 CMD 语句是否异常
 * @param $cmd_string
 * @return bool
 */
function prvd_detect_cmd_injection($cmd_string) {
    // TODO: 目前只考虑了逃脱引号的情况，在双引号内的情况暂未支持
    $strlen = prvd_get_function('strlen');
    $stripos = prvd_get_function('stripos');
    $substr = prvd_get_function('substr');
    $in_array = prvd_get_function('in_array');

    $cur_pos = 0;
    $cmd_string_len = $strlen($cmd_string);

    while ($cur_pos < $cmd_string_len) {
        while ($stripos(PRVD_WHITESPACE, $substr($cmd_string, $cur_pos, 1)) !== FALSE) $cur_pos++;


        if ($stripos('\'"', $substr($cmd_string, $cur_pos, 1)) !== FALSE) {
            // handle literal
            $quote = $substr($cmd_string, $cur_pos, 1);
            $cur_pos ++;
            while ($cur_pos < $cmd_string_len) {
                if ($quote === $substr($cmd_string, $cur_pos, 1))
                    break;
                elseif ($in_array($substr($cmd_string, $cur_pos, 2), array('\\\\', '\\\'', '\\"')))
                    $cur_pos += 1;

                $cur_pos ++;
            }

            // broken cmd statement
            if ($cur_pos == $cmd_string_len) return TRUE;

            $cur_pos ++;
        } elseif ($stripos(PRVD_KEYWORD_ALLOW_CHARS, $substr($cmd_string, $cur_pos, 1)) === FALSE) {
            // handle op
            $cur_pos ++;

        } else {
            // handle keyword
            $keyword_start = $cur_pos;
            while ($cur_pos < $cmd_string_len) {
                if ($stripos(PRVD_KEYWORD_ALLOW_CHARS, $substr($cmd_string, $cur_pos, 1)) === FALSE) break;
                $cur_pos ++;
            }
            if ($stripos($substr($cmd_string, $keyword_start, $cur_pos-$keyword_start),  PRVD_TANZI) !== FALSE)
                return TRUE;
        }
    }

    return FALSE;
}


function prvd_detect_unserialize($serialize_string) {
    // TODO:

    return FALSE;
}