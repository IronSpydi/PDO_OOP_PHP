<?php

class database{
    
    
    private $conf = "mysql:host=localhost;dbname=test";
    private $user = "root";
    private $pass = "";
    private $dbname = 'test';
    private $error = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
    private $con;
    private $result = array();
    public function __construct(){

                try {
                    $this->con = new PDO($this->conf,$this->user,$this->pass,$this->error);
                } catch (PDOException $e) {
                    array_push($this->result,$e->getMessage());
                    print_r($this->result[0]);
                }
    }

    public function insert($table,$paramv=array()){
        if ($this->istableExist($table)) {
            print_r($paramv);
            
            $table_columns = implode(',',array_keys($paramv));
            
            $table_bind = implode(',:',array_keys($paramv));

            $sql = "INSERT INTO $table ($table_columns) VALUES (:$table_bind)";
            $res = $this->con->prepare($sql);
      
            if($res->execute($paramv)){
                array_push($this->result,"data inserted successfully");
                print_r($this->result[0]);
                return true;
            }else {
                array_push($this->result,"insertion failed");
                print_r($this->result[0]);
                return false;
            }
        }
    }


    public function update($table,$paramv=array(),$where=NULL){
        if ($this->istableExist($table)) {
            
            //print_r($paramv);
            $setarg = array();
            foreach ($paramv as $key => $value) {
               $setarg [] = "$key = :$key";
            }
            $sql = "UPDATE $table SET ".implode(', ',$setarg);
            if($where!=NULL){
                $sql .=" WHERE id=:id";
                $paramv['id']=$where;
            }
            echo $sql;
            $res = $this->con->prepare($sql);
            if($res->execute($paramv)){
                array_push($this->result,"data updated successfully");
                print_r($this->result[0]);
                return true;
            }else {
                array_push($this->result,"update failed");
                print_r($this->result[0]);
                return false;
            }
        }else{
            return false;
        }
    }

    public function delete($table,$where=NULL){
        if($this->istableExist($table)){
            $wherearr = array();
            $sql= "DELETE FROM $table";
            if($where!=NULL){
                $sql.=" WHERE id=:id";
                $wherearr['id'] = $where; 
            }
            echo $sql;
            $res=$this->con->prepare($sql);
            if($res->execute($wherearr)){
                array_push($this->result,"data deleted successfully");
                print_r($this->result[0]);
                return true;
            }else {
                array_push($this->result,"deletion failed");
                print_r($this->result[0]);
                return false;
            }
        }
    }


    private function istableExist($table){
        $sql = $this->con->prepare("SHOW TABLES FROM $this->dbname LIKE ?");

        $sql->execute([$table]);
        if($sql->rowCount()==1){
            return true;
        }   
        else{
            array_push($this->result,$table." table is not exist in '$this->dbname' database");
            print_r($this->result[0]);

            return false;
        }
            
    }  
    public function __destruct(){
        $this->con = NULL;
    }
}


?>