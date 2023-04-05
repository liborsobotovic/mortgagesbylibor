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

  if (isset($_POST['debt1']) && isset($_POST['amount1'])) {
    $bi = (int)$_POST['borrower_id'];
    if ($_POST['debt1'] == '---SELECT---' || $_POST['amount1'] == '') {
      $_SESSION['error'] = "Fill out all the mandatory fields";
      header("Location: addliability.php?borrower_id=$bi");
      return;
    }
    for ($i = 1; $i <= 5; $i++) {
      if (! isset($_POST['debt'.$i])) continue;
      if (! isset($_POST['amount'.$i])) continue;
      $debt = $_POST['debt'.$i];
      $detail = $_POST['detail'.$i];
      $amount = $_POST['amount'.$i];

      $sql = "INSERT INTO liabilities (debt, detail, amount, borrower_id)
        VALUES (:debt, :detail, :amount, :borrower_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ":debt" => $debt,
          ":detail" => $detail,
          ":amount" => $amount,
          ":borrower_id" => $bi,
        ));
      };
  $_SESSION['success'] = "The details have been added";
  header("Location: liabilities.php?borrower_id=$bi");
  return;
  };
  ?>

  <?php echo "<h2>Add $first_name's liability</h2>"; ?>
  <div class="add">
  <form class="left-form" method="post">
    <p>
      <label for="debt">Debt</label>
      <select id="debt" name="debt1">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Credit Card">Credit Card</option>
        <option value="Line of Credit">Line of Credit</option>
        <option value="Car Loan/Lease">Car Loan/Lease</option>
        <option value="Student Loan">Student Loan</option>
        <option value="Other Debts">Other Debts</option>
      </select>
    </p>
    <p>
      <label for="detail">Description</label>
      <textarea name="detail1" rows="1" cols="40"></textarea>
    </p>
    <p>
      <label for="amount">Amount</label>
      <input type="number" id="amount" name="amount1">
    </p>
    <p>
      <input class="button" type="button" id="more" value="Add debt">
    </p>
    <div id="next">
    </div>
    <p class="center-p">
      <input type="hidden" name="borrower_id"
        value="<?= htmlentities($_GET['borrower_id']) ?>">
      <input class="button" type="submit">
      <a class="button" href="liabilities.php?borrower_id=<?= htmlentities($_GET['borrower_id']) ?>">Cancel</a>
    </p>
  </form>
</div>

  <script type="text/javascript" src="jquery.min.js">
  </script>
  <script type="text/javascript">
  function remove() {
    document.getElementById("position"+count).remove();
    count--;
    return false;
  };
  count = 1;
    $(document).ready(function() {
      $("#more").click(function(event) {
        event.preventDefault();
        if (count >= 10) {
          alert("Maximum of 10 entries");
          return;
        }
        count++;
        $('#next').append(
          '<div id="position'+count+'"> \
            <br><p><label for="debt">Debt</label> \
            <select id="debt" name="debt'+count+'"> \
              <option value="---SELECT---" selected>---SELECT---</option> \
              <option value="Credit Card">Credit Card</option> \
              <option value="Line of Credit">Line of Credit</option> \
              <option value="Car Loan/Lease">Car Loan/Lease</option> \
              <option value="Student Loan">Student Loan</option> \
              <option value="Other Debts">Other Debts</option> \
            </select> \
          </p> \
          <p> \
            <label for="detail">Description</label> \
            <textarea name="detail'+count+'" rows="1" cols="40"></textarea> \
          </p> \
          <p> \
            <label for="amount">Amount</label> \
            <input type="number" id="amount" name="amount'+count+'"> \
            <input class="button" type="button" value="Remove" onclick="remove()"> \
          </p></div>');
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
