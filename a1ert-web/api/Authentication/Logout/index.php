<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/api/AccountManager.php");

    if(\Authentication\AccountManager::isValidAuthKey(\Authentication\AccountManager::getKey()))
    {
        \Authentication\AccountManager::logout(\Authentication\AccountManager::getKey());

        //echo json_encode(array("status" => "valid"));
    }
    else
    {
        //echo json_encode(array("status" => "invalid"));
    }

    header("location: https://www.iismathwizard.com/a1ert/");
?>