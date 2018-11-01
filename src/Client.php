<?php


class PRVD_Sentry_Client extends Raven_Client
{
    public function __construct($options_or_dsn = null, $options = array())
    {
        parent::__construct($options_or_dsn, $options);
    }

    public function captureVuln($message, $level='error', $stack=true)
    {
        $stack = debug_backtrace();
        array_shift($stack);
        $this->captureMessage($message, array(), $level, $stack);
    }
}

class PRVD_Fuzzer_Client extends Raven_Client
{
    public function __construct($options_or_dsn = null, $options = array())
    {
        if (is_array($options_or_dsn)) {
            $options = array_merge($options_or_dsn, $options);
        }

        if (!is_array($options_or_dsn) && !empty($options_or_dsn)) {
            $dsn = $options_or_dsn;
        } elseif (!empty($_SERVER['SENTRY_DSN'])) {
            $dsn = @$_SERVER['SENTRY_DSN'];
        } elseif (!empty($options['dsn'])) {
            $dsn = $options['dsn'];
        } else {
            $dsn = null;
        }

        if (!empty($dsn)) {
            $options = array_merge($options, $this->parseDSN($dsn));
        }

        $options['trace'] = false;
        $options['processors'] = array();
        $options['auto_log_stacks'] = false;

        unset($options['dsn']);
        unset($_SERVER['SENTRY_DSN']);

        parent::__construct($options);
    }

    public static function parseDSN($dsn)
    {
        switch (strtolower($dsn)) {
            case '':
            case 'false':
            case '(false)':
            case 'empty':
            case '(empty)':
            case 'null':
            case '(null)':
                return array();
        }

        $url = parse_url($dsn);
        $scheme = (isset($url['scheme']) ? $url['scheme'] : '');
        if (!in_array($scheme, array('http', 'https'))) {
            throw new InvalidArgumentException(
                'Unsupported Sentry DSN scheme: ' .
                (!empty($scheme) ? $scheme : '<not set>')
            );
        }

        $netloc = (isset($url['host']) ? $url['host'] : null);
        $netloc .= (isset($url['port']) ? ':' . $url['port'] : null);
        $path = (isset($url['path']) ? $url['path'] : '');
        $username = (isset($url['user']) ? $url['user'] : null);
        $password = (isset($url['pass']) ? $url['pass'] : null);

        if (empty($netloc) || empty($username)) {
            throw new InvalidArgumentException('Invalid Sentry DSN: ' . $dsn);
        }

        return array(
            'server' => sprintf('%s://%s%s', $scheme, $netloc, $path),
            'project' => 0,
            'public_key' => $username,
            'secret_key' => $password,
        );
    }

    public function captureRequest()
    {
        if (!static::is_http_request()) return;

        $data = $this->get_http_data();
        $data['platform'] = 'php';
        if (!empty($_GET)) {
            $data['request']['query'] = $_GET;
        }
        if (!empty($_FILES)) {
            $data['request']['files'] = $_FILES;
        }

        $this->process($data);
        $this->send($data);
    }

    public function capture($data, $stack = null, $vars = null)
    {
        // do nothing
    }

    public function install(...$args)
    {
        // do nothing
    }
}
