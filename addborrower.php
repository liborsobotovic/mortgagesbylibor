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

  $sql = "SELECT * FROM borrower_info WHERE borrower_id = :borrower_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ":borrower_id" => $_GET['borrower_id'],
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($row == true) {
      $_SESSION['error'] = "A record already exists";
      header('Location: index.php');
      return;
    }};

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

  if(isset($_POST['dob']) && isset($_POST['marital_status'])
    && isset($_POST['dependents']) && isset($_POST['cell_phone'])
    && isset($_POST['email']) && isset($_POST['address'])) {
    $bi = (int)$_POST['borrower_id'];
    if ($_POST['own_rent'] == 'Rent') {
      if ($_POST['rent_amount'] == '') {
        $_SESSION['error'] = "Fill out all the mandatory fields";
        header("Location: addborrower.php?borrower_id=$bi");
        return;
      };
    };
    if ($_POST['sin'] == '') {
      $_POST['sin'] = null;
    };
    if ($_POST['dob'] == '' || $_POST['marital_status'] == '---SELECT---'
      || $_POST['dependents'] == '' || $_POST['cell_phone'] == ''
      || $_POST['email'] == '' || $_POST['address'] == '') {
        $_SESSION['error'] = "Fill out all the mandatory fields";
        header("Location: addborrower.php?borrower_id=$bi");
        return;
      }
    $sql = "INSERT INTO borrower_info (DOB, SIN, marital_status, dependents,
      home_phone, cell_phone, work_phone, email, address, move_in_date,
      rent_own, rent_amount, previous_address, borrower_id) VALUES (:DOB,
      :SIN, :marital_status, :dependents, :home_phone, :cell_phone, :work_phone,
      :email, :address, :move_in_date, :rent_own, :rent_amount,
      :previous_address, :borrower_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ":DOB" => $_POST['dob'],
      ":SIN" => $_POST['sin'],
      ":marital_status" => $_POST['marital_status'],
      ":dependents" => $_POST['dependents'],
      ":home_phone" => $_POST['home_phone'],
      ":cell_phone" => $_POST['cell_phone'],
      ":work_phone" => $_POST['work_phone'],
      ":email" => $_POST['email'],
      ":address" => $_POST['address'],
      ":move_in_date" => $_POST['move_in_date'],
      ":rent_own" => $_POST['own_rent'],
      ":rent_amount" => $_POST['rent_amount'],
      ":previous_address" => $_POST['previous_address'],
      ":borrower_id" => $bi,
    ));
    $_SESSION['success'] = "The details have been added";
    header("Location: borrower_info.php?borrower_id=$bi");
    return;
  }
  ?>

  <?php echo "<h2>Add $first_name's information</h2>"; ?>
  <div class="add">
  <form class="left-form" method="post">
    <p>
      <label for="dob">Date of Birth</label>
      <input type="date" id="dob" name="dob">
    </p>
    <p>
      <label for="sin">Social Insurance Number</label>
      <input type="number" id="sin" name="sin" min="111111111" max="999999999">
    </p>
    <p>
      <label for="marital_status">Marital Status</label>
      <select id="marital_status" name="marital_status">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Single">Single</option>
        <option value="Married">Married</option>
        <option value="Widowed">Widowed</option>
        <option value="Divorced">Divorced</option>
      </select>
    </p>
    <p>
      <label for="dependents">Number of Dependents</label>
      <input type="number" id="dependents" name="dependents">
    </p>
    <p>
      <label for="home_phone">Home Phone</label>
      <input type="tel" id="home_phone" name="home_phone"
        pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="123-456-7890">
    </p>
    <p>
      <label for="cell_phone">Cell Phone</label>
      <input type="tel" id="cell_phone" name="cell_phone"
        pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="123-456-7890">
    </p>
    <p>
      <label for="work_phone">Work Phone</label>
      <input type="tel" id="work_phone" name="work_phone"
        pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="123-456-7890">
    </p>
    <p>
      <label for="email">E-mail Address</label>
      <input type="email" id="email" name="email">
    </p>
    <p>
      <label for="address">Present Address</label>
      <textarea name="address" rows="2" cols="40"
        placeholder="Street Number, Street Name&#10;City, Province, Postal Code"></textarea>
    </p>
    <p>
      <label for="move_in_date">Move-In Date</label>
      <input type="date" id="move_in_date" name="move_in_date">
    </p>
    <p>
      <label for="own_rent">Do you own, rent, or live with parents?</label>
      <select id="own_rent" name="own_rent">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Own">Own</option>
        <option value="Rent">Rent</option>
        <option value="Live with parents">Live with parents</option>
      </select>
    </p>
    <p id="rent_amount">
    </p>
    <script type="text/javascript" src="jquery.min.js">
    </script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#move_in_date').change(function() {
          var today = new Date();
          var date = new Date(today.getFullYear(), today.getMonth(),
            today.getDate());
          date.setFullYear(date.getFullYear() - 3);
          var move_date =
            new Date(document.getElementById('move_in_date').value);

          if(move_date > date) {
            $('#previous_address').empty();
            $('#previous_address').append(
              '<label for="previous_address"> \
                Previous Addresses (Last 3 Years)</label>').show();
            $('#previous_address').append(
              '<textarea name="previous_address" rows="12" \
                cols="40" placeholder="Street Number, Street Name \
                City, Province, Postal Code \
                Dates You Lived There"></textarea>').show();
          }
        });
      });
    </script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#own_rent').change(function() {
          var selected_option = $('#own_rent').val();

          if(selected_option == 'Rent') {
            $('#rent_amount').empty();
            $('#rent_amount').append(
              '<label for="rent_amount"> \
                What is the rent amount?</label>').show();
            $('#rent_amount').append(
              '<input type="number" id="rent_amount" \
                name="rent_amount">').show();
            };
          if(selected_option !== 'Rent') {
            $('#rent_amount').empty()
            }
          });
        });
    </script>
    <p id="previous_address">
    </p>
    <input type="hidden" name="borrower_id"
      value="<?= htmlentities($_GET['borrower_id']) ?>">
    <p class="center-p">
      <input class="button" type="submit">
      <a class="button" href="borrower_info.php?borrower_id=<?= htmlentities($_GET['borrower_id']) ?>">Cancel</a>
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
