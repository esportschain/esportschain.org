<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rediscli extends CI_Driver_Library {

    public $valid_drivers;

    public $CI;

    function __construct() {

        $this->CI = &get_instance();

        $this->valid_drivers = array('default');
    }
}