<?php
/**
 * Created by IntelliJ IDEA.
 * User: pc
 * Date: 9/10/2014
 * Time: 12:08
 */

namespace Blog;

include_once($_SERVER['DOCUMENT_ROOT'] . "/api/SQL/sql.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/Blog/BlogPost.php");

class BlogManager {
    public static function getLatestPosts($count)
    {
        global $db;

        $posts = array();

        if($stmt = $db->prepare("SELECT id FROM blogposts ORDER BY datecreated DESC LIMIT ?"))
        {
            $stmt->bind_param("i", $count);
            $stmt->execute();
            $stmt->bind_result($id);

            $index = 0;

            while($stmt->fetch())
            {
                $posts[$index++] = new BlogPost($id);
            }

            $stmt->close();
        }

        return $posts;
    }
} 