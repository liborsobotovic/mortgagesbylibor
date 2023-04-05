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

  $sql = "SELECT income_source FROM employment WHERE borrower_id = :borrower_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ":borrower_id" => htmlentities($_GET['borrower_id'])
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $source = $row['income_source'];
  };

  $sql = "SELECT marital_status, dependents FROM borrower_info WHERE
    borrower_id = :borrower_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ":borrower_id" => htmlentities($_GET['borrower_id'])
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $children = $row['dependents'];
    $marital_status = $row['marital_status'];
  };

  $sql = "SELECT rent_own FROM borrower_info WHERE borrower_id = :borrower_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ":borrower_id"=> htmlentities($_GET['borrower_id'])
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $rent = $row['rent_own'];
  };

  $sql = "SELECT status FROM home WHERE borrower_id = :borrower_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ":borrower_id" => htmlentities($_GET['borrower_id'])
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $sold = $row['status'];
  };

  $sql = "SELECT type, source FROM preferences WHERE borrower_id = :borrower_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ":borrower_id" => htmlentities($_GET['borrower_id'])
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $purchase = $row['type'];
    $source = $row['source'];
  };
  ?>
  <div class="centered-documents">
  <h2>Common Mistakes</h2>
  <ul>
    <li>Documents must have your name on them or some way to connect your name
      to the account on your bank statement.</li>
    <li>Due to security and privacy reasons, please do not text documents.</li>
    <li>Please send documents as a PDF via e-mail (not pictures from a cell
      phone via text).</li>
    <li>Income documents must be within 30 days of the submission.</li>
    <li>Do not cross out any data on any documents.</li>
  </ul>

  <?php echo "<h2>List of required documents for $first_name</h2>"; ?>
  <div id="status">
  </div>
  <div id="own">
  </div>

  <h3>ID and Payment Documents</h3>
  <ul id="marital_status">
    <li>2 pieces of acceptable, valid, and current ID. One with photo (driver's
    license, passport, credit card, SIN card, birth certificate, etc.).
    No health cards are permitted.</li>
    <li>A void cheque from the account where you wish the loan to be paid
      from.</li>
  </ul>

  <div id="down_payment">
  </div>

  <div id="purchase_section">
  </div>

  <div id="sale_section">
  </div>

  <h3>Assets/Net Worth</h3>
  <ul>
    <li>If the mortgage requires extended income or a high mortgage amount,
      we recommend including a net-worth statement or proof of assets (e.g.
      stocks, bonds, mutual funds, RRSP, etc.). Is the money in Canada or
      another country, etc.?
    </li>
  </ul>
</div>

  <script type="text/javascript" src="jquery.min.js">
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      var source = "<?php echo $source; ?>";
      var children = "<?php echo $children; ?>";
      var rent = "<?php echo $rent; ?>";
      var sold = "<?php echo $sold; ?>";
      var marital_status = "<?php echo $marital_status; ?>";
      var purchase = "<?php echo $purchase; ?>";
      var source = "<?php echo $source; ?>";

      if (source == "employed") {
        $('#status').empty();
        $('#status').append(
          '<h3>Income Documents</h3> \
          <ul><li>Most recent pay stub (showing the complete hours).</li> \
          <li>Recent job letter stating minimum hours, job title, salary or \
          hourly rate, and starting date (no job offers). Ideally signed with \
          manager contact, recently dated.</li> \
          <li>T1 Generals and Notice of Assessment (NOA) for most recent 2 \
          years (required if we are using your 2-year bonus average).</li> \
          <li>T4s for the most recent 2 or 3 years (required if we are using \
          your 2 or 3-year bonus average).</li></ul>').show();
      } else {
        $('#status').empty();
        $('#status').append(
          '<h3>Income Documents</h3> \
          <ul><li>T1 Generals (the full version, not the condensed version), \
          T4s and NOAs for most recent 2-3 years.</li> \
          <li>Articles of incorporation and last 2 years of business \
          financials (if incorporated), recent 3-6 months of bank statements. \
          </li> \
          <li>6 months of bank statements showing your active cash flow.</li> \
          <li>Year-to-date commission/income plus "booked" income.</li></ul>')
          .show();
      };
      if (children > 0) {
        $('#status ul').append('<li>Proof of child tax credit and/or \
        alimony/child support (if applicable). How old are the kids (13+? \
        When is the closing?). Document from CRA and 3-month bank \
        deposits/statements.</li>').show();
      };
      if (rent == "Own") {
        $('#own').empty();
        $('#own').append(
          '<h3>Property Documents</h3> \
          <ul><li>Current mortgage statement.</li> \
          <li>Current property tax statement.</li> \
          <li>Lease statement, if property rented.</li> \
          <li>T1 Generals for the past 2 years (the full version, not the \
            condensed version).</li></ul>').show();
      };
      if (sold == "sold" || sold == "conditional") {
        $('#own ul').append('<li>Complete sale agreement including any waivers \
        for conditions and MLS listing.</li>').show();
      };
      if (marital_status == "divorced") {
        $('#marital_status').append('<li>Separation agreement.</li>')
      }
      if (purchase == "purchase" || purchase == "pre_approval") {
        $('#down_payment').empty();
        $('#down_payment').append('<h3>Down Payment</h3> \
        <ul><li>Proof of your deposit on the \
        purchase (i.e. photocopy of bank draft). Plus 90 days history of the \
        account where the money came from showing the withdrawal of the \
        actual deposit.</li> \
        <li>90 days history from any account where the down payment will be \
        coming from (e.g. RRSPs, TFSAs, chequing, etc.). Please make sure your \
        name is included on the statements.  If you are transferring funds, \
        please include all the accounts so the money can be traced.</li>').show()
      };
      if (sold == "sold" || sold == "conditional") {
        $('#down_payment ul').append('<li>If down payment comes from the sale \
        of another property, the sale agreement and mortgage statement for that \
        home. If the property is already closed and sold, the statement of \
        adjustments on the sale from your lawyer.</li>').show();
      };
      if (source == "gift") {
        $('#down_payment ul').append('<li>Signed gift letter. Let us know \
        as soon as possible about any gifts (most lenders do not permit gifts \
        on rental properties).</li>').show();
      };
      if (purchase == "purchase" || purchase == "pre_approval") {
        $('#purchase_section').empty();
        $('#purchase_section').append('<h3>For the Purchase</h3> \
        <ul><li>Fully executed Agreement of Purchase and Sale for the new home \
        with all amendments, waivers, and schedules. (Do not waive financing \
        until consulting with us).</li> \
        <li>MLS Listing for the new property.</li> \
        <li>Lawyer information.</li> \
        <li>Any lease agreements, if applicable.</li>').show();
      };
      if (purchase == "pre_approval") {
        $('#purchase_section ul').append('<li>If you have not purchased a \
        property yet, please send a sample realtor-full version of MLS of the \
        type of property you like, so I can understand the size, property \
        taxes, maintenance fees, etc.').show();
      };
      if (sold == "sold" || sold == "conditional") {
        $('#sale_section').append('<h3>For the Sale</h3> \
        <ul><li>Fully executed Sale Agreement with all amendments, waivers, \
        and schedules.</li></ul>').show();
      };
    })
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
