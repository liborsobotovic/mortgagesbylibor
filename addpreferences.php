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

  $sql = "SELECT * FROM borrower WHERE user_id = :user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ":user_id" => $_SESSION['id']
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

  if (isset($_POST['type']) && isset($_POST['product'])
    && isset($_POST['frequency']) && isset($_POST['source'])) {
    $bi = (int)$_POST['borrower_id'];
    if ($_POST['type'] == '---SELECT---' || $_POST['product'] == '---SELECT---'
      || $_POST['frequency'] == '---SELECT---'
      || $_POST['source'] == '---SELECT---') {
      $_SESSION['error'] = "Fill out all the mandatory fields";
      header("Location: addpreferences.php?borrower_id=$bi");
      return;
      }
    $sql = "INSERT INTO preferences (type, product, price, closing_date,
      mortgage_amount, frequency, amortization, source, borrower_id)
      VALUES (:type, :product, :price, :closing_date, :mortgage_amount,
      :frequency, :amortization, :source, :borrower_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ":type" => $_POST['type'],
      ":product" => $_POST['product'],
      ":price" => $_POST['price'],
      ":closing_date" => $_POST['closing_date'],
      ":mortgage_amount" => $_POST['mortgage_amount'],
      ":frequency" => $_POST['frequency'],
      ":amortization" => $_POST['amortization'],
      ":source" => $_POST['source'],
      ":borrower_id" => $bi,
    ));
    $_SESSION['success'] = "The details have been added";
    header("Location: preferences.php?borrower_id=$bi");
    return;
  };
  ?>

  <?php echo "<h2>Add $first_name's mortgage preferences</h2>"; ?>
  <div class="add">
  <form class="left-form" method="post">
    <p>
      <label for="type">Mortgage Type</label>
      <select id="type" name="type">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Purchase">Purchase</option>
        <option value="Refinance">Refinance</option>
        <option value="Renewal">Renewal</option>
        <option value="Pre-Approval">Pre-Approval</option>
      </select>
    </p>
    <p>
      <label for="product">Product & Term</label>
      <select id="product" name="product">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="6-Month Fixed">6-Month Fixed</option>
        <option value="1-Year Fixed">1-Year Fixed</option>
        <option value="2-Year Fixed">2-Year Fixed</option>
        <option value="3-Year Fixed">3-Year Fixed</option>
        <option value="4-Year Fixed">4-Year Fixed</option>
        <option value="5-Year Fixed">5-Year Fixed</option>
        <option value="6-Year Fixed">6-Year Fixed</option>
        <option value="7-Year Fixed">7-Year Fixed</option>
        <option value="8-Year Fixed">8-Year Fixed</option>
        <option value="Variable">Variable</option>
        <option value="HELOC">HELOC</option>
        <option value="Unsure">Unsure</option>
      </select>
    </p>
    <p>
      <label for="price">Home Price</label>
      <input type="number" id="price" name="price">
    </p>
    <p>
      <label for="closing_date">Closing/Refinance/Renewal Date</label>
      <input type="date" id="closing_date" name="closing_date">
    </p>
    <p>
      <label for="mortgage_amount">Mortgage Amount Requested</label>
      <input type="number" id="mortgage_amount" name="mortgage_amount">
    </p>
    <p>
      <label for="frequency">Payment Frequency</label>
      <select id="frequency" name="frequency">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Monthly">Monthly</option>
        <option value="Semi-Monthly">Semi-Monthly</option>
        <option value="Bi-Weekly">Bi-Weekly</option>
        <option value="Accelerated Bi-Weekly">Accelerated Bi-Weekly</option>
        <option value="Weekly">Weekly</option>
        <option value="Accelerated Weekly">Accelerated Weekly</option>
        <option value="Unsure">Unsure</option>
      </select>
    </p>
    <p>
      <label for="amortization">Preferred Amortization Period</label>
      <input type="number" id="amortization" name="amortization"
        placeholder="years">
    </p>
    <p>
      <label for="source">Source of Down Payment</label>
      <select id="source" name="source">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Savings">Savings</option>
        <option value="Investments">Investments</option>
        <option value="Gift From Immediate Family">Gift From Immediate Family</option>
        <option value="Existing Deposit on Purchase">Existing Deposit on Purchase</option>
        <option value="Proceeds From Sale of Current Home">Proceeds From Sale of Current Home</option>
      </select>
    </p>
    <p class="center-p">
      <input type="hidden" name="borrower_id"
        value="<?= htmlentities($_GET['borrower_id']) ?>">
        <input class="button" type="submit">
        <a class="button" href="preferences.php?borrower_id=<?= htmlentities($_GET['borrower_id']) ?>">Cancel</a>
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
