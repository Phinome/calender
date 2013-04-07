<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Html {
    public function bulidCalender(Calender $pro){
        $cal_month = date('F Y',  strtotime($pro->getDate()));
        $weekDays = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
        
        $html = "\n\t<h2>$cal_month</h2>";
        for( $d = 0 , $labels = NULL; $d < 7 ; ++$d)
        {
            $labels .="\n\t<li>". $weekDays[$d] ."</li>";
        }
        $html .= "\n\t<ul class='weekDays'>". $labels ."\n\t</ul>";
        
        $events = $pro->__createEventObj();
        
        $html .= "\n\t<ul>";
        for( $i =1 , $c=1 , $t = date('j'),$m = date('m'),$y=date('Y');$c <= $pro->getDays();++$i)
        {
            $class = $i <= $pro->getStartDay() ? "fill" : NULL;
            
            if($c == $t && $m == $pro->getMonth() && $y == $pro->getYear())
            {
                $class = "today";
            }    
            
            $ls = sprintf("\n\t\t<li class=\"%s\">",$class);
            $le = "\n\t\t</li>";
            
            if($pro->getStartDay()<$i && $pro->getDays() >= $c)
            {
                $event_info = NULL;
                if( isset($events[$c]))
                {
                    foreach ($events[$c] as $event) {
                        $link = "<a href=\"view.php?event_id=". $event->getID() ."\" >" . $event->getTitle() . "</a>";
                        $event_info .= "\n\t\t\t$link";
                    }
                }
                $date = sprintf("\n\t\t\t<strong>%02d</strong>",$c++);
            }
            else {
                $date = "&nbsp;";
            }
            $wrap = $i !=0 && $i%7==0 ? "\n\t</ul>\n\t<ul>":NULL;
            
            $html .= $ls . $date . $event_info .$le .$wrap;
        }
        
        while ( $i%7 !=1)
        {
            $html .= "\n\t\t<li class='fill'>&nbsp;</li>";
            ++$i;
        }
        $html .= "\n\t</ul>\n\n";
        
        $admin = $this->__adminGrneralOptions();
        
        return $html . $admin;
    }
    
    public function displayEvent($id , Calender $pro)
    {
        $rs = $pro->eventToHtml($id);
        $str = "<h2>{$rs['title']}</h2>"."\n\t<p class=\"dates\">{$rs['date']} , {$rs['start']}&mdash;{$rs['end']}</p>" . "\n\t<p>{$rs['dec']}</p>";
        return $str;
    }
    
    public function displayForm(Calender $pro)
    {
        if( isset($_POST['event_id']) )
        {
            $id = (int) $_POST['event_id'];
        }
        else 
        {
            $id = NULL;
        }
        $submit = "Create a New Event";
        $events =array();
        $event = new Event($events);
        if( !empty($id) )
        {
            $event = $pro->__loadEventById($id);
            if( !is_object($event) ) { return NULL; }
            $submit = "Edit This Event";
        }
        
       return <<<EOT
        
            <form action="process.inc.php" method="post">
                <fieldset>
                    <legend>{$submit}</legend>
                    <label for="event_title">Event Title</label>
                    <input type="text" name="event_title" id="event_title" value="{$event->getTitle()}" />
                    <label for="event_start">Start Time</label>
                    <input type="text" name="event_start" id="event_start" value="{$event->getStart()}" />
                    <label for="event_end">End Time</label>
                    <input type="text" name="event_end" id="event_end" value="{$event->getEnd()}" />
                    <label for="event_end">Person Name</label>
                    <input type="text" name="event_person" id="event_end" value="{$_SESSION['user']}" />
                    <label for="event_end">Event Status</label>
                    <input type="radio" name="event_status" class="event_status" value="Start" /><span>Start</span>
                    <input type="radio" name="event_status" class="event_status" value="Doing" /><span>Doing</span>
                    <input type="radio" name="event_status" class="event_status" value="Pause" /><span>Pause</span>
                    <input type="radio" name="event_status" class="event_status" value="End" /><span>End</span>
                    <label for="event_end">Event Categroy</label>
                    <input type="checkbox" name="event_cate" class="event_cate" value="" />One
                    <input type="checkbox" name="event_cate" class="event_cate" value="" />Two
                    <input type="checkbox" name="event_cate" class="event_cate" value="" />Three
                    <label for="event_dec">Event Description</label>
                    <textarea type="text" name="event_dec" id="event_dec" value="{$event->getDec()}"></textarea>
                    <input type="hidden" name="event_id" value="{$event->getID()}" />
                    <input type="hidden" name="token" value="{$_SESSION['token']}" />
                    <input type="hidden" name="action" value="event_edit" />
                    <input type="submit" name="event_submit" value="{$submit}" />
                    or <a href="./">Cancle</a>
                </fieldset>    
            </form>        
EOT;
    }
    
    public function __adminGrneralOptions()
    {
        return <<<ADMIN_OPTIONS
        <a href="admin.php" class="admin">+Add a New Event</a>
        
ADMIN_OPTIONS;
    }
}

?>
