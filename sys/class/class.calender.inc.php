<?php
/**
 * @description: 日程表基类
 * 
 *  PHP	version 5
 *
 *  @author: Phinome
 *  @Time: 2013/2/23
 *  @Version: 1.0
 **/

class Calender extends DB {
	
	/**
	  *	 日历根据此日期构建
	  *
	  *	 格式为：YYYY-MM-DD HH:MM:SS
	  *  @var : string 日历显示日期
	  **/
	private $_useDate;

	/**
	  *	 日历显示月份
	  *
	  *  @var : int 月份
	  **/
	private $_month;

	/**
	  *	 日历显示年份
	  *
	  *  @var : int 年份
	  **/
	private $_year;

        /**
	  *	 这个月有多少天
	  *
	  *  @var : int 天数
	  **/
        private $_daysInMonth;

	/**
	  *	 这个月起始日周几的索引
	  *
	  *  @var : int 这个月从周几开始
	  **/
	private $_startDay;

	/**
	  * 创建一个数据库对象存储有关的数据
	  *
	  * @param object $dbo
	  * @param object string $useDate
	  * @return void
	  */
	public function __construct( $dbo = NULL , $useDate = NULL )
	{
		  parent ::__construst($dbo);

		  if( isset( $useDate) ) {
			  $this->_useDate = $useDate;
		  }
		  else {
			  $this->_useDate = date( 'Y-m-d H:i:s' );
		  }

		  $ts = strtotime($this->_useDate);
		  $this->_month = date( 'm' , $ts );
		  $this->_year = date( 'Y', $ts );

		  $this->_daysInMonth = cal_days_in_month( CAL_GREGORIAN , $this->_month , $this->_year );

		  $tse = mktime( 0, 0 , 0 , $this->_month , 1 , $this->_year );

		  $this->_startDay = date( 'w' , $tse); 
	}

	/**
	  * @description : 将活动信息载入一个关联数组
	  *
	  * @ param int $id 用来过滤结果的可选活动 ID
	  * @ return array   来自数据库的活动信息数据
	  *
	  **/
	private function __loadEventDate( $id = NULL ) {
		  $sql = "SELECT  *  FROM `" . DB_PREFIX . "event` ";

		  if( !empty( $id ) )
		  {
			  $sql .= " WHERE `event_id` =:id LIMIT 1";
		  }
		  else {
			  $start_ts = mktime( 0, 0, 0, $this->_month , 1 ,$this->_year );
			  $end_ts =mktime( 23, 59, 59, $this->_month , $this->_daysInMonth ,$this->_year );
			  $start_date = date( 'Y-m-d H:i:s' , $start_ts);
			  $end_date = date( 'Y-m-d H:i:s' , $end_ts );

			  $sql .= "WHERE  `event_start` BETWEEN '$start_date' AND '$end_date' ORDER BY 'event_start' ";
		  }

		  try{
				$stmt = $this->db->prepare($sql);
                                
				if( !empty( $id ) )
				{
					$stmt->bindParam( ":id" , $id , PDO::PARAM_INT );
                                }
                             
                                        $stmt->execute();
                                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);        
                                	$stmt->closeCursor();
                                        
					return $results;
                  }
				catch ( Exception $e )
				  {
						die( $e-getMessage() );
				  }
		  }
          
        public function __createEventObj() {
            $arr = $this->__loadEventDate();
            
            $events = array();
            
            foreach ($arr as $event){
                $day = date('j' , strtotime($event['event_start']));
                try{
                    $events[$day][] = new Event($event);
            }
                catch ( Exception $e ){
                    die( $e->getMessage() );
                }
            }
            return $events;
        }
        
        public function __loadEventById($id)
        {
            if( empty($id) )
            {
                return NULL;
            }
            $event = $this->__loadEventDate($id);

            if( isset($event[0]) )
            {
                return new Event($event[0]);
            }
            else
            {
                return NULL;
            }
        }
        
        public function eventToHtml($id)
        {
        if( empty($id) )
        {
            return NULL;
        }
        $id = preg_replace('/[^0-9]/', '' , $id);
        
        $event = $this->__loadEventById($id);
        $events = array();
        $events['ts'] = strtotime($event->getStart());
        $events['date'] = date('F d, Y',$events['ts']);
        $events['start'] = date('g:ia',$events['ts']);
        $events['end'] = date('g:ia',  strtotime($event->getEnd()));
        $events['title'] = $event->getTitle();
        $events['dec'] = $event->getDec();
        return $events;
        }
        
        public function processForm()
        {
            if( $_POST['action'] != 'event_edit')
            {
                return "The method processForm was accessed incorrectly";
            }
            
            $title = htmlspecialchars($_POST['event_title'], ENT_QUOTES);
            $desc = htmlspecialchars($_POST['event_dec'], ENT_QUOTES);
            $start = htmlspecialchars($_POST['event_start'], ENT_QUOTES);
            $end = htmlspecialchars($_POST['event_end'],ENT_QUOTES);
            $person = htmlspecialchars($_POST['event_person'],ENT_QUOTES);
            $status = htmlspecialchars($_POST['event_status'],ENT_QUOTES);
            $cate = htmlspecialchars($_POST['event_cate'],ENT_QUOTES);
            if( empty($_POST['event_id']) )
            {
                $sql = "INSERT INTO `".DB_PREFIX."event`(`event_title`,`event_detail`,`event_start`,`event_end`,`event_person`,`event_status`,`event_cate`)
                        VALUES 
                        (:title,:detail,:start,:end,:person,:status,:cate)";
            }
            else
            {
                $id = (int)$_POST['event_id'];
                $sql = "UPDATE `".DB_PREFIX."events` 
                    SET
                        `event_title`=:title,
                        `event_detail`=:detail,
                        `event_start`=:start,
                        `event_end`=:end,
                        `event_person`=:person,
                        `event_status`=:status,
                        `event_cate`=:cate
                    WHERE `event_id` = $id";
            }
            try
            {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":title",$title,PDO::PARAM_STR);
                $stmt->bindParam(":detail",$desc,PDO::PARAM_STR);
                $stmt->bindParam(":start",$start,PDO::PARAM_STR);
                $stmt->bindParam(":end",$end,PDO::PARAM_STR);
                $stmt->bindParam(":person",$person,PDO::PARAM_STR);
                $stmt->bindParam(":status",$status,PDO::PARAM_STR);
                $stmt->bindParam(":cate",$cate,PDO::PARAM_STR);

                $stmt->execute();
                $stmt->closeCursor();
                return TRUE;
            }
            catch ( Exception $e )
            {
                return $e->getMessage();
            }
        }
        public function getDate() {
            return $this->_useDate;
        }
        public function getMonth() {
            return $this->_month;
        }
        public function getYear() {
            return $this->_year;
        }
        public function getDays() {
            return $this->_daysInMonth;
        }
        public function getStartDay() {
            return $this->_startDay;
        }
}
?>