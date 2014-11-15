<!DOCTYPE HTML>
<!--
	Telephasic by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<?php
	include ($_SERVER['DOCUMENT_ROOT'] . "/api/blog/BlogManager.php");

    $mainPost;

    if(isset($_GET["id"]))
    {
        $mainPost = new \Blog\BlogPost($_GET["id"]);
    }
    else
    {
        $postList = Blog\BlogManager::getLatestPosts(1);
        $mainPost = count($postList) > 0 ? $postList[0] : $mainPost = new Blog\BlogPost(-1); //if the post doesn't exist, give a bad entry
    }
?>

<html>
	<head>
		<title>Blog - iilum</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="../css/ie/html5shiv.js"></script><![endif]-->
		<script src="../js/jquery.min.js"></script>
		<script src="../js/jquery.dropotron.min.js"></script>
		<script src="../js/skel.min.js"></script>
		<script src="../js/skel-layers.min.js"></script>
		<script src="../js/init.js"></script>
        <link rel="stylesheet" href="../css/skel.css" />
        <link rel="stylesheet" href="../css/style.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="../css/ie/v8.css" /><![endif]-->
	</head>
	<body class="right-sidebar">

		<!-- Header -->
			<div id="header-wrapper">				
				<?php include($_SERVER['DOCUMENT_ROOT'] . "/nextgen" . "/reuseable/title.php"); ?>		
			</div>

		<!-- Main -->
			<div class="wrapper">
				<div class="container" id="main">
					<div class="row oneandhalf">
						<div class="8u">
					
							<!-- Content -->
								<article id="content">
									<header>
										<h2><?php echo $mainPost->getTitle();?></h2>
										<p><?php echo $mainPost->getDescription();?></p>
									</header>
									<a href="#" class="image featured">
                                        <?php
                                            $location = "../images/pic01.jpg";

                                            if($mainPost->getImage() != "")
                                            {
                                                $location = $mainPost->getImage();
                                            }

                                            echo "<img src=\"{$location}\"/>";
                                        ?>
                                    </a>
									<p>
                                        <?php
                                            $message = $mainPost->getPost();
                                            $message = trim($message, " ");
                                            $message = str_replace("\r\n\r\n", "</p><p>", $message);

                                            echo $message;
                                        ?>
                                    </p>
								</article>

						</div>
						<div class="4u">

							<!-- Sidebar -->
								<section id="sidebar">
									<section>
										<header>
											<h3>Blog - Nathanial Lattimer</h3>
										</header>
										<p>This is my personal blog where I'll keep track of projects and my progress with them. I'll also put some neat things I've learned from various aspects of programming here.</p>
									</section>
									<section>
										<a href="https://plus.google.com/+NathanialLattimer" class="image featured"><img src="http://upcity.com/blog/wp-content/uploads/2013/11/Google-Plus-Logo-650x318.png" alt="" /></a>
										<header>
											<h3>Find Me On Google Plus</h3>
										</header>
										<p>I like to post occasional programming humour as well as thoughts on major tech releases or expos. Think: Google IO, WWDC and XKCD and that pretty much sums up my page.</p>
									</section>
								</section>
					
						</div>
					</div>
					<div class="row features">
                        <?php
                            $posts = \Blog\BlogManager::getLatestPosts(4);

                            $index = 0;

                            while($index < count($posts))
                            {
                                $post = $posts[$index++];

                                if($post->id == $mainPost->id) continue;
                            ?>

                                <section class="4u feature">
                                    <div class=<?php echo "\"image-wrapper";
                                                     if($index < 3) echo " first";
                                                     echo "\""; ?>>
                                        <a href=<?php echo "\"?id={$post->id}\"";?> class="image featured">
                                            <?php
                                                $location = "../images/pic03.jpg";

                                                if($post->getImage() != "")
                                                {
                                                    $location = $post->getImage();
                                                }

                                                echo "<img src=\"{$location}\"/>";
                                            ?>
                                        </a>
                                    </div>
                                    <header>
                                        <h3><?php echo $post->getTitle();?></h3>
                                    </header>
                                    <p><?php echo $post->getDescription();?></p>
                                    <ul class="actions">
                                        <li><a href=<?php echo "\"?id={$post->id}\"";?> class="button">Read More</a></li>
                                    </ul>
                                </section>

                            <?php
                            }

                        ?>
					</div>
				</div>
			</div>
	</body>
</html>