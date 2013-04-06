<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author long
 */
class Event{
    
    private $id;
    private $title;
    private $detail;
    private $person;
    private $category;
    private $start;
    private $end;
    private $status;
    
    public function __construct($event) {
        if(is_array($event) ){
            $this->id = $event['event_id'];
            $this->title = $event['event_title'];
            $this->detail = $event['event_detail'];
            $this->person = $event['event_person'];
            $this->category = $event['event_cateID'];
            $this->start = $event['event_start'];
            $this->end = $event['event_end'];
            $this->status = $event['event_status'];    
        }
        else{
            throw new Exception("No Event data was supplied.");
        }
    
    }
    
    public function getID(){
        return $this->id;
    }
    public function setID($eventID) {
        $this->id = $eventID;
    }
    public function getTitle() {
        return $this->title;
    }
    public function setTitle($eventTitle)  {
        $this->title = $eventTitle;
    }
    public function getStart(){
        return $this->start;
    }
    public function getEvent()
    {
        return $this->end;
    }
    public function getDec()
    {
        return $this->detail;
    }
    public function getEnd()
    {
        return $this->end;
    }
}
?>
