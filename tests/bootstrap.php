<?php


error_reporting(E_ALL | E_STRICT);


const TEST_PAYLOAD = '\'"<'. PRVD_TANZI.'>./../'. PRVD_TANZI;


class Dummy_Sentry_Client {
    public $captured = false;

    public function captureVuln(...$args) {
        $this->captured = true;
    }
}