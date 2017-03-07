<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo HTTP_SERVER ?>favicon.ico">

    <title>Board</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?php echo HTTP_SERVER ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="<?php echo HTTP_SERVER ?>css/style.css" rel="stylesheet">
  </head>

  <body>

    <div class="bg-inverse" id="navbarHeader">
      <div class="container">
        <div class="row">
          <div class="col-sm-8">
            <h4 class="navbar-inverse">
	            <a href="<?php echo HTTP_SERVER ?>" class="navbar-brand text-white">Board</a>
            </h4>
            <p class="text-muted">This is my board - and I'm going to write.</p>
          </div>
          <div class="col-sm-4">
            <?php if (true === $auth) {
    ?>
            <?php if (0 !== count($userinfo)) {
        ?>
		            <div class="user-info row">
			            <div class="col-12 col-sm-4">
				            <img class="img-fluid rounded" src="<?php echo $userinfo['image'] ?>" alt="<?php echo $userinfo['name'] ?>">
			            </div>
			            <div class="col-6 col-sn-8">
				            <h5 class="text-white"><?php echo $userinfo['name'] ?></h5>
				            <p>
					            <a class="text-white" href="<?php echo HTTP_SERVER ?>?logout">
						            Logout
						            <i class="fa fa-sign-out" aria-hidden="true"></i>
					            </a>
				            </p>
			            </div>
		            </div>
            <?php 
    } ?>

            <?php 
} else {
    ?>
            <h4 class="text-white">Log in</h4>
            <ul class="list-unstyled">
              <li><a href="<?php echo HTTP_SERVER ?>?google&google_auth" class="text-white">
                      <i class="fa fa-google-plus" aria-hidden="true"></i>
                  </a>
              </li>
<!--
              <li><a href="#" class="text-white">
                      <i class="fa fa-facebook"></i>
                      Facebook
                  </a>
              </li>
              <li><a href="#" class="text-white">
                      <i class="fa fa-vk"></i>
                      VK
                  </a>
              </li>
-->
            </ul>
            <?php 
} ?>
          </div>
        </div>
      </div>
    </div>

