<?php
    include_once($_SERVER["DOCUMENT_ROOT"] . "/api/AccountManager.php");
    $authed = \Authentication\AccountManager::isValidAuthKey(\Authentication\AccountManager::getKey());
?>
<!DOCTYPE HTML>
<!--
	Telephasic by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>iilum - By iismathwizard</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
        <link rel="stylesheet" href="css/skel.css" />
        <link rel="stylesheet" href="css/style.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	<body class="homepage">

		<!-- Header -->
			<div id="header-wrapper" <?php if($authed){ echo "style=\"padding: 6em 0 0 0;\"";}?>/>
				<?php include($_SERVER['DOCUMENT_ROOT'] . "/nextgen" . "/reuseable/title.php");
                      if(!$authed){//should we show the hero ?>

				<!-- Hero -->
					<section id="hero" class="container">
						<header>
							<h2>iilum</h2>
						</header>
						<p>A Data Management and Communications Solution</p>
						<ul class="actions">
							<li><a href="account/signup" class="button">Sign up</a></li>
							<li><a href="account/login" class="button">Login</a></li>
						</ul>
					</section>
                <?php }?>
			</div>

		<!-- Features 1 -->
			<div class="wrapper">
				<div class="container">
					<div class="row">
						<section class="6u feature">
							<div class="image-wrapper first">
								<a href="#" class="image featured first"><img src="images/comingsoon.jpg" alt="" /></a>
							</div>
							<header>
								<h2>Klowd<br />
								A Cloud Storage Solution</h2>
							</header>
							<p>A cloud storage solution that is safe, integrated, and easy to use. Klowd integrates into
                            all applications that <strong>iilum</strong> can and functions as a standard cloud storage
                            for personal use at home or on the go.</p>
							<ul class="actions">
								<li><a href="#" class="button">Learn More</a></li>
							</ul>
						</section>
						<section class="6u feature">
							<div class="image-wrapper">
								<a href="#" class="image featured"><img src="images/comingsoon.jpg" alt="" /></a>
							</div>
							<header>
								<h2>iilum<br />
								The Intelligent Assistant</h2>
							</header>
							<p>A personal assistant that grows and learns the more you talk to it. Easily integratable into
                            personal projects, iilum is great for developers with a simple api and constantly growing database
                            of knowledge.</p>
							<ul class="actions">
								<li><a href="#" class="button">Learn More</a></li>
							</ul>
						</section>
					</div>
				</div>
			</div>
			
		<!-- Promo -->
			<div id="promo-wrapper" style="display: none;">
				<section id="promo">
					<h2>Introducing iiAuth</h2>
					<a href="/iiAuth" class="button">Learn More</a>
				</section>
			</div>
			
		<!-- Features 2 -->
			<div class="wrapper" style="display: none;">
				<section class="container">
					<header class="major">
						<h2>Sed magna consequat lorem curabitur tempus</h2>
						<p>Elit aliquam vulputate egestas euismod nunc semper vehicula lorem blandit</p>
					</header>
					<div class="row features">
						<section class="4u feature">
							<div class="image-wrapper first">
								<a href="#" class="image featured"><img src="images/pic03.jpg" alt="" /></a>
							</div>
							<p>Lorem ipsum dolor sit amet consectetur et sed adipiscing elit. Curabitur 
							vel sem sit dolor neque semper magna lorem ipsum.</p>
						</section>
						<section class="4u feature">
							<div class="image-wrapper">
								<a href="#" class="image featured"><img src="images/pic04.jpg" alt="" /></a>
							</div>
							<p>Lorem ipsum dolor sit amet consectetur et sed adipiscing elit. Curabitur 
							vel sem sit dolor neque semper magna lorem ipsum.</p>
						</section>
						<section class="4u feature">
							<div class="image-wrapper">
								<a href="#" class="image featured"><img src="images/pic05.jpg" alt="" /></a>
							</div>
							<p>Lorem ipsum dolor sit amet consectetur et sed adipiscing elit. Curabitur 
							vel sem sit dolor neque semper magna lorem ipsum.</p>
						</section>
					</div>
					<ul class="actions major">
						<li><a href="#" class="button">Elevate my awareness</a></li>
					</ul>
				</section>
			</div>

		<!-- Footer -->
			<div id="footer-wrapper" style="display: none;">
				<div id="footer" class="container">
					<header class="major">
						<h2>Euismod aliquam vehicula lorem</h2>
						<p>Lorem ipsum dolor sit amet consectetur et sed adipiscing elit. Curabitur vel sem sit<br />
						dolor neque semper magna lorem ipsum feugiat veroeros lorem ipsum dolore.</p>
					</header>
					<div class="row">
						<section class="6u">
							<form method="post" action="#">
								<div class="row collapse-at-2 half">
									<div class="6u">
										<input name="name" placeholder="Name" type="text" />
									</div>
									<div class="6u">
										<input name="email" placeholder="Email" type="text" />
									</div>
								</div>
								<div class="row half">
									<div class="12u">
										<textarea name="message" placeholder="Message"></textarea>
									</div>
								</div>
								<div class="row half">
									<div class="12u">
										<ul class="actions">
											<li><input type="submit" value="Send Message" /></li>
											<li><input type="reset" value="Clear form" /></li>
										</ul>
									</div>
								</div>
							</form>
						</section>
						<section class="6u">
							<div class="row collapse-at-2 flush">
								<ul class="divided icons 6u">
									<li class="icon fa-twitter"><a href="#"><span class="extra">twitter.com/</span>untitled</a></li>
									<li class="icon fa-facebook"><a href="#"><span class="extra">facebook.com/</span>untitled</a></li>
									<li class="icon fa-dribbble"><a href="#"><span class="extra">dribbble.com/</span>untitled</a></li>
								</ul>
								<ul class="divided icons 6u">
									<li class="icon fa-instagram"><a href="#"><span class="extra">instagram.com/</span>untitled</a></li>
									<li class="icon fa-youtube"><a href="#"><span class="extra">youtube.com/</span>untitled</a></li>
									<li class="icon fa-pinterest"><a href="#"><span class="extra">pinterest.com/</span>untitled</a></li>
								</ul>
							</div>
						</section>
					</div>
				</div>
				<div id="copyright" class="container">
					<ul class="menu">
						<li>&copy; Untitled. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
					</ul>
				</div>
			</div>

	</body>
</html>