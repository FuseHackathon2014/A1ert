<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/api/AccountManager.php");

	$folder = "http://" . $_SERVER['HTTP_HOST'];

	define("ROOT", $folder . "/nextgen");

    $accountMessage = "Login";

    if(isset($_SESSION["authkey"]) && \Authentication\AccountManager::isValidAuthKey($_SESSION["authkey"]))
    {
        $accountMessage = "Account";
    }
?>

<div id="header" class="container">
	
	<!-- Logo -->
		<h1 id="logo"><a href=<?php echo "\"" . ROOT . "\""; ?>>iilum</a></h1>
	
	<!-- Nav -->
		<nav id="nav">
			<ul>
				<li><a href=<?php echo "\"" . ROOT . "/blog\""; ?>>Blog</a></li>
				<li class="break"><a href=<?php echo "\"" . ROOT . "/account\""; ?>><?php echo $accountMessage; ?></a></li>
			</ul>
		</nav>
</div>