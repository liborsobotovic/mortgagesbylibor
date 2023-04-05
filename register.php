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
            if (isset($_SESSION['error'])) {
            echo '<li style="color: red">' . $_SESSION['error'] . '</li>';
            unset($_SESSION['error']);
          }
          } else {
            echo "<ul class='topnav'>";
            echo "<li><a href='application.php'>Application</a></li>";
            echo "<ul class='topnav-right'>";
            echo "<li><a href='logout.php'>Log out</a></li>";
            echo "<li>Hello " . $_SESSION['username'] . "!</li>";
          }
        ?>
      </ul>
    </nav>
  </header>

  <?php
    require_once "pdo.php";

    if(isset($_POST['username']) && isset($_POST['password'])
      && isset($_POST['password1'])) {
      if ($_POST['username'] == '' || $_POST['password'] == ''
        || $_POST['password1'] == '') {
        $_SESSION['error'] = "Fill out all the mandatory fields";
        header("Location: register.php?borrower_id=$bi");
        return;
      };
      if ($_POST['password'] !== $_POST['password1']) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: register.php?borrower_id=$bi");
        return;
      };
      $sql = "SELECT username FROM user WHERE username = :username";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ":username" => $_POST['username']));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row['username']) {
        $_SESSION['error'] = "Username already exists";
        header("Location: register.php?borrower_id=$bi");
        return;
      };
      $hash_and_salt = password_hash($_POST['password'], PASSWORD_DEFAULT, array('cost' => 9));
      $sql = "INSERT INTO user (username, password) VALUES
        (:username, :password)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ":username" => $_POST['username'],
        ":password" => $hash_and_salt));
      $_SESSION['success'] = "Username " . htmlentities($_POST['username'])
        . " has been created.";
      $_SESSION['username'] = htmlentities($_POST['username']);
      $sql = "SELECT * FROM user WHERE username = :username
        AND password = :password";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ":username" => $_POST['username'],
        ":password" => $_POST['password']));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $_SESSION['id'] = $row['id'];
      header('Location: index.php');
      return;
    }
  ?>

  <div class="main">
    <h2>Register</h2>
  <div class="add">
  <form class="left-form" method="post">
  <p>
    <label for="username">Username</label>
    <input type="text" id="username" name="username">
  </p>
  <p>
    <label for="password">Password</label>
    <input type="password" name="password">
  </p>
  <p>
    <label for="password1">Re-Type Password</label>
    <input type="password" name="password1">
  </p>
  <p class="center-p">
    <input class="button" type="submit">
    <a class="button" href="index.php">Cancel</a>
  </p>
  </form>
</div>
</div>

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
