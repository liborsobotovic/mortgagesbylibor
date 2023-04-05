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
            echo "<ul class='topnav-right'>";
            echo "<li><a href='register.php'>Register</a></li>";
            echo "<li><a href='login.php'>Log in</a></li>";
          } else {
            echo "<ul class='topnav'>";
            echo "<li><a href='application.php'>Application</a></li>";
            echo "<li><a href='contact.php'>Contact</a></li>";
            echo "<ul class='topnav-right'>";
            echo "<li><a href='logout.php'>Log out</a></li>";
            echo "<li>Hello " . $_SESSION['username'] . "!</li>";
            if (isset($_SESSION['error'])) {
            echo '<li style="color: red">' . $_SESSION['error'] . '</li>';
            unset($_SESSION['error']);
          }
          }
        ?>
      </ul>
    </nav>
  </header>

  <?php
  require_once "pdo.php";

  $sql = "SELECT * FROM borrower WHERE borrower_id = :borrower_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ":borrower_id" => $_GET['borrower_id']
  ));
  $rows = array();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $first_name = $row['first_name'];
  };

  $sql = "SELECT * FROM other_income WHERE borrower_id = :borrower_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ":borrower_id" => htmlentities($_GET['borrower_id'])
  ));
  $rows = array();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $rows[] = $row['borrower_id'];
  };
  if (! in_array($_GET['borrower_id'], $rows)) {
    $_SESSION['error'] = "Unauthorized access";
    header('Location: index.php');
    return;
  };

  if (isset($_POST['source']) && isset($_POST['income'])) {
    $bi = (int)$_POST['borrower_id'];
    $oi = (int)$_POST['other_income_id'];
    if($_POST['source'] == '' || $_POST['income'] == '') {
        $_SESSION['error'] = "Fill out all the mandatory fields";
        header("Location: other_income.php?borrower_id=$bi&other_income_id=$oi");
        return;
      }
    $sql = "UPDATE other_income SET income_source = :income_source,
      income = :income, borrower_id = :borrower_id
      WHERE borrower_id = :borrower_id AND other_income_id = :other_income_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      "income_source" => $_POST['source'],
      ":income" => $_POST['income'],
      ":borrower_id" => $bi,
      ":other_income_id" => $oi,
    ));
    $_SESSION['success'] = "The details have been updated";
    header("Location: other_income.php?borrower_id=$bi");
    return;
  };

  if (isset($_SESSION['borrower_id'])) {
    $_GET['borrower_id'] = $_SESSION['borrower_id'];
    unset($_SESSION['borrower_id']);
  }

  $sql = "SELECT * FROM other_income WHERE borrower_id = :borrower_id AND
  other_income_id = :other_income_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ":borrower_id" => $_GET['borrower_id'],
    ":other_income_id" => $_GET['other_income_id'],
  ));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row == false) {
    $_SESSION['error'] = "The data does not exist";
    header('Location: application.php');
    return;
  };

  $is = htmlentities($row['income_source']);
  $i = htmlentities($row['income']);
  $id = htmlentities($row['borrower_id']);
  ?>

  <div class="centered-edit">
    <?php echo "<h2>Edit $first_name's other income</h2>"; ?>
  <form class="left-form" method="post">
    <p>
      <label for="source">What is the source of your income?</label>
      <input type="text" id="source" name="source" value="<?php echo $is ?>">
    </p>
    <p>
      <label for="income">What is your income from this source?</label>
      <input type="number" id="income" name="income" value="<?php echo $i ?>">
    </p>
    <p class="center-p">
      <input type="hidden" name="borrower_id"
        value="<?= htmlentities($_GET['borrower_id']) ?>">
      <input type="hidden" name="other_income_id"
        value="<?= htmlentities($_GET['other_income_id']) ?>">
      <input class="button" type="submit" value="Save">
      <a class="button" href="other_income.php?borrower_id=<?= htmlentities($_GET['borrower_id']) ?>">Cancel</a>
    </p>
  </form>
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
