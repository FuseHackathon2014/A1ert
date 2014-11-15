<?php
/**
 * Created by IntelliJ IDEA.
 * User: pc
 * Date: 11/15/2014
 * Time: 5:01
 */
    include_once($_SERVER['DOCUMENT_ROOT'] . "/api/AccountManager.php");

    if(!\Authentication\AccountManager::isValidAuthKey(\Authentication\AccountManager::getKey()))
    {
?>
<!-- not logged in -->
<html>
    <head>
        <title>A1ERT</title>
        <link type="text/css" rel="stylesheet" href="style.css">
    </head>
    <body>
        <div id="flex-wrapper">
            <div id="login-h-filler"></div>
            <div id="login-h-wrapper">
                <div id="login-v-wrapper">
                    <h1 class="login-title">A1ERT - Login</h1>
                    <form action="../api/Authentication/Login/" method="get">
                        <input type="text" placeholder="email" name="email" size="30"><br/>
                        <input type="password" placeholder="password" name="password" size="30">
                        <input type="submit" value="Login" class="submitInput">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<?php } else {?>
<!-- logged in -->
<html>
    <head>
        <title>A1ERT - Query Page</title>
        <script type="text/javascript" src="jquery-2.1.1.min.js"></script>
        <link type="text/css" rel="stylesheet" href="style.css">
        <script type="text/javascript">
            function updateQuery()
            {
                var query = document.getElementById("input-query").value;

                $.ajax({
                    url: "https://www.iismathwizard.com/api/a1ert/note/get",
                    type:"get", //send it through get method
                    data:{id:query}})
                    .done(function(response) {
                        document.getElementById("results").innerHTML = response;
                    });
            }
        </script>
    </head>
    <body>
        <div id="q-flex-wrapper">
            <div id="q-search">
                <h2 class="search-title">Search</h2>
                <input type="text" id="input-query" placeholder="User ID">
                <button id="input-query-search" class="submitInput" onclick="updateQuery()">Search</button>
                <div id="logout">
                    <button id="input-query-logout" class="submitInput logout" onclick="window.location.href='https://www.iismathwizard.com/api/authentication/logout'">Logout</button>
                </div>
            </div>
            <div id="results-panel">
                <!-- results here -->
                <div id="results">
                    <!-- code here for results/fixed size with autoscrolling -->
                </div>
            </div>
        </div>
    </body>
</html>
<?php }?>