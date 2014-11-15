<?php
/**
 * Created by IntelliJ IDEA.
 * User: pc
 * Date: 11/15/2014
 * Time: 6:09
 */


include_once($_SERVER['DOCUMENT_ROOT'] . "/api/a1ert/note/NoteManager.php");

if(isset($_GET["id"]))
{
    $id = $_GET["id"];

    $list = \a1ert\NoteManager::getAllNotesForID($id);
    $return = "";

    foreach($list as $note)
    {
        $alert = "";
        if($note->keyword == 1) $alert = " alert";
        if($note->connotation == 1)
        {
            $return .= "<div class=\"note good\">";
        }
        else if($note->connotation == -1)
        {
            $return .= "<div class=\"note bad" . $alert . "\">";
        }else{
            $return .= "<div class=\"note\">";
        }

        $return .= "(" . (date("M-j-Y H:i:s", $note->time)) . ") " . $note->content . "</div>";
    }

    echo $return;
}