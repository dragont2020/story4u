<?php
include 'config/ZenDB.php';
class DB_driver
{
    private $__conn;
    function __construct(){
        if (!$this->__conn){
            try {
                $this->__conn = new PDO("mysql:host=".__ZEN_DB_HOST.";dbname=".__ZEN_DB_NAME, __ZEN_DB_USER, __ZEN_DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }
            catch (PDOException $e) {
                return false;
            }
        }
    }
    function __destruct(){
        if ($this->__conn){
            $this->__conn = null;
        }
    }
    function insert($table, $data){
        $field_list = '';
        $value_list = '';
        foreach ($data as $key => $value){
            $field_list .= ",$key";
            $value_list .= ",'".$value."'";
        }
        $sql = 'INSERT INTO '.$table. '('.trim($field_list, ',').') VALUES ('.trim($value_list, ',').')';
        try {
            $this->__conn->exec($sql);
            return $this->__conn->lastInsertId();
        }
        catch (PDOException $e) {
            return false;
        }
    }
    function update($table, $data, $where){
        $sql = '';
        foreach ($data as $key => $value){
            $sql .= "$key = '".$value."',";
        }
        $sql = 'UPDATE '.$table. ' SET '.trim($sql, ',').' WHERE '.$where;
        try {
            $stmt = $this->__conn->prepare($sql);
            $stmt->execute();
            return true;
        }
        catch(PDOException $e) {
            return false;
        }
    }
    function remove($table, $where){
        $sql = "DELETE FROM $table WHERE $where";
        try {
            $this->__conn->exec($sql);
            return true;
        }
        catch(PDOException $e) {
            return false;
        }
    }
    function select($table, $where = array(), $limit = '', $order = array()){
    	$w = '';    	$l = '';    	$o = '';
    	if(!empty($where)){
    		$w = 'WHERE ';
    		if(is_array($where)){
    			foreach($where as $k => $v){
    				$w .= "$k = ".(is_string($v)?"'$v'":$v).($v != end($where)?' AND ':'');
    			}
    		}
    	}
    	if($limit != ''){
    		$l = "LIMIT $limit";
    	}
    	if(!empty($order)){
    		$o = "ORDER BY ";
    		if(is_array($order)){
    			foreach($order as $k => $v){
    				$o .= "$k $v".($v != end($order)?', ':'');
    			}
    		}
    	}
    	$sql = "SELECT * FROM $table $w $o $l";
        try {
            $stmt = $this->__conn->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;
        }
        catch(PDOException $e) {
            return false;
        }
    }
    function query($sql){
        try {
            $stmt = $this->__conn->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;
        }
        catch(PDOException $e) {
            return false;
        }
    }
}
?>