<?php
namespace Authentication;

include_once($_SERVER['DOCUMENT_ROOT'] . "/api/SQL/sql.php");

class Account
{
    public $key = -1;
    public $id = -1;

    function __construct($key = -1, $id = -1)
    {
        global $db;

        $this->key = $key;

        if($id == -1 && AccountManager::isValidAuthKey($key) && $stmt = $db->prepare("SELECT id FROM accountkeys WHERE authkey = ?"))
        {
            $stmt->bind_param("s", $key);
            $stmt->execute();
            $stmt->bind_result($this->id);
            $stmt->fetch();
            $stmt->close();
        }
        else
        {
            $this->id = $id;
        }
    }

    public function exists()
    {
        global $db;

        $exists = false;

        if($stmt = $db->prepare("SELECT email FROM accounts WHERE id = ?"))
        {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->bind_result($email);

            if($stmt->fetch())
            {
                $exists = true;
            }

            $stmt->close();
        }

        return $exists;
    }

    public function getEmail()
    {
        global $db;

        $email = null;

        if($stmt = $db->prepare("SELECT email FROM accounts WHERE id = ?"))
        {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->bind_result($email);
            $stmt->fetch();
            $stmt->close();
        }

        return $email;
    }

    public function setPassword($new, $old)
    {
        global $db;

        $result = false;

        if($stmt = $db->prepare("SELECT id FROM accounts WHERE password = ?"))
        {
            $stmt->bind_param("s", AccountManager::encrypt($old));
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);

            if($stmt->fetch())
            {
                if($stmt2 = $db->prepare("UPDATE accounts SET password = ? WHERE id = ?"))
                {
                    $stmt2->bind_param("si", AccountManager::encrypt($new), $id);
                    $stmt2->execute();
                    $stmt2->close();

                    $result = true;
                }
            }

            $stmt->free_result();
            $stmt->close();
        }

        return $result;
    }

    public function getUserLevel()
    {
        global $db;

        $lvl = -1;

        if($stmt = $db->prepare("SELECT userlevel FROM accountpermissions WHERE id = ?"))
        {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->bind_result($lvl);
            $stmt->fetch();
            $stmt->close();
        }

        return $lvl;
    }

    public function setUserLevel($lvl)
    {
        global $db;

        if(AccountManager::isValidAuthKey($this->key, $this->id) || AccountManager::isSessionAdmin($this->key))
        {
            if($stmt = $db->prepare("UPDATE accountpermissions SET userlevel = ? WHERE id = ?"))
            {
                $stmt->bind_param("ii", $lvl, $this->id);
                $stmt->execute();
                $stmt->close();

                return true;
            }
        }

        return false;
    }

    public function getName()
    {
        global $db;

        $name = null;

        if($stmt = $db->prepare("SELECT accountname FROM accountinfo WHERE id = ?"))
        {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->bind_result($name);
            $stmt->fetch();
            $stmt->close();
        }

        return $name;
    }

    public function setName($name)
    {
        global $db;

        if(AccountManager::isValidAuthKey($this->key, $this->id) || AccountManager::isSessionAdmin($this->key))
        {
            if($stmt = $db->prepare("UPDATE accountinfo SET accountname = ? WHERE id = ?"))
            {
                $stmt->bind_param("si", $name, $this->id);
                $stmt->execute();
                $stmt->close();

                return true;
            }
        }

        return false;
    }

    public function getPicture()
    {
        global $db;

        $picture = null;

        if($stmt = $db->prepare("SELECT profilepicture from accountinfo WHERE id = ?"))
        {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->bind_result($picture);
            $stmt->fetch();
            $stmt->close();
        }

        return $picture;
    }

    public function setPicture($picture)
    {
        global $db;

        if(AccountManager::isValidAuthKey($this->key, $this->id) || AccountManager::isSessionAdmin($this->key))
        {
            if($stmt = $db->prepare("UPDATE accountinfo SET profilepicture = ? WHERE id = ?"))
            {
                $stmt->bind_param("si", $picture, $this->id);
                $stmt->execute();
                $stmt->close();

                return true;
            }
        }

        return false;
    }
}
?>