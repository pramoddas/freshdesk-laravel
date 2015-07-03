<?php namespace Freshdesk\Freshdesk;

use Exception;
use Carbon\Carbon as Carbon;
use Illuminate\Session\Store as SessionStore;
use Freshdesk\Freshdesk\Traits\AccountTrait;


class Freshdesk {


    protected $response = '';       // Contains the cURL response for debug
    protected $session;             // Contains the cURL handler for a session
    protected $url;                 // URL of the session
    protected $options = array();   // Populates curl_setopt_array
    protected $headers = array();   // Populates extra HTTP headers
    private $error_code;             // Error code returned as an int
    private $error_string;           // Error message returned as a string
    private $info;                   // Returned after request (elapsed time, etc)

// CURL LIBRARY https://github.com/unikent/laravel-curl/blob/master/libraries/Curl.php
    // Start a session from a URL
    private function create($url)
    {
        // If no a protocol in URL, assume its a Laravel link
        if ( ! preg_match('!^\w+://! i', $url))
        {
            $url = url($url);
        }
        $this->url = $url;
        $this->session = curl_init($this->url);
        return $this;
    }

    // End a session and return the results
    private function execute()
    {
        // Set two default options, and merge any extra ones in
        if ( ! isset($this->options[CURLOPT_TIMEOUT]))
        {
            $this->options[CURLOPT_TIMEOUT] = 30;
        }
        if ( ! isset($this->options[CURLOPT_RETURNTRANSFER]))
        {
            $this->options[CURLOPT_RETURNTRANSFER] = TRUE;
        }
        if ( ! isset($this->options[CURLOPT_FAILONERROR]))
        {
            $this->options[CURLOPT_FAILONERROR] = TRUE;
        }
        // Only set follow location if not running securely
        if ( ! ini_get('safe_mode') && ! ini_get('open_basedir'))
        {
            // Ok, follow location is not set already so lets set it to true
            if ( ! isset($this->options[CURLOPT_FOLLOWLOCATION]))
            {
                $this->options[CURLOPT_FOLLOWLOCATION] = TRUE;
            }
        }
        if ( ! empty($this->headers))
        {
            $this->option(CURLOPT_HTTPHEADER, $this->headers);
        }
        $this->options();
        // Execute the request & and hide all output
        $this->response = curl_exec($this->session);
        $this->info = curl_getinfo($this->session);
        // Request failed
        if ($this->response === FALSE)
        {
            $errno = curl_errno($this->session);
            $error = curl_error($this->session);
            curl_close($this->session);
            $this->set_defaults();
            $this->error_code = $errno;
            $this->error_string = $error;
            return FALSE;
        }
        // Request successful
        else
        {
            curl_close($this->session);
            $this->last_response = $this->response;
            $this->set_defaults();
            return $this->last_response;
        }
    }

    private function post($params = array(), $options = array())
    {
        // If its an array (instead of a query string) then format it correctly
        if (is_array($params))
        {
            $params = http_build_query($params, NULL, '&');
        }
        // Add in the specific options provided
        $this->options($options);
        $this->http_method('post');
        $this->option(CURLOPT_POST, TRUE);
        $this->option(CURLOPT_POSTFIELDS, $params);
    }

    private function http_header($header, $password, $data)
    {
        $this->headers[] = $content ? $header . ': ' . $content : $header;
        return $this;
    }

//  $data = array(
         // "user" => array("email"=>"me@abc.com","name"=>"Me")
    // );
    public function createUser($email, $content = NULL)
    {
        $jsondata= json_encode($data);
    }
    // https://github.com/freshdesk/fresh-samples/blob/master/php_samples/create_user.php
    // $email="sample@freshdesk.com";//username or apiKey
    // $password="test";//pwd or X
    // $data = array(
    //   "user" => array("email"=>"me@abc.com","name"=>"Me")
    // );
    // //encoding to json format
    // $jsondata= json_encode($data);
    // echo "START....<br /> ";
    // $header[] = "Content-type: application/json";
    // $connection = curl_init();
    // curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($connection, CURLOPT_HTTPHEADER, $header);
    // curl_setopt($connection, CURLOPT_FOLLOWLOCATION, true);
    // curl_setopt($connection, CURLOPT_HEADER, false);
    // curl_setopt($connection, CURLOPT_USERPWD, "sample@freshdesk.com:test");
    // curl_setopt($connection, CURLOPT_POST, 1);
    // curl_setopt($connection, CURLOPT_POSTFIELDS, $jsondata);
    // curl_setopt($connection, CURLOPT_VERBOSE, 1);
    // //replace your domain url below.
    // curl_setopt($connection, CURLOPT_URL, "http://yourcompany.freshdesk.com/contacts.json");
    // $response = curl_exec($connection);
    // echo 'RESULT:'.$response;

}