<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
	include_once ("../sys/core/init.inc.php");
        
        $page_title = "Add/Edit Event";
        $css_files =  array('default.css','admin.css');
        
        $cal = new Calender();
        
        $htmls = new Html();  
        
        include_once 'header.inc.php';
?>
<div id="content">
<?php        
        echo $htmls->displayForm($cal);
?>
</div>
<?php
        include_once 'footer.inc.php';
?>
