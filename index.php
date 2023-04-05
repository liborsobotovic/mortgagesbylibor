<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="mortgage" content="A webpage of a mortgage agent.">
  <title>Mortgages by Libor</title>
  <link rel="icon" type="images/x-icon" href="./image/mortgagesbylibor.png"/>
  <link rel="stylesheet" type="text/css" href="./css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <script>
    (function(w,d,e,u,f,l,n){w[f]=w[f]||function(){(w[f].q=w[f].q||[])
    .push(arguments);},l=d.createElement(e),l.async=1,l.src=u,
    n=d.getElementsByTagName(e)[0],n.parentNode.insertBefore(l,n);})
    (window,document,'script','https://assets.mailerlite.com/js/universal.js','ml');
    ml('account', '35108');
</script>
</head>
<body>

  <header>
    <nav>
      <ul class="topnav">
        <li><a href="index.php"><img class="header-img" src="./image/mortgagesbylibor.png" alt="Better Mortgage Image" style="width: 70px; height: 70px;"></li></a></img>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="calculator.php">Calculator</a></li>

        <?php
        session_start();

          if (!isset($_SESSION['username'])) {
            echo "<li><a href='contact.php'>Contact</a></li>";
            echo "<ul class='topnav-right'>";
            echo "<li><a href='register.php'>Register</a></li>";
            echo "<li><a href='login.php'>Log in</a></li>";
            if (isset($_SESSION['success'])) {
            echo '<li style="color: lawngreen">' . $_SESSION['success'] . '</li>';
            unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
            echo '<li style="color: red">' . $_SESSION['error'] . '</li>';
            unset($_SESSION['error']);
          }
          } else {
            echo "<ul class='topnav'>";
            echo "<li><a href='application.php'>Application</a></li>";
            echo "<li><a href='contact.php'>Contact</a></li>";
            echo "<ul class='topnav-right'>";
            echo "<li><a href='logout.php'>Log out</a></li>";
            echo "<li>Hello " . $_SESSION['username'] . "!</li>";
            if (isset($_SESSION['success'])) {
            echo '<li style="color: lawngreen">' . $_SESSION['success'] . '</li>';
            unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
            echo '<li style="color: red">' . $_SESSION['error'] . '</li>';
            unset($_SESSION['error']);
          }
          }
        ?>
      </ul>
    </nav>
  </header>

  <div class="jumbotron">
    <img class="jumbotron-logo" src="./image/jumbotron-logo.png" alt="Logo">
    <p class="lead">
      Welcome to Mortgages by Libor! I'm here to help you navigate the complex and often confusing world of mortgages. Whether you're a first-time homebuyer, looking to refinance your existing home, or looking for investment property financing, I'm here to make the process as easy and stress-free as possible.
    </p>
    <p class="lead">
      I know that every client is unique, and I take the time to understand your specific needs and goals. I'll work with you to find the best mortgage solution that fits your budget and long-term financial objectives. I have access to a wide range of lenders and products, and I'll use my expertise to find the best options for you.
    </p>
    <p class="lead">
      At Mortgages by Libor, I pride myself on providing exceptional customer service. I'll guide you through every step of the mortgage process, from pre-approval to closing, and beyond. My goal is to make your home buying experience enjoyable and rewarding.
    </p>
    <hr class="my-4">
    <p>
      Don't let the complexity of the mortgage process intimidate you. Let me help you achieve your dream of homeownership. Contact me today to schedule a consultation and get started on your journey to owning your dream home.
    </p><br>
    <p class="center-p">
      <?php
      if (!isset($_SESSION['username'])) {
        echo '<a class="btn btn-primary btn-lg" href="register.php">Apply now &raquo;</a>
        <a class="btn btn-primary btn-lg" href="contact.php">Contact me &raquo;</a>';
      } else {
        echo '<a class="btn btn-primary btn-lg" href="application.php">Apply now &raquo;</a>
        <a class="btn btn-primary btn-lg" href="contact.php">Contact me &raquo;</a>';
      }
      ?>
    </p>
  </div>

<div class="slideshow-container">

<div class="mySlides fade">
  <div class="numbertext">1 / 3</div>
  <img src="./image/alterna3.png" style="width:16%">
  <img src="./image/Duca3.png" style="width:16%">
  <img src="./image/Home-Trust3.png" style="width:16%">
  <img src="./image/MCAP3.png" style="width:16%">
  <img src="./image/Radius3.png" style="width:16%">
  <img src="./image/Scotiabank3.png" style="width:16%">
</div>

<div class="mySlides fade">
  <div class="numbertext">2 / 3</div>
  <img src="./image/CWB3.png" style="width:16%">
  <img src="./image/EQ3.png" style="width:16%">
  <img src="./image/Lendwise3.png" style="width:16%">
  <img src="./image/Meridian3.png" style="width:16%">
  <img src="./image/RFA3.png" style="width:16%">
  <img src="./image/TD3.png" style="width:16%">
</div>

<div class="mySlides fade">
  <div class="numbertext">3 / 3</div>
  <img src="./image/Desjardins3.png" style="width:16%">
  <img src="./image/Haventree3.png" style="width:16%">
  <img src="./image/Manulife.png" style="width:16%">
  <img src="./image/Merix3.png" style="width:16%">
  <img src="./image/RMG3.png" style="width:16%">
  <img src="./image/XMC3.png" style="width:16%">
</div>

</div>
<br>

<div style="text-align:center">
  <span class="dot"></span>
  <span class="dot"></span>
  <span class="dot"></span>
</div>

<script type="text/javascript">
let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  setTimeout(showSlides, 2000); // Change image every 2 seconds
}
</script>

  <div class="footer-dark">
          <footer>
              <div class="container">
                  <div class="row">
                      <div class="col-sm-6 col-md-3 item">
                          <ul>
                              <img src="./image/mortgagesbylibor.png" alt="Better Mortgage Image" style="width: 200px; height: 200px;"/>
                          </ul>
                      </div>
                      <div class="col-sm-6 col-md-3 item">
                        <h3>Quick Links</h3>
                          <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="about.php">About</a></li>
                            <li><a href="calculator.php">Calculator</a></li>
                            <li><a href="contact.php">Contact</a></li>
                            <?php
                            if (!isset($_SESSION['username'])) {
                              echo "<li><a href='register.php'>Register</a></li>";
                              echo "<li><a href='login.php'>Log in</a></li>";
                            } else {
                              echo "<li><a href='application.php'>Application</a></li>";
                              echo "<li><a href='logout.php'>Log out</a></li>";
                            }
                            ?>
                          </ul>
                      </div>
                      <div class="col-md-6 item text">
                          <h3>Mortgages by Libor</h3>
                          <p>E-mail: <a class="footer-a" href="mailto:mortgagesbylibor@gmail.com">mortgagesbylibor@gmail.com</a></p>
                          <p>Phone: 289-707-6626</p>
                          <p>Website: <a class="footer-a" href="www.mortgagesbylibor.com" target="_blank">www.mortgagesbylibor.com</a></p>
                          <p>FSRA # M21000926</p>
                          <a class="ml-onclick-form" href="javascript:void(0)" onclick="ml('show', 'gqd1W1', true)">Subscribe to my newsletter</a>
                      </div>
                      <div class="col item social"><a href="https://www.facebook.com/libor.sobotovic" target="_blank"><i class="icon ion-social-facebook"></i></a><a href="https://twitter.com/Leebors" target="_blank"><i class="icon ion-social-twitter"></i></a><a href="https://www.instagram.com/liborsobotovic/" target="_blank"><i class="icon ion-social-instagram"></i></a></div>
                  </div>
                  <p class="copyright">Mortgages by Libor © 2023</p>
              </div>
          </footer>
      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>

</body>
</html>
