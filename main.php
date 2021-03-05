<?php
require "go.php";
require 'mail.php';

if ($my) {

    $dox = $_POST['do'];
    $expireT = 1800;
    if ($dox == "up") {
        $userf = editData($_POST['user']);
        $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
        $timee = ['joined' => time(), 'suspended' => ''];
        $kin = json_encode($timee);
        $bio = editData($_POST['bio']);
        $eml = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

       if (isset($userf) && isset($pass) && filter_var($eml, FILTER_VALIDATE_EMAIL) && (strlen($bio) >= 150 && strlen($bio) <= 500)) {
            if (count($accepted)) {
                $cates = implode('-', $accepted);
                $ch = sqli_pstat($my, "SELECT * FROM authors where mail = ?", "s", [$eml], true);
                if ($ch) {
                    $hh = mysqli_fetch_assoc($ch);
                    if (isset($hh)) {
  if ($hh['verified'] == "Yes") {
      echo "Seems Like You Have An Account With Us Try Signing In";
  } else {
      $_SESSION['suser'] = $hh['mail'];
      $_SESSION['author'] = $userf;

      $c = hash_hmac('ripemd160', $userf . $bio . $eml, $pass . time());
      $url = "login?verif=$c";
      $code = strtoupper(substr($c, 0, 4) . substr($c, strlen($c) - 3));
      $sCode = password_hash($code, PASSWORD_BCRYPT);
      $sCC = password_hash($c, PASSWORD_BCRYPT);
      $time = time() + $expireT;
      $xx = mysqli_query($my, "INSERT INTO codes(user,code,code2,expiry) values ('$eml','$sCode','$sCC','$time')");

      if ($xx) {
          if (pmail([
              "subject" => "Mail Verification",
              "mess" => "confirm",
              "pin" => $code,
              "url" => $url
          ], [
              "mail" => $eml,
              "name" => $userf
          ])) {
              $mq = sqli_pstat($my, "UPDATE authors set name = ?, biography = ?, mail = ?, pass = ?,date = ?, category = ? where mail = ?", "sssssss", [$userf, $bio, $eml, $pass, $kin, $cates, $eml]);
              if ($mq) {
                  echo "done";
              } else {
                  echo $error;
              }
          } else {
              echo "Please Check Your Email Address Or Internet Access And Try Again";
          };
      } else {
          echo $error;
      }
  }
                    } else {
  $_SESSION['suser'] = $eml;
  $_SESSION['author'] = $userf;
  //move on to email validation
  $c = hash_hmac('ripemd160', $userf . $bio . $eml, $pass . time());
  $url = "login?verif=$c";
  $code = strtoupper(substr($c, 0, 4) . substr($c, strlen($c) - 3));
  $sCode = password_hash($code, PASSWORD_BCRYPT);
  $sCC = password_hash($c, PASSWORD_BCRYPT);
  $time = time() + $expireT;
  $xx = mysqli_query($my, "INSERT INTO codes(user,code,code2,expiry) values ('$eml','$sCode','$sCC','$time')");

  if ($xx) {
      if (pmail([
          "subject" => "Mail Verification",
          "mess" => "confirm",
          "pin" => $code,
          "url" => $url
      ], [
          "mail" => $eml,
          "name" => $userf
      ])) {
          $mq = sqli_pstat($my, "INSERT INTO authors(name, biography, mail, pass, category,date) values (?,?,?,?,?,?)", "ssssss", [$userf, $bio, $eml, $pass, $cates, $kin]);
          if ($mq) {
              echo "done";
          } else {
              echo $error;
          }
      } else {
          echo "Please Check Your Email Address Or Internet Access And Try Again";
      };
  } else {
      echo $error;
  }
                    }
                } else {
                    echo $error;
                }
            } else {
                echo "Something Isn't Right With Your Selected Categories Try Reentering Them";
            }
        } else {
            echo "Something Is Wrong With Your Info Try Reloading The Page";
        }
    } else if ($dox == "ofresh") {
        echo $_SESSION['sont'];
        unset($_SESSION['sont']);
    } else if ($dox == "confirm") {
        $pin = editData($_POST['pin']);
        if (isset($pin)) {
            if (strlen($pin) == 7) {
                $lq = time();
                $lx = mysqli_query($my, "SELECT * FROM codes where expiry > '$lq' order by expiry desc");
                if ($lx) {
                    $mm = mysqli_fetch_all($lx);
                    $tt = false;
                    if (isset($mm[0][0])) {
  for ($i = 0; $i < count($mm); $i++) {
      if (password_verify($pin, $mm[$i][2])) {
          $tt = true;
          $ox = $mm[$i][1];
      }
  }
  if ($tt) {
      $_SESSION['suser'] = $ox;
      $_SESSION['nstage'] = true;
      echo "done";
  } else {
      echo "Your Code Does Not Exist Check The Code And Try Again";
  }
                    } else {
  echo "Your Code Does Not Exist Or Has Expired";
                    }
                } else {
                    echo $error;
                }
            } else {
                echo "Your Pin Is Incorrect";
            }
        } else {
            echo "Your Pin Is Required";
        }
    } else if ($dox == "recover") {
        $user = filter_var($_POST['user'], FILTER_SANITIZE_EMAIL);
        if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            $l = sqli_pstat($my, "SELECT * FROM authors where mail = ? and date -> '$.suspended' = ''", 's', [$user], true);
            if ($l) {
                $ll = mysqli_fetch_assoc($l);
                if (isset($ll['mail'])) {
                    $_SESSION['author'] = $ll['name'];
                    $c = hash_hmac('ripemd160', $ll['mail'], $ll['pass'] . time());

                    $code = strtoupper(substr($c, 0, 4) . substr($c, strlen($c) - 3));
                    $url = "login?con=$c";
                    $sCode = password_hash($code, PASSWORD_BCRYPT);
                    $sCC = password_hash($c, PASSWORD_BCRYPT);
                    $time = time() + $expireT;
                    $xx = mysqli_query($my, "INSERT INTO codes(user,code,code2,expiry) values ('$user','$sCode','$sCC','$time')");
                    if ($xx) {
  if (pmail([
      "subject" => "Password Recovery",
      "mess" => "recovery",
      "pin" => $code,
      "url" => $url
  ], [
      "mail" => $user,
      "name" => $ll['name']
  ])) {
      $_SESSION['suser'] = $user;
      echo "done";
  } else {
      echo "Please Go Back And Check Your Email Address And Try Again  Or Check Your Internet Access";
  };
                    } else {
  echo $error;
                    }
                } else {
                    echo "Your Email Does Not Exist";
                }
            } else {
                echo $error;
            }
        } else {
            echo "Your Email Is Incorrect";
        }
    } else if ($dox == "in") {
        $user = filter_var($_POST['user'], FILTER_SANITIZE_EMAIL);
        $t = editData($_POST['typ']);
        $pass = $_POST['pass'];
        if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            $ql = sqli_pstat($my, "SELECT * FROM authors where mail = ? and verified = 'Yes' and type = '$t' limit 1", "s", [$user], true);
            if ($ql) {
                $fg = mysqli_fetch_assoc($ql);

                if (isset($fg)) {
                    if (password_verify($pass, $fg['pass'])) {
  $rx = json_decode($fg['date'], true)['suspended'];
  if (empty($rx)) {
      $_SESSION['username'] = $user;
      $_SESSION['author'] = $fg['name'];
      echo "true";
      //continue from here
  } else {
      if ($rx == "Yes") {
          echo "This Account Has Been Suspended Till Further Notice";
      } else {
          echo "This Account Has Been Suspended Till On " . $rx;
      }
  }
                    } else {
  echo "Email Does Not Match Password";
                    }
                } else {
                    echo "Your Account Does Not Exist Or Has Not Been Verified Try Signing Up";
                }
            } else {
                echo $error;
            }
        } else {
            echo "Your Email Address Is Wrong";
        }
    } else if ($dox == "verif") {
        $code = editData($_POST['code']);
        $t = $_POST['t'];
        if ($t == 'sub') {
            $ss = $_SESSION['subname']['mail'];
        } else {
            $ss = $_SESSION['suser'];
        }
        if (isset($code)) {
            if (strlen($code) == 7) {
                $f = sqli_pstat($my, "SELECT * From codes where user = ? order by expiry desc limit 1", 's', [$ss], true);
                if ($f) {
                    $op = mysqli_fetch_assoc($f);
                    if (isset($op)) {
  if (password_verify($code, $op['code'])) {
      if ($t == 'sub') {
          $go = mysqli_query($my, "UPDATE subscribers set verified = 'Yes' Where mail = '$op[user]'");
      } else if ($t == 'main') {
          $go = mysqli_query($my, "UPDATE authors set verified = 'Yes' Where mail = '$op[user]'");
      }
      if ($go) {
          switch ($t) {
              case "sub":
                  $_SESSION['subname'] = ["code" => md5($op['user']), "mail" => $op['user']];
                  $_SESSION['nsstage'] = true;
                  break;
              case "main":
                  $_SESSION['username'] = $_SESSION['suser'] = $op['user'];
                  break;
          }
          echo "done";
      } else {
          echo $error;
      }
  } else {
      echo "The Code You Entered Doesnt Exist.";
  }
                    } else {
  echo "The Code You Entered Doesnt Exist Or Has Expired Try Resending.";
                    }
                } else {
                    echo $error;
                }
            } else {
                echo "Your Code Is Incorrect";
            }
        } else {
            echo "No Code Was Found";
        }
    } else if ($dox == "resend") {
        $x = editData($_POST['type']);
        $xz = editData($_POST['mode']);
        $what = $_POST['what'];
        if (isset($_SESSION['suser'])) {
            $cc = $_SESSION['suser'];
            $f = mysqli_query($my, "SELECT * FROM codes where user = '$cc' order by expiry desc limit 1");

            if ($f) {
                $bb = mysqli_fetch_assoc($f);
                if (isset($bb)) {
                    $cur = time();
                    $lq = $bb['code'];
                    if (mysqli_query($my, "DELETE FROM codes where expiry <= $cur || user = '$cc'")) {

  $ccc = hash_hmac('ripemd160', $cc, $lq . $cur);
  $code = strtoupper(substr($ccc, 0, 3) . '-' . substr($ccc, strlen($ccc) - 3));
  $exp = time() + $expireT;
  $sCode = password_hash($code, PASSWORD_BCRYPT);
  $sCC = password_hash($ccc, PASSWORD_BCRYPT);
  $lox = mysqli_query($my, "INSERT INTO codes(user,code,code2,expiry) values ('$cc','$sCode','$sCC','$exp')");
  if ($lox) {
      //send mail 
      if ($what == "login") {
          $url = "login?con=$ccc";
      } else if ($what == "verif") {
          $url = "login?verif=$ccc";
      }

      if (pmail([
          "subject" => $x,
          "mess" => $xz,
          "pin" => $code,
          "url" => $url
      ], [
          "mail" => $_SESSION['suser'],
          "name" => $_SESSION['author']
      ])) {
          echo "done";
      }
  } else {
      echo $error;
  }
                    } else {
  echo $error;
                    };
                } else {
                    echo "unauth";
                }
            } else {
                echo $error;
            }
        } else {
            echo "unauth";
        }
    } else if ($dox == "npass") {
        $new = $_POST['new'];
        if (isset($_SESSION['nstage'])) {
            if (isset($new) && empty($new) != true) {
                $new = password_hash($new, PASSWORD_BCRYPT);
                $xc = sqli_pstat($my, "SELECT name, pass From authors where mail = ?", 's', [$_SESSION['suser']], true);
                if ($xc) {
                    $d = mysqli_fetch_assoc($xc);
                    if (isset($d)) {
  if (sqli_pstat($my, "UPDATE authors set pass = ? where mail = ?", 'ss', [$new, $_SESSION['suser']])) {
      echo "done";
      unset($_SESSION['nstage']);
      $_SESSION['username'] = $_SESSION['suser'];
      $_SESSION['author'] = $d['name'];
  } else {
      echo $error;
  }
                    } else {
  echo "Your Dont Have An Account With Us Pls Try Signing Up";
                    }
                } else {
                    echo $error;
                }
            } else {
                echo "Your New Password Is Required";
            }
        }
    }
} else {
    echo $error;
}
