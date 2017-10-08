<!DOCTYPE html>
<html>
<head>
  <title> E-Commerce Website</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>
<body>
  <!-- Top navbar -->
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <a href="index.php" class="navbar-brand"> Online Farm Products</a>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Fruits<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Apples</a></li>
            <li><a href="#">Banana</a></li>
            <li><a href="#">Berries & Cherries</a></li>
            <li><a href="#">Grapes</a></li>
            <li><a href="#">Dried Fruits and Nuts</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <!--  Header -->
  <div id="headerWrapper">
    <div id="background"></div>
    <div id="logotext"></div>
    <div id="foreground"></div>
  </div>

<script type="text/javascript">
  jquery(window).scroll(function(){
    var vscroll = jquery(this).scrollTop();
    jquery('#logotext').css({
      "transform" : "translate(0px,"+vscroll/2+"px)"
    });

    var vscroll = jquery(this).scrollTop();
    jquery('#background').css({
      "transform" : "translate("+vscroll/5+"px,-"+vscroll/12+"px)"
    });

    var vscroll = jquery(this).scrollTop();
    jquery('#foreground').css({
      "transform" : "translate(0px,"+vscroll/2+"px)"
    });
  });
</script>
</body>
</html>