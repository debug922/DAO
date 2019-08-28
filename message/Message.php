<?php
/**
 * Created by PhpStorm.
 * User: gio
 * Date: 14/07/2018
 * Time: 01:05
 */

class Message extends Entity
{
    const table="message";
    private $username;
    private $receiver;
    private $times;
    private $texts;

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @return mixed
     */
    public function getTexts()
    {
        return $this->texts;
    }

    /**
     * @return mixed
     */
    public function getTimes()
    {
        return $this->times;
    }

    /**
     * @param mixed $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @param mixed $texts
     */
    public function setTexts($texts)
    {
        $this->texts = $texts;
    }

    /**
     * @param mixed $times
     */
    public function setTimes($times)
    {
        $this->times = $times;
    }
}
class MessageDao extends DAO{
    public function getIdAll($id)
    {
        if(!$this->checkType($id)){
            Debug::debug_to_console("notSame");
            exit();
        }
        if ($this->isCLose())
            exit();
        $message=null;
        $array=self::createArray($id);
        try {
            $stmt = $this->db->prepare("SELECT * FROM message WHERE (username=:username and receiver=:receiver) 
                                          or (username=:receiver and receiver=:username)order by times;");
            $stmt->execute($array);
            $message = $stmt->fetchAll(PDO::FETCH_CLASS, 'Message');
            $stmt->closeCursor();
            $stmt=null;

        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
            //echo "error!".$exception->getMessage();
            exit();
        }
        return $message;
    }
    public function getIdReceiver($id)
    {
        if(!$this->checkType($id)){
            Debug::debug_to_console("notSame");
            exit();
        }
        if ($this->isCLose())
            exit();
        $message=null;
        $array=self::createArray($id);
       // print_r($array);
        try {
         //  $stmt = $this->db->prepare("SELECT username,times FROM message WHERE receiver=:receiver group by receiver ;");
        //    $stmt = $this->db->prepare("SELECT * FROM message WHERE times in
         //                                   (Select max(times) from message where receiver=:receiver )    ;");
            //select receiver from message group by times
           // $stmt = $this->db->prepare("SELECT username,times FROM message where  receiver in(SELECT receiver from message WHERE receiver=:receiver group by receiver ) ;");
            $stmt = $this->db->prepare("SELECT * FROM message M where  receiver=:receiver and times=(SELECT max(times) from message WHERE M.username=username) ;");
            $stmt->execute($array);
            $message = $stmt->fetchAll(PDO::FETCH_CLASS, 'Message');
            $stmt->closeCursor();
            $stmt=null;

        }catch (PDOException $exception){
            Debug::debug_to_console($exception->getMessage());
            //echo "error!".$exception->getMessage();
            exit();
        }
        return $message;
    }
    public function update($input)
    {
        // TODO: Implement update() method.
    }

}