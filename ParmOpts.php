<?php

/*
 * PHP parameters and Options handler
 * 
 * Accept request/CLI parameters
 * Save options according to defaults
 * Supply saved parameters/options as array/object
 *
 * @package ParmOpts
 * @author Vallo Reima
 * @copyright (C)2015, 2017
 */

class ParmOpts {

    protected $rqt = array(); /* input parameters */
    protected $opt = array(); /* option values */
    protected $jsn = null;    /* json request data */
    protected $mds = array(     /* misc modes */
        'http2' => null,        /* HTTP/2 or not (HTTP/1) */
        'https' => null,        /* secured or not */
        'xhr' => null,          /* AJAX request or not*/
        'rqm' => null           /* request method */
    );

    /**
     * accept the request or CLI parameters
     * @param string $pty request data priority:
     *                                    J - json
     *                                    P - post
     *                                    G - get
     */
    public function __construct($pty = 'JPG') {
        global $argc, $argv;
        if (empty($argc)) { // request parameters
            $jsn = @json_decode(file_get_contents('php://input'), true);  // check for json body
            if (is_array($jsn)) {
                $this->jsn = $jsn;  // json request
            } else {
                $this->jsn = isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'json') !== false ? array() : false;  // empty json body?
                $jsn = array(); // no json data
            }
            $this->Merge(array('J' => $jsn, 'P' => $_POST, 'G' => $_GET), strtoupper($pty));  // save
            $this->mds['http2'] = (isset($_SERVER['SERVER_PROTOCOL']) && strpos($_SERVER['SERVER_PROTOCOL'], 'HTTP/2') === 0);
            $this->mds['https'] = ((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443));
            $this->mds['xhr'] = ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
                    (isset($_SERVER['X_REQUESTED_WITH']) && strtolower($_SERVER['X_REQUESTED_WITH']) == 'xmlhttprequest'));
            $this->mds['rqm'] = $_SERVER['REQUEST_METHOD']; //client request method
        } else {  // command arguments
            for ($i = 1; $i < $argc; $i++) {  // loop the arguments
                $arg = mb_split('=', $argv[$i]);  // split to key/value
                if (!empty($arg[0])) {
                    $this->rqt[$arg[0]] = !empty($arg[1]) ? $arg[1] : '';  // save
                }
            }
            $this->mds['rqm'] = 'CLI'; //command-line
        }
    }

    /**
     * merge the request data according to priority
     * @param array $rqt
     * @param string $pty
     */
    private function Merge($rqt, $pty) {
        $seq = array_reverse(str_split($pty));  //processing sequence
        foreach ($seq as $r) {
            if (isset($rqt[$r])) {  //not skipped
                $this->rqt = array_merge($this->rqt, $rqt[$r]); // append/replace
            }
        }
    }

    /**
     * save option values, set type
     * @param array $opt defaults
     * @param array $rqt values -- null - take accepted ones
     * @return array Description
     */
    public function Opts($opt, $rqt = null) {
        if (is_null($rqt)) {
            $rqt = & $this->rqt;
        }
        foreach ($opt as $key => $val) {  // loop defaults
            if (array_key_exists($key, $rqt) && (!empty($rqt[$key]) || $this->Check($val))) {  // user's value or allowed empty
                $val = $rqt[$key];
                if (is_array($opt[$key])) {
                    $val = (array) $val;  // array required
                } else if ($this->Check($opt[$key], true)) {
                    settype($val, gettype($opt[$key])); // adjust type
                }
            }
            $this->opt[$key] = $val;  // save value
        }
        return $this->opt;
    }

    /**
     * check allowance
     * @param mixed $val -- option value
     * @param bool $flg -- check allowance of:
     *                        false - replace with empty value
     *                        true - adjust data type
     * @return bool
     */
    private function Check($val, $flg = false) {
        if ($flg) {
            $f = is_numeric($val) || is_string($val) || is_bool($val);  // set only these types
        } else {
            $f = empty($val) || is_bool($val) || is_array($val);  // replace only these empties
        }
        return $f;
    }

    /**
     * supply the values
     * @param string $prp property name (rqt/opt/jsn)
     * @param bool $flg -- true - dual mode
     * @return mixed
     */
    public function Get($prp = 'rqt', $flg = false) {
        if (!isset($this->$prp)) {
            $r = array();
        } else if ($flg && is_array($this->$prp)) {
            $r = new ArrayObject($this->$prp, ArrayObject::ARRAY_AS_PROPS); // object looking like array
        } else {
            $r = $this->$prp;
        }
        return $r;
    }

}
