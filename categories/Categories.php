<?php
/**
 * Created by PhpStorm.
 * User: gio
 * Date: 26/06/2018
 * Time: 18:37
 */
require_once(__DIR__ . '/../../Debug.php');
require_once (__DIR__ .'/../DAO.php');
class Categories extends Entity
{
    const table="categories";
    private $username;
    /*
    private $sport;
    private $event;
    private $trip;
    private $activity;
    private $study;
    */
    public $sport;
    public $event;
    public $trip;
    public $activity;
    public $study;


    public function setFalse(){
       /*
        $this->sport=false;
        $this->event=false;
        $this->trip=false;
        $this->activity=false;
        $this->study=false;
       */
        $this->sport=0;
        $this->event=0;
        $this->trip=0;
        $this->activity=0;
        $this->study=0;
     }
    public function getUsername(){
        return $this->username;
    }
    public function setUsername($user){
        $this->username=$user;
    }
    public function anyTrue(){
        return $this->sport || $this->event ||$this->trip ||$this->activity || $this->study;
    }
    /*
    public function getSport(){
        return $this->sport;
    }
    public function getEvent(){
        return $this->event;
    }
    public function getTrip(){
        return $this->trip;
    }
    public function getActivity(){
        return $this->activity;
    }

    public function getStudy(){
        return $this->study;
    }
    */
}
class CategoriesDao extends DAO{
     public function update($input)
     {
         $bollR=false;
         if(!$this->checkType($input)){
             Debug::debug_to_console("notSame");
             exit();
         }
         if ($this->isCLose())
             exit();
         $array=$this->createArrayU($input);
         //print_r($array);
         $query= $this->createQueryUpdate($input,"categories",array("username"));
         //echo $query;
         try{
             // $query="UPDATE users SET name = ? WHERE id = ?";
             $stmt = $this->db->prepare($query);
             $bollR=$stmt->execute($array);
             $stmt->closeCursor();
             $stmt = null;

         }catch (PDOException $exception){
             echo $exception->getMessage();
         }
         return $bollR;
     }
     public function delete($id)
     {
         // TODO: Implement delete() method.
     }
     public function getIdAll($id)
     {
         if ($this->isCLose())
             exit();
         $categories=null;
         try {
             $stmt = $this->db->prepare("SELECT * FROM categories WHERE username=:id;");
             //$stmt = DAO::$db->prepare("SELECT * FROM users WHERE username=:id;");
             $stmt->bindValue(':id', $id, PDO::PARAM_STR);
             $stmt->execute();
             $categories = $stmt->fetchObject('Categories');
             $stmt->closeCursor();
         }catch (PDOException $exception){
             Debug::debug_to_console($exception->getMessage());
             //echo "error!".$exception->getMessage();
             exit();
         }
         return $categories;

     }
    private function createArrayU($object) {
        $reflectionClass = new ReflectionClass(get_class($object));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if($property->getValue($object)!==null)
                $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }
        return $array;
    }

}

