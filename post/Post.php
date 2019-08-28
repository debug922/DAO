<?php
/**
 * Created by PhpStorm.
 * User: gio
 * Date: 27/06/2018
 * Time: 02:53
 */
require_once(__DIR__ . '/../../Debug.php');
require_once (__DIR__ .'/../DAO.php');
class Post extends Entity
{
    const table="post";
    private $title;
    private $dataCreation;
    private $dataI;
    private $dataF;
    private $tipo;
    private $available;
    private $id;
    private $description;
    private $photo;
    private $location;


    public function setAll($location, $dataI,$dataF,$desc,$Available,$title,$tipo)
    {
        $this->location = $location;
        $this->dataI = $dataI;
        $this->dataF = $dataF;
        $this->description = $desc;
        $this->available = $Available;
        $this->title = $title;
        $this->tipo = $tipo;
    }
    public function getDataCreation(){
        return $this->dataCreation;
    }
    public function getLocation(){
        return $this->location;
    }

    public function setLocation($location){
        $this->location=$location;
    }
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id=$id;
    }

    public function getDataI(){
        return $this->dataI;
    }

    public function setDataI($dataI){
        $this->dataI=$dataI;
    }
    public function getDataF(){
        return $this->dataF;
    }

    public function setDataF($dataF){
        $this->dataF=$dataF;
    }
    public function getDecription(){
        return $this->description;
    }
    public function setDecription($desc){
        $this->description=$desc;
    }
    public function getPhoto(){
        return $this->photo;
    }
    public function setPhoto($ph){
        $this->photo=$ph;
    }
    public function  getAvailable(){
        return $this->available;
    }
    public function  setAvailable($Available){
        $this->available=$Available;
    }
    public function  getTitle(){
        return $this->title;
    }
    public function  setTitle($title){
        $this->title=$title;
    }
    public function  getTipo(){
        return $this->tipo;
    }
    public function  setTipo($tipo){
        $this->tipo=$tipo;
    }
}
class PostDao extends Dao{

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
        $query= $this->createQueryUpdate($input,"post",array("id"));
        // echo $query;
        try{
            $stmt = $this->db->prepare($query);
            $bollR=$stmt->execute($array);
            $stmt->closeCursor();
            $stmt = null;

        }catch (PDOException $exception){
            echo $exception->getMessage();
        }
        return $bollR;
    }
    public function getIdAll($id)
    {
        if ($this->isCLose())
            exit();
        $post=null;
        try {
            $stmt = $this->db->prepare("SELECT * FROM CreatePost natural join post WHERE dataI>=current_date &&  available>0
                                        order by dataCreation;");
            $stmt->execute();
            $post = $stmt->fetchAll(PDO::FETCH_CLASS, 'Post');
            $stmt->closeCursor();
            $stmt=null;

        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
            //echo "error!".$exception->getMessage();
            exit();
        }
        return $post;
    }
    public function getAnything($id)
    {
        if ($this->isCLose())
            exit();
        $post=null;
        try {
            $stmt = $this->db->prepare("SELECT * FROM CreatePost natural join post WHERE dataI>=current_date && description like :id or title like :id 
                                          or location like :id;");
            $id1='%'.$id.'%';
            $stmt->bindValue(':id', $id1, PDO::PARAM_STR);
            $stmt->execute();
            $post = $stmt->fetchAll(PDO::FETCH_CLASS, 'Post');
            $stmt->closeCursor();
            $stmt=null;
        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
            //echo "error!".$exception->getMessage();
            exit();
        }
        return $post;
    }
    public  function getLikeCategorie($input){
        if ($this->isCLose())
            exit();
        $post=null;
        $in="";
        $categorie=$this->strangeArray($input,$in);
        //print_r($categorie);
        //echo $in;
        try {


            $stmt = $this->db->prepare("SELECT * FROM CreatePost natural join post WHERE dataI>=current_date && tipo in($in)
                                        order by dataCreation;");

            $stmt->execute($categorie);
            $post = $stmt->fetchAll(PDO::FETCH_CLASS, 'Post');
            $stmt->closeCursor();
            $stmt=null;

        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
            //echo "error!".$exception->getMessage();
            exit();
        }
        return $post;
    }
    private  function strangeArray($object,&$string){
        $reflectionClass = new ReflectionClass(get_class($object));
        $array = array();
        $string1="";
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if($property->getValue($object)){
                $array[$property->getName()] = $property->getName();
                $string1.=":".$property->getName().", ";
            }
            $property->setAccessible(false);
        }
        $string=substr($string1,0,-2);
        return $array;
    }


}
class CreatePost extends Entity
{
    const table="CreatePost";
    protected $id;
    protected $username;

/**
 * @param mixed $id
 */public function setId($id)
{
    $this->id = $id;
}
/**
 * @return mixed
 */public function getId()
{
    return $this->id;
}
/**
 * @return mixed
 */public function getUsername()
{
    return $this->username;
}
/**
 * @param mixed $username
 */public function setUsername($username)
{
    $this->username = $username;
}

}

class CreatePostDao extends DAO{

    //used in myPost
    public function getIdAll($id)
    {
        if ($this->isCLose())
            exit();
        $post=null;
        try {
            $stmt = $this->db->prepare("SELECT id,title,dataCreation,dataI,dataF,tipo,available FROM CreatePost natural join post WHERE username=:id;");
            //$stmt = DAO::$db->prepare("SELECT * FROM users WHERE username=:id;");
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $post = $stmt->fetchAll(PDO::FETCH_CLASS, 'Post');
            $stmt->closeCursor();
            $stmt=null;
        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
            //echo "error!".$exception->getMessage();
            exit();
        }
        return $post;
    }
    public function update($input)
    {
        // TODO: Implement update() method.
    }


}
class LovePost extends CreatePost{
 const table = "lovePost";
}
class LovePostDao extends DAO{
    public function update($input)
    {
        // TODO: Implement update() method.
    }
    public function getIdAll($id)
    {
        if ($this->isCLose())
            exit();
        $post=null;
        try {
            $stmt = $this->db->prepare("SELECT id,title,dataCreation,dataI,dataF,tipo,available FROM lovePost natural join post WHERE username=:id;");
            //$stmt = DAO::$db->prepare("SELECT * FROM users WHERE username=:id;");
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $post = $stmt->fetchAll(PDO::FETCH_CLASS, 'Post');
            $stmt->closeCursor();
            $stmt=null;
        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
            //echo "error!".$exception->getMessage();
            exit();
        }
        return $post;
    }

}

