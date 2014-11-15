<?php
/**
 * Created by IntelliJ IDEA.
 * User: pc
 * Date: 9/10/2014
 * Time: 12:09
 */

namespace Blog;

include_once($_SERVER['DOCUMENT_ROOT'] . "/api/SQL/sql.php");

class BlogPost
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function exists()
    {
        global $db;

        $exists = false;

        if ($stmt = $db->prepare("SELECT * FROM blogposts WHERE id = ?")) {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();

            if ($stmt->fetch()) {
                $exists = true;
            }

            $stmt->close();
        }

        return $exists;
    }

    public function getTitle()
    {
        return $this->getAttribute("title");
    }

    public function getDescription()
    {
        return $this->getAttribute("description");
    }

    public function getImage()
    {
        return $this->getAttribute("image");
    }

    public function getPost()
    {
        return $this->getAttribute("post");
    }

    public function getDate()
    {
        return $this->getAttribute("datecreated");
    }

    private function getAttribute($attribute)
    {
        global $db;

        $value = null;

        if($stmt = $db->prepare("SELECT {$attribute} FROM blogposts where id = ?"))
        {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->bind_result($value);
            $stmt->fetch();
            $stmt->close();
        }

        return $value;
    }
} 