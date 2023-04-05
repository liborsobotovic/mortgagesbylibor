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

  if (isset($_POST['price']) && isset($_POST['balance'])
    && isset($_POST['lender']) && isset($_POST['payments'])
    && isset($_POST['condo'])) {
    $bi = (int)$_POST['borrower_id'];
    if ($_POST['price'] == '' || $_POST['balance'] == ''
      || $_POST['lender'] == '' || $_POST['payments'] == ''
      || $_POST['condo'] == '---SELECT---') {
      $_SESSION['error'] = "Fill out all the mandatory fields";
      header("Location: addhome.php?borrower_id=$bi");
      return;
      }
    $sql = "INSERT INTO home (price, value, balance, lender, payments,
      maturity_date, purchase_date, taxes, original_balance, rate_amount, rate,
      amortization, condo, condo_fee, status, borrower_id) VALUES (:price, :value,
      :balance, :lender, :payments, :maturity_date, :purchase_date, :taxes,
      :original_balance, :rate_amount, :rate, :amortization, :condo, :condo_fee,
      :status, :borrower_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ":price" => $_POST['price'],
      ":value" => $_POST['value'],
      ":balance" => $_POST['balance'],
      ":lender" => $_POST['lender'],
      ":payments" => $_POST['payments'],
      ":maturity_date" => $_POST['maturity_date'],
      ":purchase_date" => $_POST['purchase_date'],
      ":taxes" => $_POST['taxes'],
      ":original_balance" => $_POST['original_balance'],
      ":rate_amount" => $_POST['rate_amount'],
      ":rate" => $_POST['rate'],
      ":amortization" => $_POST['amortization'],
      ":condo" => $_POST['condo'],
      ":condo_fee" => $_POST['condo_fee'],
      ":status" => $_POST['status'],
      ":borrower_id" => $bi,
    ));
    $_SESSION['success'] = "The details have been added";
    header("Location: home.php?borrower_id=$bi");
    return;
  }
  ?>

  <?php echo "<h2>Add $first_name's mortgage preferences</h2>"; ?>
  <div class="add">
  <form class="left-form" method="post">
    <p>
      <label for="price">Original Price Paid for Property</label>
      <input type="number" id="price" name="price">
    </p>
    <p>
      <label for="value">Estimate Value/Sold Price</label>
      <input type="number" id="value" name="value">
    </p>
    <p>
      <label for="balance">Current Mortgage Balance</label>
      <input type="number" id="balance" name="balance">
    </p>
    <p>
      <label for="lender">Current Mortgage Lender</label>
      <input type="text" id="lender" name="lender">
    </p>
    <p>
      <label for="payments">Monthly Payments</label>
      <input type="number" id="payments" name="payments">
    </p>
    <p>
      <label for="maturity_date">Current Maturity Date</label>
      <input type="date" id="maturity_date" name="maturity_date">
    </p>
    <p>
      <label for="purchase_date">Date of Purchase</label>
      <input type="date" id="purchase_date" name="purchase_date">
    </p>
    <p>
      <label for="taxes">Property Taxes</label>
      <input type="number" id="taxes" name="taxes">
    </p>
    <p>
      <label for="original_balance">Original Mortgage Balance</label>
      <input type="number" id="original_balance" name="original_balance">
    </p>
    <p>
      <label for="rate_amount">Current Rate</label>
      <input type="number" id="rate_amount" name="rate_amount">
      <select id="rate" name="rate">
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
      </select>
    </p>
    <p>
      <label for="amortization">Remaining Amortization</label>
      <input type="number" id="amortization" name="amortization">
    </p>
    <p>
      <label for="condo">Is This a Condo?</label>
      <select id="condo" name="condo">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Yes">Yes</option>
        <option value="No">No</option>
      </select>
    </p>
    <p id="condo_fees">
    </p>
    <p>
      <label for="status">Property is:</label>
      <select id="status" name="status">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Not for Sale">Not for Sale</option>
        <option value="Sold">Sold</option>
        <option value="Sold Conditional">Sold Conditional</option>
      </select>
    </p>
    <p class="center-p">
      <input type="hidden" name="borrower_id"
        value="<?= htmlentities($_GET['borrower_id']) ?>">
        <input class="button" type="submit">
        <a class="button" href="home.php?borrower_id=<?= htmlentities($_GET['borrower_id']) ?>">Cancel</a>
    </p>
  </form>
</div>

  <script type="text/javascript" src="jquery.min.js">
  </script>
  <script type="text/javascript">
  $(document).ready(function() {
    $('#condo').change(function() {
      var selected_option = $('#condo').val();

      if(selected_option == 'Yes') {
        $('#condo_fees').empty();
        $('#condo_fees').append(
          '<label for="condo_fee">Monthly Condo Fees</label> \
            <input type="number" id="condo_fee" name="condo_fee">').show();
        } else {
          $('#condo_fees').empty();
        };
      });
    });
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
