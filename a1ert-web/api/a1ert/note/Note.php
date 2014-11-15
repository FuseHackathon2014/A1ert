<?php
/**
 * Created by IntelliJ IDEA.
 * User: pc
 * Date: 11/14/2014
 * Time: 19:29
 */

namespace a1ert;
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/SQL/sql.php");

class Note
{
    var $content = "Default";
    var $userid = -1;
    var $time = -1;
    var $connotation = 0;
    var $keyword = 0;

    public function __construct($message = "", $userid = -1, $time = -1, $connotation = 0, $keyword = 0) //treat the time like an id with the uID
    {
        global $db;
        $this->content = $message;
        $this->userid = $userid;
        $this->time = $time;
        $this->connotation = $connotation;
        $this->keyword = $keyword;

        if($this->time == -1) //no time set = no note id (CREATE)
        {
            $this->time = time(); //set the time of creation

            if($stmt = $db->prepare("INSERT INTO alert (Uid, message, timeCreated, connotation, containsConditionWords) VALUES (?, ?, ?, ?, ?)"))
            {
                $stmt->bind_param("isiii", $this->userid, $this->content, $this->time, $this->connotation, $this->keyword);
                $stmt->execute();
                $stmt->close();
            }
        }
        else //(RETRIEVE)
        {
            if($stmt = $db->prepare("SELECT message, connotation, containsConditionWords FROM alert WHERE Uid = ? and timeCreated = ?"))
            {
                $stmt->bind_param("ii", $this->userid, $this->time);
                $stmt->execute();
                $stmt->bind_result($this->content, $this->connotation, $this->keyword);
                $stmt->fetch(); //just assume first result atm
                $stmt->close();
            }
        }
    }
} 