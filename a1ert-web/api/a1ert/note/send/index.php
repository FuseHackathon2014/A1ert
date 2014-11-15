<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/api/a1ert/note/NoteManager.php");
/**
 * Created by IntelliJ IDEA.
 * User: pc
 * Date: 11/14/2014
 * Time: 19:28
 */
    if(isset($_GET["message"]) && isset($_GET["userid"]))
    {
        \a1ert\NoteManager::createNote($_GET["message"], $_GET["userid"]);
        echo json_encode(array("status" => "success"));
    }
    else
    {
        echo json_encode(array("status" => "Missing attribute"));
    }
?>