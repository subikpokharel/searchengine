<?php

class Database {

	private $conn;
	private function connect() {

		$host = "undcsmysql.mysql.database.azure.com:3306";
        	$userid = "subik.pokharel@undcsmysql";
        	$password = "Subik7112";
        	$dbname = "subik_pokharel";

        	$this->conn = mysql_connect( $host, $userid, $password );

		//$this->conn = new mysql('localhost', 'root', 'basanta', 'db_class_430_newsportal');
		if ($this->conn->connect_errno != 0) {
		//if(!$this->conn){
			die('database connection error');
		}
		else
			mysql_select_db($dbname, $this->conn);

	}

	public function insert($table, $fields, $values) {
		$this->connect();
		$sql = "insert into $table (";

		foreach ($fields as $field) {
			$sql = $sql."$field,";

		}

		$sql = substr($sql, 0, strlen($sql)-1);

		$sql = $sql.') values (';

		foreach ($values as $value) {
			$sql = $sql."'$value',";

		}

		$sql = substr($sql, 0, strlen($sql)-1);
		$sql = $sql.')';
		//echo $sql;
		$result = mysql_query($sql);

		if( $result != 1){
                                return false ;
                }else
			return $result;
		//print_r($result);
		//$this->conn->mysql_query($sql);
		/*if ($this->conn->insert_id != null) {
			return $this->conn->insert_id;
		} else {
			return false;
		}*/

	}

	public function select($table, $fields, $condition = null) {
		$this->connect();

		//echo $this->conn;
		//select fields list ..,,,, from table name
		$sql = "select ";

		foreach ($fields as $field) {
			$sql = $sql."$field,";
		}
		$sql = substr($sql, 0, strlen($sql)-1);

		$sql  = $sql." from $table";

		if ($condition) {
			$sql = $sql." where ";
			foreach ($condition as $key => $value) {
				$sql = $sql."$key='$value'";
			}
		}

		$res	= mysql_query($sql, $this->conn);
		//echo $sql, '<br>';
		$data = array();
		//if ($res->num_rows > 0) {
			//echo "Data";
			while ($a = mysql_fetch_array($res)){ //$res->fetch_object()) {
				array_push($data, $a);
			}

	//	}
		return $data;//"testing";		
	}
}

?>
