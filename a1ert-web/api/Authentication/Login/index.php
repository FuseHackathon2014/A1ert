<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/api/AccountManager.php");

	if(isset($_GET["email"]) && isset($_GET["password"]))
    {
		$id = -1;
		$email = $_GET["email"];
		$password = $_GET["password"];

        $account = \Authentication\AccountManager::login($email, $password);

        if($account != null)
        {
            $_SESSION["authkey"] = $account->key;
            //echo json_encode(array("status" => "valid", "user" => array("key" => $account->key, "id" => $account->id)));
        }
        else
        {
            //echo json_encode(array("status" => "invalid"));
        }

        header("location: https://www.iismathwizard.com/a1ert/");
	}
?>