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

  if (isset($_POST['address']) && isset($_POST['occupancy'])
    && isset($_POST['price']) && isset($_POST['taxes'])
    && isset($_POST['condo'])) {
    $bi = (int)$_POST['borrower_id'];
    if ($_POST['address'] == '' || $_POST['occupancy'] == '---SELECT---'
      || $_POST['price'] == '' || $_POST['taxes'] == ''
      || $_POST['condo'] == '---SELECT---') {
      $_SESSION['error'] = "Fill out all the mandatory fields";
      header("Location: addproperty.php?borrower_id=$bi");
      return;
      }
    $sql = "INSERT INTO property (address, occupancy, rental, price, taxes,
      heating, condo, condo_fee, dwelling_type, dwelling_style, garage, age,
      heat, living_space, living_number, lot_size, lot_number, water, sewage,
      borrower_id) VALUES (:address, :occupancy, :rental, :price, :taxes,
      :heating, :condo, :condo_fee, :dwelling_type, :dwelling_style, :garage,
      :age, :heat, :living_space, :living_number, :lot_size, :lot_number,
      :water, :sewage, :borrower_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ":address" => $_POST['address'],
      ":occupancy" => $_POST['occupancy'],
      ":rental" => $_POST['rental'],
      ":price" => $_POST['price'],
      ":taxes" => $_POST['taxes'],
      ":heating" => $_POST['heating'],
      ":condo" => $_POST['condo'],
      ":condo_fee" => $_POST['condo_fee'],
      ":dwelling_type" => $_POST['dwelling_type'],
      ":dwelling_style" => $_POST['dwelling_style'],
      ":garage" => $_POST['garage'],
      ":age" => $_POST['age'],
      ":heat" => $_POST['heat'],
      ":living_space" => $_POST['living_space'],
      ":living_number" => $_POST['living_number'],
      ":lot_size" => $_POST['lot_size'],
      ":lot_number" => $_POST['lot_number'],
      ":water" => $_POST['water'],
      ":sewage" => $_POST['sewage'],
      ":borrower_id" => $bi,
    ));
    $_SESSION['success'] = "The details have been added";
    header("Location: property.php?borrower_id=$bi");
    return;
  }
  ?>

  <?php echo "<h2>Add $first_name's information</h2>"; ?>
  <div class="add">
  <form class="left-form" method="post">
    <p>
      <label for="address">Address</label>
      <textarea name="address" rows="2" cols="40"
        placeholder="Street Number, Street Name&#10;City, Province, Postal Code"></textarea>
    </p>
    <p>
      <label for="occupancy">Occupancy</label>
      <select id="occupancy" name="occupancy">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Owner-Occupied">Owner-Occupied</option>
        <option value="Owner-Occupied & Rental">Owner-Occupied & Rental</option>
        <option value="2nd Home">2nd Home</option>
        <option value="Rental">Rental</option>
      </select>
    </p>
    <p id="rental_income">
    </p>
    <p>
      <label for="price">Current Property Value/Purchase Price</label>
      <input type="number" id="price" name="price">
    </p>
    <p>
      <label for="taxes">Annual Property Taxes</label>
      <input type="number" id="taxes" name="taxes">
    </p>
    <p>
      <label for="heating">Monthly Heating Cost</label>
      <input type="number" id="heating" name="heating">
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
      <label for="dwelling_type">Dwelling Type</label>
      <select id="dwelling_type" name="dwelling_type">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Detached">Detached</option>
        <option value="Semi-Detached">Semi-Detached</option>
        <option value="Duplex - Detached">Duplex - Detached</option>
        <option value="Duplex - Semi-Detached">Duplex - Semi-Detached</option>
        <option value="Row Housing">Row Housing</option>
        <option value="Apartment Low Rise">Apartment Low Rise</option>
        <option value="Apartment High Rise">Apartment High Rise</option>
        <option value="Mobile">Mobile</option>
        <option value="Tri Plex - Detached">Tri Plex - Detached</option>
        <option value="Tri Plex - Semi-Detached">Tri Plex - Semi-Detached</option>
        <option value="Stacked">Stacked</option>
        <option value="Modular Home - Detached">Modular Home - Detached</option>
        <option value="Modular Home - Semi-Detached">Modular Home - Semi-Detached</option>
        <option value="Four Plex - Detached">Four Plex - Detached</option>
        <option value="Four Plex - Semi-Detached">Four Plex - Semi-Detached</option>
      </select>
    </p>
    <p>
      <label for="dwelling_style">Dwelling Style</label>
      <select id="dwelling_style" name="dwelling_style">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="One Storey">One Storey</option>
        <option value="Bi-Level">Bi-Level</option>
        <option value="Two-Storey">Two-Storey</option>
        <option value="Split Level">Split Level</option>
        <option value="Storey and a Half">Storey and a Half</option>
        <option value="Three Storey">Three Storey</option>
        <option value="Other">Other</option>
      </select>
    </p>
    <p>
      <label for="garage">Garage</label>
      <select id="garage" name="garage">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="No Garage">No Garage</option>
        <option value="Attached/Single">Attached/Single</option>
        <option value="Attached/Double">Attached/Double</option>
        <option value="Attached/Triple">Attached/Triple</option>
        <option value="Detached/Single">Detached/Single</option>
        <option value="Detached/Double">Detached/Double</option>
        <option value="Detached/Triple">Detached/Triple</option>
      </select>
    </p>
    <p>
      <label for="age">Age of Property</label>
      <input type="number" id="age" name="age">
    </p>
    <p>
      <label for="heat">Heat</label>
      <select id="heat" name="heat">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Electric Baseboard">Electric Baseboard</option>
        <option value="Forced Air Gas/Oil/Electric">Forced Air Gas/Oil/Electric</option>
        <option value="Hot Water Heating">Hot Water Heating</option>
        <option value="Other">Other</option>
      </select>
    </p>
    <p>
      <label for="living_space">Living Space</label>
      <input type="number" id="living_space" name="living_space">
      <select id="living_number" name="living_number">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Sq Ft">Sq Ft</option>
        <option value="Sq M">Sq M</option>
      </select>
    </p>
    <p>
      <label for="lot_size">Lot Size</label>
      <input type="number" id="lot_size" name="lot_size">
      <select id="lot_number" name="lot_number">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Sq Ft">Sq Ft</option>
        <option value="Sq M">Sq M</option>
        <option value="Acres">Acres</option>
        <option value="Hectares">Hectares</option>
      </select>
    </p>
    <p>
      <label for="water">Water Type</label>
      <select id="water" name="water">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Municipal">Municipal</option>
        <option value="Well">Well</option>
      </select>
    </p>
    <p>
      <label for="sewage">Sewage Type</label>
      <select id="sewage" name="sewage">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="Municipal">Municipal</option>
        <option value="Septic">Septic</option>
        <option value="Holding Tank">Holding Tank</option>
      </select>
    </p>
    <p class="center-p">
      <input type="hidden" name="borrower_id"
        value="<?= htmlentities($_GET['borrower_id']) ?>">
        <input class="button" type="submit">
        <a class="button" href="property.php?borrower_id=<?= htmlentities($_GET['borrower_id']) ?>">Cancel</a>
    </p>
  </form>
</div>

  <script type="text/javascript" src="jquery.min.js">
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#occupancy').change(function() {
        var selected_option = $('#occupancy').val();

        if(selected_option == 'Owner-Occupied & Rental' || selected_option == 'Rental') {
          $('#rental_income').empty();
          $('#rental_income').append(
            '<label for="rental">Rental Income</label> \
              <input type="number" id="rental" name="rental">').show();
          } else {
            $('#rental_income').empty();
          };
        });
      });
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
