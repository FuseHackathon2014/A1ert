<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/api/AccountManager.php");

    if(isset($_GET["id"]))
    {
        $account = new \Authentication\Account(-1, $_GET["id"]);

        if($account->exists())
        {
            echo json_encode(array("status" => "valid", "name" => $account->getName()));
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