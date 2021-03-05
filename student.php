<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="files/css/style.css" />
  <script src="files/js/jquery-3.js"></script>
  <script src="files/js/timer.js"></script>
  <link rel="stylesheet" href="files/css/mprogress.min.css">
  <script src="files/js/mprogress.min.js"></script>
  <title>Student | Portal</title>
</head>

<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <?php
        if (isset($_GET['recovery'])) {
          if ($my) {
            $time = time();
            $io = mysqli_query($my, "SELECT * FROM codes where expiry > '$time' and user = '$_SESSION[suser]' order by expiry desc limit 1");
            if ($io) {
              $ix = mysqli_fetch_assoc($io);
              if (isset($ix)) {
                $m = (int) $ix['expiry'] - time();

                $ct = ($m - ((2 / 3) * $m)) * 1000;
              }
            }
          }

          echo '<form method="POST" action="#" onsubmit="return false" class="mail_form login100-form validate-form" enctype="multipart/form-data">
      <div class="lloader"></div>
    <div class="error_note"></div>
    <h2 class="title">Check Your Mail We Sent A Message</h2>

    <div class="rimg"></div>
    <div class="input-field">
    <i class="fas fa-lock"></i>
    <input class="mainpass input200" type="text" name=\'pass\' placeholder="New Password" />
  </div>
<div class="resend_pin">
    <button disabled="" onclick="return false" class="rmail btn solid">Didnt Recieve A Mail? Resend Here</button>
    
    <div class="clockdiv">
		<div class="hours"></div><span>:</span>
		<div class="minutes"></div><span>:</span>
		<div class="seconds"></div>
	</div>
</div>
<script>
    initializeClock(".rmail",' . (isset($ct) ? $ct : 600000) . ',function(x){
    $(".rmail").click(function(){
        $(this).attr("disabled","");
          conB("do=resend&what=login&mode=recovery&type=Password Recovery",function(x){
       if (x == "done"){
        window.location = "";
  } else {
    errorx(x);
  }
  });
    });
});
    </script>
</form>';
        } else if (isset($_GET['repass'])) {
          echo '<form method="POST" class="sign-in-form recovery-form" enctype="multipart/form-data" action="#">
          <div class="lloader"></div>
          <div class="error_note"></div>
          <h2 class="title">Password Recovery</h2>

          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input class="mainpass input100" type="password" name=\'pass\' placeholder="New Password" />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input class="input100" type="password" name=\'repeat-pass\' placeholder="Retype Password">
          </div>
          <input type="submit" value="Change Password" class="btn solid">
        </form>';
        }else {
         echo ' <form action="#" method=\'POST\' onsubmit="ggo(\'st\');return false" class="validate-form sign-in-form">
          <div class="lloader"></div>
          <div class="error_note"></div>
          <h2 class="title">Student Sign in</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" class=\'input100 inputx\' name=\'email\' placeholder="Email">
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input class="input100" type="password" name=\'pass\' placeholder="Password" />
          </div>
          <input type="submit" value="Login" class="btn solid" />
        </form>
        <a onclick="valid()" class="jik">forgot my password</a>';
        }
        ?>
</div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>Not A Student?</h3>
          <p>If You Are Not A Student Sign In Here</p>
          <button onclick='window.location = this.getAttribute("href")' href='instructor' class="btn transparent" id="sign-in-btn">
            Sign in
          </button>
        </div>
        <img src="img/log.svg" class="image" alt="" />
      </div>
      <div class="panel right-panel">

        <img src="img/register.svg" class="image" alt="" />
      </div>
    </div>
  </div>
  <script src="files/js/app.js"></script>
</body>

</html>