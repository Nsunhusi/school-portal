<?php
session_start();
$error = "Something Went Wrong Please Try Again Later";

$ssl = 0;
define('SERVER', "localhost");
define('USER', "root");
define('PASS', "");
define('DB', 'portal');
$my = mysqli_connect(SERVER, USER, PASS, DB);
if ($my) {
    mysqli_set_charset($my, 'utf8');
}
function sqli_pstat($lin/*server*/, $v/*Query*/, $b/*datatype*/, $a/*data*/, $t = false/*query type*/)
{
    $vov = mysqli_stmt_init($lin);
    if (mysqli_stmt_prepare($vov, $v)) {
        switch (strlen($b)) {
            case '1':
                mysqli_stmt_bind_param($vov, $b, $a[0]);
                break;
            case '2':
                mysqli_stmt_bind_param($vov, $b, $a[0], $a[1]);
                break;
            case '3':
                mysqli_stmt_bind_param($vov, $b, $a[0], $a[1], $a[2]);
                break;
            case '4':
                mysqli_stmt_bind_param($vov, $b, $a[0], $a[1], $a[2], $a[3]);
                break;
            case '5':
                mysqli_stmt_bind_param($vov, $b, $a[0], $a[1], $a[2], $a[3], $a[4]);
                break;
            case '6':
                mysqli_stmt_bind_param($vov, $b, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5]);
                break;
            case '7':
                mysqli_stmt_bind_param($vov, $b, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6]);
                break;
            case '8':
                mysqli_stmt_bind_param($vov, $b, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7]);
                break;
        }
        mysqli_stmt_execute($vov);
        if ($t) {
            return mysqli_stmt_get_result($vov);
        } else {
            return true;
        }
    } else {
        return false;
    }
};

function editData($data)
{
    return trim(stripslashes((filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH))));
};
function getI(array $f)
{
    if (count($f)) {
        for ($b = 0; $b < count($f); $b++) {
            if ($f[$b] != "") {
                $vx[] = $f[$b];
            }
        }
        return count($vx);
    } else {
        return 0;
    }
}
function total(array $t)
{
    $f = array_sum($t);

    foreach ($t as $l => $p) {
        if (is_array($p)) {
            $f += array_sum($p);
        }
    }
    if ($f) {
        return $f;
    } else {
        return 0;
    }
}
function pdate(int $stamp)
{
    if ($stamp > (time() - 86400)) {
        return date("g:ia", $stamp);
    } else if (date("Y", $stamp) == date("Y")) {
        return date("F jS", $stamp);
    } else {
        return date("jS F, Y", $stamp);
    }
}
