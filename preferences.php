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
            if (isset($_SESSION['success'])) {
            echo '<li style="color: lawngreen">' . $_SESSION['success'] . '</li>';
            unset($_SESSION['success']);
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

  $_SESSION['borrower_id'] = htmlentities($_GET['borrower_id']);

  $sql = "SELECT * FROM preferences WHERE borrower_id = :borrower_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ":borrower_id" => htmlentities($_GET['borrower_id'])
  ));
  $rows = array();
  $years = "";
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($row['amortization'] > 1) {
      $years = "Years";
    } elseif ($row['amortization'] <= 1) {
      $years = "Year";
    };
    $rows[] = $row;
  }
  ?>

  <?php echo "<h2>$first_name's mortgage preferences</h2>"; ?>
  <table>
    <tbody id="mytab">
    </tbody>
  </table>

  <div id="options">
  </div>

  <script type="text/javascript" src="jquery.min.js">
  </script>
  <script type="text/javascript">
    function htmlentities(str) {
      return $('<div/>').text(str).html();
    }
  </script>

  <script type="text/javascript">
  $.getJSON('getpreferences.php', function(rows) {
      $("#mytab").empty();
      found = false;
      for (var i = 0; i < rows.length; i++) {
          row = rows[i];
          found = true;
          if (row.type !== null) {
            $("#mytab").append('<tr><td>'
            + 'Mortgage Type</td><td>'
            + htmlentities(row.type)
            + '</td></tr>');
          }
          if (row.product !== null) {
            $("#mytab").append('<tr><td>'
            + 'Product & Term</td><td>'
            + htmlentities(row.product)
            + '</td></tr>');
          }
          if (row.price !== null) {
            $("#mytab").append('<tr><td>'
            + 'Home Price</td><td>'
            + "$" + Number(htmlentities(row.price)).toLocaleString("en")
            + '</td></tr>');
          }
          if (row.closing_date !== null) {
            $("#mytab").append('<tr><td>'
            + 'Closing/Refinance/Renewal Date</td><td>'
            + htmlentities(row.closing_date)
            + '</td></tr>');
          }
          if (row.mortgage_amount !== null) {
            $("#mytab").append('<tr><td>'
            + 'Mortgage Amount Requested</td><td>'
            + "$" + Number(htmlentities(row.mortgage_amount)).toLocaleString("en")
            + '</td></tr>');
          }
          if (row.frequency !== null) {
            $("#mytab").append('<tr><td>'
            + 'Payment Frequency</td><td>'
            + htmlentities(row.frequency)
            + '</td></tr>');
          }
          if (row.amortization !== null) {
            $("#mytab").append('<tr><td>'
            + 'Preferred Amortization Period</td><td>'
            + htmlentities(row.amortization) + " <?php echo $years ?>"
            + '</td></tr>');
          }
          if (row.source !== null) {
            $("#mytab").append('<tr><td>'
            + 'Source of Down Payment</td><td>'
            + htmlentities(row.source)
            + '</td></tr>');
          }
          $("#options").empty();
          $("#options").append("<a class='edit-button' href='editpreferences.php?borrower_id="
          + <?php echo htmlentities($_GET['borrower_id']) ?> + "'>Edit</a>"
          + ' '
          + "<a class='delete-button' href='deletepreferences.php?borrower_id="
          + <?php echo htmlentities($_GET['borrower_id']) ?> + "'>Delete</a>")
      }

      if ( ! found ) {
          $("#mytab").append("<tr><td>No entries found</td></tr>\n");
          $("#options").empty();
          $("#options").append("<a class='button' href='addpreferences.php?borrower_id=<?=$_GET['borrower_id']?>'>Add</a>");
      }
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
