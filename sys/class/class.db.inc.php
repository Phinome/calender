<?php
/**
 * @description:数据库操作
 * 
 *  PHP	version 5
 *
 *  @author: Phinome
 *  @Time: 2013/2/23
 *  @Version: 1.0
 **/
class DB {
	
	protected $db;
	
	/*
	  * @parm object $dbo
	  */
	protected function __construst($db = NULL)
	{
		if( is_object($db) )
		{
			$this->db = $db;
		}
		else{
			$dsn = "mysql:host=".DB_HOST . ";dbname=" .DB_NAME ;
			try{
				$this->db = new PDO($dsn , DB_USER , DB_PASS);
			}
			catch( Exception $e )
			{
				die( $e->getMessage() );
			}
		}
	}

}
?>