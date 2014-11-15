<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/api/AccountManager.php");

    if(\Authentication\AccountManager::isValidAuthKey(\Authentication\AccountManager::getKey()) && isset($_GET["id"]) && isset($_GET["value"]))
    {
        $account = new \Authentication\Account(\Authentication\AccountManager::getKey(), $_GET["id"]);

        if($account->setPicture($_GET["value"]))
        {
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