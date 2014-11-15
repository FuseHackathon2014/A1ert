<?php
    include_once("BlogManager.php");
/**
 * Created by IntelliJ IDEA.
 * User: pc
 * Date: 9/10/2014
 * Time: 12:07
 */
// ----------- THIS IS A TEST AND SHOULD BE REMOVED BEFORE RELEASE -----------  //
    $post = \Blog\BlogManager::getLatestPosts(1)[0];

    echo "TITLE: {$post->getTitle()}<br/>";
    echo "DESCRIPTION: {$post->getDescription()}<br/>";
    echo "IMAGE: {$post->getImage()}<br/>";
    echo "POST: {$post->getPost()}<br/>";
    echo "DATE: {$post->getDate()}<br/>";
?>