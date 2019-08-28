<?php
/**
 * Created by PhpStorm.
 * User: gio
 * Date: 25/06/2018
 * Time: 16:05
 */
abstract class  DAO{
   // protected static $db;
    protected $db;
    public function __construct() {
    }
    public function  connect($db=null)
    {
        try {
            if($this->isCLose()) {
                //require_once(__DIR__ . '/mysql_credentials.php');
                require(__DIR__ . '/mysql_credentials.php');
                $this->db = new PDO("mysql:dbname=" . $mysql_db . ";host=" . $mysql_server, $mysql_user, $mysql_pass);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
                //DAO::$db = new PDO("mysql:dbname=" . $mysql_db . ";host=" . $mysql_server, $mysql_user, $mysql_pass);
                //DAO::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }else
                $this->db=$db;
        }catch (Exception $e){
            Debug::debug_to_console($e->getMessage());
           throw new PDOException();
        }
    }
    public function close(){
        $this->db=null;
    }
    public function setDb(PDO $db) {
        $this->db = $db;
    }
    public function getDb() {
        return $this->db;
    }
    protected function  checkType($entity){
         return is_a($entity,"Entity");
    }
    protected function isCLose(){
        //return DAO::$db==null;
        return $this->db==null;
    }
    public static function createArray($object) {
        $reflectionClass = new ReflectionClass(get_class($object));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if($property->getValue($object)!=null)
                $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }
        return $array;
    }
    protected function createQuery($object,$table){
        $reflectionClass = new ReflectionClass(get_class($object));
        $prexQuery="INSERT INTO ".$table."(";
        $sufQuery="VALUES (";
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if($property->getValue($object)!=null){
             $prexQuery.=$property->getName().", ";
             $sufQuery.=":".$property->getName().", ";
            }
            $property->setAccessible(false);
        }
        $result= substr($prexQuery,0,-2).") ".substr($sufQuery,0,-2).");";
        //Debug::debug_to_console($result);
        return $result;
    }
    protected function createQueryDelete($object,$table){
        $reflectionClass = new ReflectionClass(get_class($object));
        $prexQuery="Delete from ".$table." where ";
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if($property->getValue($object)!=null){
                $prexQuery.=$property->getName()."= :".$property->getName()." and ";
            }
            $property->setAccessible(false);
        }
        $result= substr($prexQuery,0,-4).";";
        //echo $result;
        //Debug::debug_to_console($result);
        return $result;
    }


    protected function createQueryUpdate($object,$table,$keys){
        $reflectionClass = new ReflectionClass(get_class($object));
        $prexQuery="UPDATE ".$table." SET ";
        $sufQuery="WHERE ";
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if($property->getValue($object)!==null){
                    foreach ($keys as $key)
                        if ($key!=$property->getName())
                            $prexQuery.=$property->getName()."= :".$property->getName().", ";
                        else
                            $sufQuery .= $property->getName() . "= :" . $property->getName() . ", ";


            }
            $property->setAccessible(false);
        }
        $result= substr($prexQuery,0,-2)." ".substr($sufQuery,0,-2).";";
        return $result;
    }
    public function insert($input,&$id=-1)
    {
        //Debug::debug_to_console("isInsert");
        $bollR=false;
        if(!$this->checkType($input)){
            Debug::debug_to_console("notSame");
            echo "notSame<br>";
            exit();
        }
        if ($this->isCLose())
            exit();
        $array=$this->createArray($input);
        //print_r($array);
        $query=$this->createQuery($input,$input::table);
        try {
            $stmt = $this->db->prepare($query);
            //$stmt =  DAO::$db->prepare($query);
            $bollR=$stmt->execute($array);
            $id = $this->db->lastInsertId();
            //echo $id;
            $stmt->closeCursor();
            $stmt=null;
        }catch (PDOException $exception){
            //Debug::debug_to_console($exception->getMessage());
            if($exception->errorInfo[1] === 1062)
                return 1062;
        }
        return $bollR;
    }
    public function deleteW($input)
    {
        $bollR=false;
        if ($this->isCLose())
            exit();
        try{
            $array=self::createArray($input);
            $query=$this->createQueryDelete($input,$input::table);
            $stmt = $this->db->prepare($query);
            $bollR=$stmt->execute($array);
            $stmt->closeCursor();
            $stmt=null;
        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
        }
        return $bollR ;
    }
    abstract public function getIdAll($id);
    //abstract public function insert($input);
    abstract public function  update($input);
    //abstract public function delete($id);

}

abstract class Entity {
    public function createArrayN($object) {
        $reflectionClass = new ReflectionClass(get_class($object));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }
        return $array;
    }

}
