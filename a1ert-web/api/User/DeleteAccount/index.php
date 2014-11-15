<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/api/AccountManager.php");

    if(isset($_GET["id"]) && ((\Authentication\AccountManager::isValidAuthKey(\Authentication\AccountManager::getKey(), $_GET["id"])
                && isset($_GET["email"]) && isset($_GET["password"]))
            || (\Authentication\AccountManager::isSessionAdmin(\Authentication\AccountManager::getKey()))))
    {
        $id = $_GET["id"];
        $account = null;

        if(\Authentication\AccountManager::isSessionAdmin(\Authentication\AccountManager::getKey()))
        {
            $account = new \Authentication\Account(\Authentication\AccountManager::getKey(), $id);
        }
        else
        {
            $email = $_GET["email"];
            $password = $_GET["password"];
            $account = \Authentication\AccountManager::login($email, $password);
        }


        if($account != null && $account->exists())
        {
            \Authentication\AccountManager::deleteAccount($account->getEmail());
            echo json_encode(array("status" => "valid"));
        }
        else
        {
            echo json_encode(array("status" => "invalid"));
        }
    }
    else
    {
        echo json_encode(array("status" => "invalid"));
    }
?>