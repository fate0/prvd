<?php

define("PRVD_FUZZER_USRENAME", "admin");
define("PRVD_FUZZER_PASSWORD", "password");

if (!defined('PRVD_RENAME_PREFIX'))
    define("PRVD_RENAME_PREFIX", "prvd_");

if (!defined("PRVD_XTANZI"))
    define("PRVD_TANZI", "xtanzi");


define("DEBUG", false);


// Copy from Sentry, This file is part of Raven.
class CurlHandler
{
    protected $join_timeout;
    protected $multi_handle;
    protected $options;
    protected $requests;

    public function __construct($options, $join_timeout = 5)
    {
        $this->options = $options;
        $this->multi_handle = curl_multi_init();
        $this->requests = array();
        $this->join_timeout = $join_timeout;
    }

    public function __destruct()
    {
        $this->join();
    }

    public function enqueue($url, $method = 'GET', $headers = array(), $data = null)
    {
        if (DEBUG) {
            echo json_encode(array(
                "method" => $method,
                "url" => $url,
                "headers" => $headers,
                "body" => $data,
            ), JSON_PRETTY_PRINT);
            return;
        }

        $ch = curl_init();

        $new_headers = array();
        foreach ($headers as $key => $value) {
            array_push($new_headers, $key .': '. $value);
        }
        // XXX(dcramer): Prevent 100-continue response form server (Fixes GH-216)
        $new_headers[] = 'Expect:';

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $new_headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt_array($ch, $this->options);

        if (isset($data)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_multi_add_handle($this->multi_handle, $ch);

        $fd = (int)$ch;
        $this->requests[$fd] = 1;

        $this->select();

        return $fd;
    }

    public function join($timeout = null)
    {
        if (!isset($timeout)) {
            $timeout = $this->join_timeout;
        }
        $start = time();
        do {
            $this->select();
            if (count($this->requests) === 0) {
                break;
            }
            usleep(10000);
        } while ($timeout !== 0 && time() - $start < $timeout);
    }

    protected function select()
    {
        $active = false;

        do {
            $mrc = curl_multi_exec($this->multi_handle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($this->multi_handle) !== -1) {
                do {
                    $mrc = curl_multi_exec($this->multi_handle, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            } else {
                return;
            }
        }

        while ($info = curl_multi_info_read($this->multi_handle)) {
            $ch = $info['handle'];
            $fd = (int)$ch;

            curl_multi_remove_handle($this->multi_handle, $ch);

            if (!isset($this->requests[$fd])) {
                return;
            }

            unset($this->requests[$fd]);
        }
    }
}


function unparse_url($parsed_url) {
    $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
    $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
    $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
    $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
    $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
    $pass     = ($user || $pass) ? "$pass@" : '';
    $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
    $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
    $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
    return "$scheme$user$pass$host$port$path$query$fragment";
}


function build_data_files($boundary, $fields, $files) {
    $data = '';
    $eol = "\r\n";

    $delimiter = '-------------' . $boundary;

    foreach ($fields as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
            . $content . $eol;
    }

    foreach ($files as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $content[0] . '"' . $eol
            . 'Content-Transfer-Encoding: binary'.$eol
        ;

        $data .= $eol;
        $data .= $content[1] . $eol;
    }
    $data .= "--" . $delimiter . "--".$eol;

    return $data;
}


function parse_qs($query_string) {
    $result = array();
    foreach (explode('&', $query_string) as $param) {
        $i = explode('=', $param);
        $result[$i[0]] = $i[1];
    }

    return $result;
}


class Fuzzer {
    static public $payloads = array('\'"<'. PRVD_TANZI.'>./../'. PRVD_TANZI);
    public $request;
    private $curl_handler = null;

    function __construct($request)
    {
        $options = array(
            CURLOPT_VERBOSE => true,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT => 2,
        );

        $this->curl_handler = new CurlHandler($options);
        $this->request = $request;
    }

    public function start()
    {
        var_dump($this->request);
        if (isset($this->request['query'])) $this->fuzz_query();
        if (isset($this->request['data'])) $this->fuzz_body();
        if (isset($this->request['files'])) $this->fuzz_files();
        if (isset($this->request['cookies'])) $this->fuzz_cookies();
        if (isset($this->request['headers'])) $this->fuzz_headers();

        $this->curl_handler->join();
    }

    private function fuzz_query()
    {
        $query = $this->request['query'];
        $fuzz_queries = Fuzzer::fuzz_value($query);

        foreach ($fuzz_queries as $fuzz_query) {
            $fuzz_request = $this->request;
            $fuzz_request['query'] = $fuzz_query;
            $this->make_request($fuzz_request, 'query');
        }
    }

    private function fuzz_body()
    {
        $body = $this->request['data'];
        $fuzz_queries = Fuzzer::fuzz_value($body);

        foreach ($fuzz_queries as $fuzz_query) {
            $fuzz_request = $this->request;
            $fuzz_request['data'] = $fuzz_query;
            $this->make_request($fuzz_request, 'body');
        }
    }

    private function fuzz_cookies()
    {
        $cookie = $this->request['cookies'];
        $fuzz_queries = Fuzzer::fuzz_value($cookie);

        foreach ($fuzz_queries as $fuzz_query) {
            $fuzz_request = $this->request;
            $fuzz_request['cookies'] = $fuzz_query;
            $this->make_request($fuzz_request, 'cookies');
        }
    }

    private function fuzz_files()
    {
        $files = $this->request['files'];
        foreach ($files as $key => $file) {
            $fuzz_files = Fuzzer::add_value($file['name']);
            foreach ($fuzz_files as $fuzz_file) {
                $fuzz_request = $this->request;
                $fuzz_request['files'][$key]['name'] = $fuzz_file;
                $this->make_request($fuzz_request, 'body');
            }

            $fuzz_files = Fuzzer::add_value('');
            foreach ($fuzz_files as $fuzz_file) {
                $fuzz_request = $this->request;
                $fuzz_request['files'][$key]['data'] = $fuzz_file;
                $this->make_request($fuzz_request, 'body');
            }
        }
    }

    private function fuzz_headers()
    {
        $headers = $this->request['headers'];
        $fuzz_headers = Fuzzer::fuzz_value($headers);

        foreach ($fuzz_headers as $fuzz_header) {
            $fuzz_request = $this->request;
            $fuzz_request['headers'] = $fuzz_header;
            $this->make_request($fuzz_request, "headers");
        }
    }

    static function add_value($value)
    {
        $result = array();
        foreach (Fuzzer::$payloads as $payload) {
            array_push($result, $value.$payload);
        }

        return $result;
    }

    static function fuzz_value($data)
    {
        $reqs = array();

        $_fuzz_value = function (&$value) use (&$data, &$reqs, &$_fuzz_value) {
            if (is_array($value)) {
                foreach ($value as $each_key => &$each_value) {
                    $new_values = $_fuzz_value($each_value);

                    if (!$new_values) continue;

                    foreach ($new_values as $new_value) {
                        $old_value = $each_value;
                        $value[$each_key] = $new_value;
                        array_push($reqs, unserialize(serialize($data)));
                        $value[$each_key] = $old_value;
                    }
                }
            } elseif (is_string($value)) {
                $r = Fuzzer::add_value($value);
                return $r;
            }
        };

        $_fuzz_value($data);
        return $reqs;
    }

    private function make_request($req, $fuzz_origin=null)
    {
        $method = strtoupper($req['method']);
        $headers = $req['headers'];
        $content_type = $headers["Content-Type"];
        $body = null;
        $files = array();

        if ($fuzz_origin === "query" && $req['query']) {
            $parsed_url = parse_url($req['url']);
            $parsed_url['query'] = http_build_query($req['query']);
            $url = unparse_url($parsed_url);
        } else {
            $url = $req['url'];
        }

        if ($fuzz_origin === "body" && $req['data']) {
            if (stripos($content_type, 'application/x-www-form-urlencoded') !== false) {
                $body = http_build_query($req['data']);
            } elseif (stripos($content_type, 'application/json')) {
                $body = json_encode($req['data']);
            }
        } else {
            $body = $req['data'];
        }

        if (stripos($content_type, 'multipart/form-data') !== false) {
            $body = parse_qs(http_build_query($req['data']));

            if (isset($req['files']) && $req['files']) {
                foreach ($req['files'] as $key => $data) {
                    if (isset($req['files'][$key]['data']))
                        $files[$key] = array($req['files'][$key]['name'], $req['files'][$key]['data']);
                    else
                        $files[$key] = array($req['files'][$key]['name'], '!PNG Hello');
                }
            }

            $boundary = uniqid();
            $delimiter = '-------------' . $boundary;

            $body = build_data_files($boundary, $body, $files);
            $headers['Content-Type'] = 'multipart/form-data; boundary='.$delimiter;
        }

        if ($fuzz_origin === 'cookies' && $req['cookies']) {
            $cookie_string = http_build_query($req['cookies']);
            $cookie_string = str_replace('&', ';', $cookie_string);
            $headers['Cookie'] = $cookie_string;
        }

        $headers['prvd-fuzzer'] = 'halo_from_fate0';
        if (isset($headers['Content-Length'])) {
            unset($headers['Content-Length']);
        }

        $this->curl_handler->enqueue($url, $method, $headers, $body);
    }
}


if (!isset($_SERVER['HTTP_X_SENTRY_AUTH'])) {
    echo "forbidden";
    return;
};


$sentry_info = array();
$auth = $_SERVER['HTTP_X_SENTRY_AUTH'];
foreach(explode(",", $auth) as $i) {
    list($key, $value) = explode("=", $i);
    $sentry_info[trim($key)] = trim($value);
}


if ($sentry_info['sentry_key'] != PRVD_FUZZER_USRENAME || $sentry_info['sentry_secret'] != PRVD_FUZZER_PASSWORD) {
    echo "access deny";
    return;
}


$data = file_get_contents('php://input');
if (!$data) {
    echo "require data";
    return;
}


if (function_exists('gzcompress'))
    $data = gzuncompress(base64_decode($data));
$data = json_decode($data, true);


$fuzzer = new Fuzzer($data['request']);
$fuzzer->start();
