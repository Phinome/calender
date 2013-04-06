<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if( isset($_GET['event_id']) )
{
    $id = preg_replace('/[^0-9]/', '', $_GET['event_id']);
    
    if(empty($id))
    {
        header("Location: ./");
        exit;
    }
    
}

else
{
    header("Location: ./");
    exit;
}

    include_once '../sys/core/init.inc.php';

    $page_title = "View Event";
    $css_files = array("default.css");
    include_once 'header.inc.php';

    $cal = new Calender();
    $htmls = new Html();
?>
<div id="content">
    <?php 
        echo $htmls->displayEvent($id, $cal);
    ?>
    <a href="./">&laquo;Back to the calender</a>
</div>
<?php
        include_once 'footer.inc.php';
?>