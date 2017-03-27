<?php


define('MY_HOST','localhost');
define('MY_USER','root');
define('MY_PASS','root');
define('MY_BASE','soundtrack');
define('ENV','dev');

function db() {
    try {
        $conn = new PDO('mysql:host='.MY_HOST.';dbname='.MY_BASE, MY_USER, MY_PASS);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch(PDOException $e) {
        if(ENV === 'dev') echo "Connection failed: " . $e->getMessage();
        return false;
    }
}
