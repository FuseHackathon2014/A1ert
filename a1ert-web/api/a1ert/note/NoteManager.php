<?php
/**
 * Created by IntelliJ IDEA.
 * User: pc
 * Date: 11/14/2014
 * Time: 19:38
 */

namespace a1ert;
@session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/SQL/sql.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/a1ert/note/Note.php");

class NoteManager {
    public static function createNote($message, $id)
    {
        $resultSet = explode(",", shell_exec("java -jar /var/www/html/api/a1ert/note/send/A1ERTengine.jar \"" . strtolower($message) . "\" \"chest, jaw, toothache, sweating\" 2>&1"));
        print_r($resultSet);

        $contains = trim($resultSet[1]) == 'false' ? 0 : 1;

        if($resultSet[0] == -1 && $contains == 1)
        {
            NoteManager::emailAlert($id, $message);
        }

        new Note($message, $id, -1, $resultSet[0], $contains); //just instantiate so we can have it automatically added
    }

    public static function getNote($id, $time)
    {
        $note = new Note(null, $id, $time);
        return $note;
    }

    public static function getAllNotesForID($id)
    {
        global $db;

        $resultArr = array();

        if($stmt = $db->prepare("SELECT timeCreated from alert WHERE Uid = ?"))
        {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($time);
            $stmt->store_result();

            $iterator = 0;

            while($stmt->fetch())
            {
                $resultArr[$iterator++] = new Note(null, $id, $time);
            }

            $stmt->free_result();
            $stmt->close();
        }

        return $resultArr;
    }

    private static function emailAlert($id, $message)
    {
        $url = 'https://api.sendgrid.com/';
        $user = USER;
        $pass = PASS;

        $params = array(
            'api_user'  => $user,
            'api_key'   => $pass,
            'to'        => 'healthcareprovider@mailinator.com',
            'subject'   => 'Client Issue',
            'html'      => "<p>A client (id: {$id}) has created a note which we've determined may need further review by a physician.<br/> Note: <p style=\"font-size: 18px;\">{$message}</p></p>",
            'text'      => '',
            'from'      => 'a1ert@sendgrid.com',
        );
        $request =  $url.'api/mail.send.json';

        // Generate curl request
        $session = curl_init($request);

        // Tell curl to use HTTP POST
        curl_setopt ($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        // Tell PHP not to use SSLv3 (instead opting for TLS)
        curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        curl_exec($session);
        curl_close($session);
    }
} 
