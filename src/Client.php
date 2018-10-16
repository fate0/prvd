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

class PRVD_Fuzz_Client extends Raven_Client
{
    public function __construct($options_or_dsn = null, $options = array())
    {
        $options['trace'] = false;
        $options['processors'] = array();
        $options['auto_log_stacks'] = false;
        parent::__construct($options_or_dsn, $options);
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
