<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/api/AccountManager.php");

    $result = array();
    $accounts = \Authentication\AccountManager::getAllAccounts();

    foreach($accounts as $index => $account)
    {
        $result[$index]["id"] = $account->id;
        $result[$index]["email"] = $account->getEmail();
        $result[$index]["name"] = $account->getName();
        $result[$index]["picture"] = $account->getPicture();
        $result[$index]["userlevel"] = $account->getUserLevel();
    }

    echo json_encode(array("status" => "valid", "accounts" => $result));
?>