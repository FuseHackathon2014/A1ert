<?php
    include_once($_SERVER["DOCUMENT_ROOT"] . "/api/AccountManager.php");

    $badattempt = false;

    if(isset($_POST["email"]) && isset($_POST["password"]))
    {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $account = \Authentication\AccountManager::login($email, $password);

        if($account != null && $account->exists())
        {
            $_SESSION["authkey"] = $account->key;
            $location = isset($_SESSION["authredirect"]) ? $_SESSION["authredirect"] : "/account";
            header("Location: " . "/nextgen" . $location);
        }
        else
        {
            $badattempt = true;
        }
    }
?>
<!DOCTYPE HTML>
<!--
	Telephasic by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>iilum - Login</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="../../css/ie/html5shiv.js"></script><![endif]-->
		<script src="../../js/jquery.min.js"></script>
		<script src="../../js/jquery.dropotron.min.js"></script>
		<script src="../../js/skel.min.js"></script>
		<script src="../../js/skel-layers.min.js"></script>
		<script src="../../js/init.js"></script>
        <link rel="stylesheet" href="../../css/skel.css" />
        <link rel="stylesheet" href="../../css/style.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="../../css/ie/v8.css" /><![endif]-->
	</head>
	<body class="no-sidebar">

        <div id="header-wrapper">
            <?php include($_SERVER['DOCUMENT_ROOT'] . "/nextgen" . "/reuseable/title.php"); ?>
        </div>
		<!-- Footer -->
			<div id="footer-wrapper">
				<div id="footer" class="container">
					<div class="row">
						<section class="6u" style="width: 100%">
							<form method="post" action="#">
                                <?php if($badattempt){?>
                                    <div class="row collapse-at-2 half">
                                        <div class="6u">
                                            <p style="color: red;">Incorrect email and/or password.</p>
                                        </div>
                                     </div>
                                <?php }?>
								<div class="row collapse-at-2 half">
									<div class="6u">
										<input name="email" placeholder="Email" type="text" />
									</div>
								</div>
								<div class="row half">
                                    <div class="6u">
                                        <input name="password" placeholder="Password" type="password" />
                                    </div>
								</div>
								<div class="row half">
									<div class="12u">
										<ul class="actions">
											<li><input type="submit" value="Login" /></li>
										</ul>
									</div>
								</div>
							</form>
						</section>
					</div>
				</div>
                <!-- footer.php -->
			</div>
	</body>
</html>