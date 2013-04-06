<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

require_once '../sys/config/db-cred.inc.php';

foreach ( $C as $name => $val )
{
    define($name , $val);
}

$actions = array(
           'event_edit'=>array(
                 'object'=>'Calender',
                 'method'=>'processForm',
                 'header'=>'Location: ./')
        );

if( $_POST['token'] == $_SESSION['token'] )
{
    $use_array = $actions[$_POST['action']];
    $obj = new $use_array['object']();
    
    if( TRUE == $msg=$obj->$use_array['method']() )
    {
        header($use_array['header']);
        exit;
    }
    else
    {
        die($msg);
    }
}
 else {
     
     header("Location: ./");
     exit;
}

function __autoload($class_name)
{
    $filename = '../sys/class/class.' . strtolower($class_name) . '.inc.php';
    if( file_exists($filename) )
    {
        include_once $filename;
    }
}
?>
