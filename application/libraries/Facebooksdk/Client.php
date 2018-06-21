<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/src/Facebook/autoload.php';
/**
 * 调用Auth
 */
class Client
{

    public $center;

    public function __construct($params)
    {

        $this->center = new \Facebook\Facebook($params);
    }
}
