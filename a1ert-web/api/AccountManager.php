<?php
namespace Authentication;
@session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/SQL/sql.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/Account.php");

class AccountManager
{
    private static $_EXPIRED = 86400; //seconds for a key to expire
    private static $_SALT = [
        'salt' => 'hshpwdasdkhas7d&5asd76'
    ];

    public static function createAccount($email, $password, $lvl)
    {
        global $db;

        if(AccountManager::getIDFromEmail($email) > -1)
        {
            return null;
        }

        if($stmt = $db->prepare("INSERT INTO accounts (email, password) VALUES (?, ?)"))
        {
            $stmt->bind_param("ss", strtolower($email), AccountManager::encrypt($password));
            $stmt->execute();
            $stmt->close();
        }

        $id = AccountManager::getIDFromEmail($email);

        if($stmt = $db->prepare("INSERT INTO accountinfo (id) VALUES (?)"))
        {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }

        if($stmt = $db->prepare("INSERT INTO accountpermissions (id, userlevel) VALUES (?, ?)"))
        {
            $stmt->bind_param("ii", $id, $lvl);
            $stmt->execute();
            $stmt->close();
        }

        return AccountManager::login($email, $password);
    }

    public static function deleteAccount($email)
    {
        global $db;

        if($stmt = $db->prepare("SELECT id FROM accounts WHERE email = ?"))
        {
            $stmt->bind_param("s", strtolower($email));
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);

            if($stmt->fetch())
            {
                if($accountinfo = $db->prepare("DELETE FROM accountinfo WHERE id = ?"))
                {
                    $accountinfo->bind_param("i", $id);
                    $accountinfo->execute();
                    $accountinfo->close();
                }

                if($accountpermission = $db->prepare("DELETE FROM accountpermissions WHERE id = ?"))
                {
                    $accountpermission->bind_param("i", $id);
                    $accountpermission->execute();
                    $accountpermission->close();
                }

                if($accountsession = $db->prepare("DELETE FROM accountkeys WHERE id = ?"))
                {
                    $accountsession->bind_param("i", $id);
                    $accountsession->execute();
                    $accountsession->close();
                }

                if($account = $db->prepare("DELETE FROM accounts WHERE id = ?"))
                {
                    $account->bind_param("i", $id);
                    $account->execute();
                    $account->close();
                }
            }

            $stmt->free_result();
            $stmt->close();
        }
    }

    public static function getAllAccounts()
    {
        global $db;

        $accountlist = array();

        if($stmt = $db->prepare("SELECT id FROM accountinfo ORDER BY accountname ASC"))
        {
            $stmt->execute();
            $stmt->bind_result($id);

            $index = 0;

            while($stmt->fetch())
            {
                $accountlist[$index++] = new Account(-1, $id);
            }

            $stmt->close();
        }

        return $accountlist;
    }

    public static function login($email, $password)
    {
        global $db;

        $account = null;

        //is this material correct
        if($stmt = $db->prepare("SELECT id FROM accounts WHERE email = ? AND password = ?"))
        {
            $stmt->bind_param("ss", strtolower($email), AccountManager::encrypt($password));
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);

            if($stmt->fetch())
            {
                $key = AccountManager::generateAuthKey($id);
                $account = new Account($key);
            }

            $stmt->free_result();
            $stmt->close();
        }

        return $account;
    }

    public static function logout($key)
    {
        global $db;

        if($stmt = $db->prepare("DELETE FROM accountkeys WHERE authkey = ?"))
        {
            $stmt->bind_param("s", $key);
            $stmt->execute();
            $stmt->close();
        }
    }

    public static function encrypt($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, AccountManager::$_SALT);
    }

    public static function getKey()
    {
        return isset($_GET["key"]) ? $_GET["key"] : (isset($_SESSION["authkey"]) ? $_SESSION["authkey"] : -1);
    }

    public static function isValidAuthKey($key, $id = -1)
    {
        global $db;
        $stmt = null;
        $issued = null;

        if($id != -1)
        {
            if($stmt = $db->prepare("SELECT issued FROM accountkeys WHERE authkey = ? AND id = ?"))
            {
                $stmt->bind_param("si", $key, $id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($issued);

                if($stmt->fetch())
                {
                    if((time() - $issued) < AccountManager::$_EXPIRED)
                    {
                        return true;
                    }
                    else
                    {
                        AccountManager::removeAuthKey($key);
                    }
                }

                $stmt->free_result();
                $stmt->close();
            }
        }
        else
        {
            if($stmt = $db->prepare("SELECT issued FROM accountkeys WHERE authkey = ?"))
            {
                $stmt->bind_param("s", $key);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($issued);

                if($stmt->fetch())
                {
                    if((time() - $issued) < AccountManager::$_EXPIRED)
                    {
                        return true;
                    }
                    else
                    {
                        AccountManager::removeAuthKey($key);
                    }
                }

                $stmt->free_result();
                $stmt->close();
            }
        }

        return false;
    }

    public static function isSessionAdmin($key)
    {
        global $db;

        $account = new Account($key);
        $lvl = -1;

        if($stmt = $db->prepare("SELECT userlevel FROM accountpermissions WHERE id = ?"))
        {
            $stmt->bind_param("i", $account->id);
            $stmt->execute();
            $stmt->bind_result($lvl);
            $stmt->fetch();
            $stmt->close();
        }

        return $lvl == 1;
    }

    private static function getIDFromEmail($email)
    {
        global $db;

        $id = -1;

        if($stmt = $db->prepare("SELECT id FROM accounts WHERE email = ?"))
        {
            $stmt->bind_param("s", strtolower($email));
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->fetch();
            $stmt->close();
        }

        return $id;
    }

    private static function addAuthKey($id, $key)
    {
        global $db;

        $time = time();

        if ($stmt = $db->prepare("INSERT INTO accountkeys (id, authkey, issued) VALUES (?, ?, ?)"))
        {
            $stmt->bind_param("isi", $id, $key, $time);
            $stmt->execute();
            $stmt->close();
        }
    }

    private static function removeAuthKey($key)
    {
        global $db;

        if($stmt = $db->prepare("DELETE FROM accountkeys WHERE authkey = ?"))
        {
            $stmt->bind_param("s", $key);
            $stmt->execute();
            $stmt->close();
        }
    }

    private static function forceReauth($id)
    {
        global $db;

        if($stmt = $db->prepare("DELETE FROM accountkeys WHERE id = ?"))
        {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    private static function generateAuthKey($id)
    {
        $key = AccountManager::randString(30);

        //is this key valid for returning?
        while(AccountManager::isValidAuthKey($key))
        {
            $key = AccountManager::randString(30);
        }

        AccountManager::addAuthKey($id, $key);

        return $key;
    }

    private static function randString($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
    {
        $str = '';
        $count = strlen($charset);

        while ($length--) {
            $str .= $charset[mt_rand(0, $count - 1)];
        }

        return $str;
    }
}
?>