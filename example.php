<?php

/*
 * PHP parameters and Options handler
 * 
 * Usage sample
 * 
 * @package System
 * @author Vallo Reima
 * @copyright (C)2015
 */

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
ini_set('log_errors', false);

/* sample default options */
$def = array(
    'sgn' => true,
    'exf' => ['*.min.*'],
    'sfx' => '_pkd',
    'aon' => [],
    'arc' => 'zip',
    'tml' => 30
);

/* sample request */
$_GET = array('sgn' => '', 'arc' => '7z','aon' => ['add']);
$_POST = array('sfx' => '', 'arc' => 'tar', 'exf' => null);

require('ParmOpts.php'); // load the class

$obj = new ParmOpts(); // instantiate with default priority

$opt = $obj->Opts($def);  // assign settings, update with the request values

$prm = $obj->Get();  // request parameters 

/* display result */
header('Content-Type: text/html; charset=utf-8');
echo 'ParmOpts usage sample<br><br>';
echo 'Options<br>';
echo '<pre>';
print_r($opt);  // updated options
echo '</pre>';
echo 'Parameters<br>';
echo '<pre>';
print_r($prm); // accepted parameters
echo '</pre>';
