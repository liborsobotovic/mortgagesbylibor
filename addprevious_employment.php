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

  if(isset($_POST['employer']) && isset($_POST['address'])
    && isset($_POST['source']) && isset($_POST['start_date'])
    && isset($_POST['end_date']) && isset($_POST['bonus'])) {
    $bi = (int)$_POST['borrower_id'];
    if($_POST['employer'] == '' || $_POST['address'] == ''
      || $_POST['source'] == '---SELECT---' || $_POST['start_date'] == ''
      || $_POST['end_date'] == '' || $_POST['bonus'] == '') {
        $_SESSION['error'] = "Fill out all the mandatory fields";
        header("Location: addprevious_employment.php?borrower_id=$bi");
        return;
      }
    $sql = "INSERT INTO previous_employer (employer, address, income_source,
      occupation, start_date, end_date, income, bonus, type,
      frequency, borrower_id) VALUES (:employer, :address, :income_source,
      :occupation, :start_date, :end_date, :income, :bonus,
      :type, :frequency, :borrower_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ":employer" => $_POST['employer'],
      ":address" => $_POST['address'],
      ":income_source" => $_POST['source'],
      ":occupation" => $_POST['occupation'],
      ":start_date" => $_POST['start_date'],
      ":end_date" => $_POST['end_date'],
      ":income" => $_POST['income'],
      ":bonus" => $_POST['bonus'],
      ":type" => $_POST['type'],
      ":frequency" => $_POST['frequency'],
      ":borrower_id" => $bi,
    ));
    $_SESSION['success'] = "The details have been added";
    header("Location: previous_employment.php?borrower_id=$bi");
    return;
  }
  ?>

  <?php echo "<h2>Add $first_name's previous employment information</h2>"; ?>
  <div class="add">
  <form class="left-form" method="post">
    <script type="text/javascript" src="jquery.min.js">
    </script>
    <p>
      <label for="employer">Employer/Company Name</label>
      <input type="text" id="employer" name="employer">
    </p>
    <p>
      <label for="address">Address</label>
      <textarea name="address" rows="2" cols="40"
        placeholder="Street Number, Street Name&#10;City, Province, Postal Code"></textarea>
    </p>
    <p>
      <label for="source">Income Source</label>
      <select id="source" name="source">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Employed">Employed</option>
        <option value="Self-Employed">Self-Employed</option>
        <option value="Incorporated">Incorporated</option>
      </select>
    </p>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#source').change(function() {
          var selected_option = $('#source').val();

          if(selected_option == 'Employed') {
            $('#income').show()
          };
          if(selected_option == 'Self-Employed') {
            $('#income').hide()
          };
          if(selected_option == 'Incorporated') {
            $('#income').hide()
          }
          });
        });
    </script>
    <p>
      <label for="occupation">Occupation/Job Title</label>
      <input type="text" id="occupation" name="occupation">
    </p>
    <p>
      <label for="start_date">Start Date</label>
      <input type="date" id="start_date" name="start_date">
    </p>
    <p>
      <label for="end_date">End Date</label>
      <input type="date" id="end_date" name="end_date">
    </p>
    <p id="income">
      <label for="income">Annual Income (Base Salary)</label>
      <input type="number" id="income" name="income">
    </p>
    <p>
      <label for="bonus">Annual Income (Bonus/Commission/Business Income)</label>
      <input type="number" id="bonus" name="bonus">
    </p>
    <p>
      <label for="type">Job Type</label>
      <select id="type" name="type">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Full-Time">Full-Time</option>
        <option value="Part-Time">Part-Time</option>
        <option value="Seasonal">Seasonal</option>
      </select>
    </p>
    <p>
      <label for="frequency">Income Frequency</label>
      <select id="frequency" name="frequency">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Annually">Annually</option>
        <option value="Monthly">Monthly</option>
        <option value="Semi-Monthly">Semi-Monthly</option>
        <option value="Bi-Weekly">Bi-Weekly</option>
        <option value="Weekly">Weekly</option>
      </select>
    </p>
    <input type="hidden" name="borrower_id"
      value="<?= htmlentities($_GET['borrower_id']) ?>">
    <p class="center-p">
      <input class="button" type="submit">
      <a class="button" href="previous_employment.php?borrower_id=<?= htmlentities($_GET['borrower_id']) ?>">Cancel</a>
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
