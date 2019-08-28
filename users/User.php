<?php
/**
 * Created by PhpStorm.
 * User: gio
 * Date: 25/06/2018
 * Time: 17:22
 */
require_once(__DIR__ . '/../../Debug.php');
require_once (__DIR__ .'/../DAO.php');
class User extends Entity{
    const table="users";
    private $firstname;
    private $surname;
    private $email;
    private $username;
    private $password;
    private $city;
    private $description;
    private $photo;

    public function setAll($user,$pass,$email,$surname,$first){
        $this->setEmail($email);
        $this->setUser($user);
        $this->setPassword($pass);
        $this->setSurname($surname);
        $this->setFirstname($first);
    }
    public function getUser()
    {
        return $this->username;
    }
    public function getPassword(){
        return $this->password;
    }
    public  function getEmail(){
        return $this->email;
    }
    public  function getSurname(){
        return $this->surname;
    }
    public  function getFirstname(){
        return $this->firstname;
    }
    public  function getCity(){
        return $this->city;
    }
    public function getDecription(){
        return $this->description;
    }
    public function getPhoto(){
        return $this->photo;
    }
    public function setUser($user)
    {
        $this->username=($user);
    }
    public function setPassword($pass){
        $this->password=sha1(($pass));
    }
    public  function setEmail($email){
        $this->email=($email);
    }
    public function setSurname($surname){
        $this->surname=($surname);
    }
    public function setFirstname($first){
        $this->firstname=($first);
    }
    public  function setCity($city){
        $this->city=($city);
    }
    public function setDecription($desc){
        $this->description=($desc);
    }
    public function setPhoto($ph){
        $this->photo=$ph;
    }
    public function checkPassword($pass){
        return $this->password==sha1(($pass));
    }

}
class UserDao extends Dao {
    /*
    public function delete($id)
    {
        $bollR=false;
        if ($this->isCLose())
            exit();
        try{
            $stmt = $this->db->prepare("Delete FROM users WHERE username=:id;");
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $bollR=$stmt->execute();
            $stmt->closeCursor();
        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
        }
        return $bollR ;
    }
*/
    public function update($input)
    {
        $bollR=false;
        if(!$this->checkType($input)){
            Debug::debug_to_console("notSame");
            exit();
        }
        if ($this->isCLose())
            exit();
        $array=$this->createArray($input);
        $query= $this->createQueryUpdate($input,"users",array("username"));
        //echo $query;
        try{
           // $query="UPDATE users SET name = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $bollR=$stmt->execute($array);
            $stmt->closeCursor();
            $stmt = null;

        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
        }
        return $bollR;
    }
    public function getIdAll($id) {
        if ($this->isCLose())
            exit();
        $user=null;
        try {
                $stmt = $this->db->prepare("SELECT * FROM users WHERE username=:id;");
                //$stmt = DAO::$db->prepare("SELECT * FROM users WHERE username=:id;");
                $stmt->bindValue(':id', $id, PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetchObject('User');
                $stmt->closeCursor();
        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
            //echo "error!".$exception->getMessage();
            exit();
        }
        return $user;
    }
    public function exist($id,$atr) {
        if ($this->isCLose())
            exit();
        try {
            $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE ".$atr."=:id;");
            //$stmt = DAO::$db->prepare("SELECT * FROM users WHERE username=:id;");
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $num=$stmt->fetchColumn();
            $stmt->closeCursor();
            return $num;
        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
            //echo "error!".$exception->getMessage();
            return -1;
        }
    }
}