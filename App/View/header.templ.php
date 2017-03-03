<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>My board</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Custom styles for this template -->
    <link href="<?php echo HTTP_SERVER ?>css/style.css" rel="stylesheet">
  </head>

  <body>

    <div class="collapse bg-inverse" id="navbarHeader">
      <div class="container">
        <div class="row">
          <div class="col-sm-8 py-4">
            <h4 class="text-white">About</h4>
            <p class="text-muted">This is my board - and I'm going to write.</p>
          </div>
          <div class="col-sm-4 py-4">
            <?php if(isset($auth) && true === $auth){ ?>
            
            <?php } else { ?>  
            <h4 class="text-white">Sign in</h4>
            <ul class="list-unstyled">
              <li><a href="<?php echo HTTP_SERVER ?>?google&google_auth" class="text-white">
                      <i class="fa fa-google"></i>
                      Google
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
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar navbar-inverse bg-inverse">
      <div class="container d-flex justify-content-between">
        <a href="<?php echo HTTP_SERVER ?>" class="navbar-brand">Board</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>

