<?php
	include_once ("../sys/core/init.inc.php");
       
        $cal = new Calender();
        
        $htmls = new Html();
        
        $page_title = "Phinome Calender";
        $css_files =  array('default.css','admin.css');

        include_once 'header.inc.php';
?>
<div id="content">
<?php        
        echo $htmls->bulidCalender($cal);
?>
</div>
<?php
        include_once 'footer.inc.php';
?>