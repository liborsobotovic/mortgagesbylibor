<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="mortgage" content="A webpage of a mortgage agent.">
  <title>Better Mortgage</title>
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
          }} else {
            echo "<ul class='topnav'>";
            echo "<li><a href='application.php'>Application</a></li>";
            echo "<li><a href='contact.php'>Contact</a></li>";
            echo "<ul class='topnav-right'>";
            echo "<li><a href='logout.php'>Log out</a></li>";
            echo "<li>Hello " . $_SESSION['username'] . "!</li>";
            if (isset($_SESSION['error'])) {
            echo '<li style="color: red">' . $_SESSION['error'] . '</li>';
            unset($_SESSION['error']);
          }}
        ?>
      </ul>
    </nav>
  </header>

  <?php
  require_once "pdo.php";

  $monthly_payment = 0;
  if(isset($_POST['amortization'])) {
    $am = $_POST['amortization'];
  } else {
    $am = "---SELECT--";
  }

  if (isset($_POST['price']) && isset($_POST['down_payment']) && isset($_POST['income'])
    && isset($_POST['property_tax']) && isset($_POST['heat']) && isset($_POST['condo_fees'])
    && isset($_POST['debt']) && isset($_POST['interest'])
    && $_POST['amortization'] != '---SELECT---' ) {
      if ($_POST['price'] == '' || $_POST['down_payment'] == '' || $_POST['income'] == ''
        || $_POST['property_tax'] == '' || $_POST['heat'] == '' || $_POST['condo_fees'] == ''
        || $_POST['debt'] == '' || $_POST['interest'] == ''
        || $_POST['amortization'] == '---SELECT---' ) {
          $_SESSION['error'] = "Please fill out all the mandatory fields";
          header("Location: calculator.php");
          return;
        }
      $mortgage_insurance = 0;
      $down_percentage = $_POST['down_payment'] / $_POST['price'];
      if ($down_percentage >= 0.05 && $down_percentage < 0.1) {
        $mortgage_insurance = 0.04 * ($_POST['price'] - $_POST['down_payment']);
      }
      if ($down_percentage >= 0.1 && $down_percentage < 0.15) {
        $mortgage_insurance = 0.031 * ($_POST['price'] - $_POST['down_payment']);
      }
      if ($down_percentage >= 0.15 && $down_percentage < 0.2) {
        $mortgage_insurance = 0.028 * ($_POST['price'] - $_POST['down_payment']);
      }
      if ($_POST['amortization'] == "30" && $down_percentage < 0.2) {
        $_SESSION['error'] = "Maximum amortization is 25 years if your down payment is lower than 20%";
        header("Location: calculator.php");
        return;
      };
      $min_down = 0.05 * $_POST['price'];
      if ($_POST['price'] > 500000 && $_POST['price'] < 1000000) {
        $min_down = (500000 * 0.05) + (($_POST['price'] - 500000) * 0.1);
      }
      if ($_POST['price'] > 1000000) {
        $min_down = 0.2 * $_POST['price'];
      }
      if ($_POST['down_payment'] < $min_down) {
        $_SESSION['error'] = "Your down payment should be at least the required minimum of $" . number_format($min_down, 2);
        header("Location: calculator.php");
        return;
      }
      $adjusted_price = $_POST['price'] + $mortgage_insurance;
      $periodic_interest = ((1 + (($_POST['interest'] / 100) / 2))**2)**(1/12)-1;
      $effective_rate = (1 + $periodic_interest)**12 - 1;
      $months_amortization = 12 * $_POST['amortization'];
      $principal = $adjusted_price - $_POST['down_payment'];
      $monthly_payment = ($principal * $periodic_interest) / (1 - (1 + $periodic_interest)**(-$months_amortization));
      $gds_max = ($_POST['income'] * 0.39) - $_POST['property_tax'] - (12 * $_POST['heat']) - (($_POST['condo_fees'] * 12) * 0.5);
      $tds_max = ($_POST['income'] * 0.44) - $_POST['property_tax'] - (12 * $_POST['heat']) - (($_POST['condo_fees'] * 12) * 0.5) - ($_POST['debt'] * 12);
      if (($monthly_payment * 12) > $gds_max && ($monthly_payment * 12) > $tds_max) {
        $_SESSION['unaffordable'] = "Your case is specific so contact me and we will see what we can find for you!";
      }
      if (($monthly_payment * 12) <= $gds_max && ($monthly_payment * 12) <= $tds_max) {
        $_SESSION['affordable'] = "You appear to be able to afford this property with monthly payments of $" . number_format($monthly_payment, 2) . " but contact me to confirm and get the best rates!";
      }
    }

  ?>

  <div class="add">
  <form class="left-form" method="post">
    <p>
      <label for="price">Asking Price</label>
      <input type="number" id="price" name="price" value="<?php echo isset($_POST['price']) ? $_POST['price'] : '' ?>">
    </p>
    <p>
      <label for="down_payment">Down Payment</label>
      <input type="number" id="down_payment" name="down_payment" value="<?php echo isset($_POST['down_payment']) ? $_POST['down_payment'] : '' ?>">
    </p>
    <p>
      <label for="income">Gross Annual Income</label>
      <input type="number" id="income" name="income" value="<?php echo isset($_POST['income']) ? $_POST['income'] : '' ?>">
    </p>
    <p>
      <label for="property_tax">Annual Property Tax</label>
      <input type="number" id="property_tax" name="property_tax" value="<?php echo isset($_POST['property_tax']) ? $_POST['property_tax'] : '' ?>">
    </p>
    <p>
      <label for="heat">Monthly Heating Costs</label>
      <input type="number" id="heat" name="heat" placeholder="i.e. $100 if not a condo" value="<?php echo isset($_POST['heat']) ? $_POST['heat'] : '' ?>">
    </p>
    <p>
      <label for="condo_fees">Monthly Condo Fees</label>
      <input type="number" id="condo_fees" name="condo_fees" value="<?php echo isset($_POST['condo_fees']) ? $_POST['condo_fees'] : '' ?>">
    </p>
    <p>
      <label for="debt">Monthly Debt Payments</label>
      <input type="number" id="debt" name="debt" value="<?php echo isset($_POST['debt']) ? $_POST['debt'] : '' ?>">
    </p>
    <p>
      <label for="interest">Interest Rate</label>
      <input type="number" step="0.01" id="interest" name="interest" placeholder="Your actual rate + 2%" value="<?php echo isset($_POST['interest']) ? $_POST['interest'] : '' ?>">
    </p>
    <p>
      <label for="amortization">Amortization</label>
      <select id="amortization" name="amortization">
        <option value="---SELECT---" selected>---SELECT---</option>
        <option value="5">5 Years</option>
        <option value="10">10 Years</option>
        <option value="15">15 Years</option>
        <option value="20">20 Years</option>
        <option value="25">25 Years</option>
        <option value="30">30 Years</option>
      </select>
    </p>
    <p class="center-p">
      <input class="btn btn-primary btn-lg" type="submit" value="Calculate">
    </p>
  </form>
</div>

  <?php

  if (isset($_SESSION['unaffordable'])) {
  echo '<p class="calc-unafford">' . $_SESSION['unaffordable'] . '</p>';
  unset($_SESSION['unaffordable']);
}

  if (isset($_SESSION['affordable'])) {
  echo '<p class="calc-afford">' . $_SESSION['affordable'] . '</p>';
  unset($_SESSION['affordable']);
}

   ?>

   <script type="text/javascript" src="jquery.min.js">
   </script>

   <script type="text/javascript">
     $(document).ready(function() {
       $('#amortization').val('<?php echo $am ?>').change();
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
