<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Instantiation and passing `true` enables exceptions
function pmail($m, $r)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = false;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = true;
        $mail->Username = 'justtestingmag69@gmail.com';
        $mail->Password = 'peerlessmag33'; // SMTP password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;


        //Recipients 
            $mail->setFrom('example@example.com', 'Example');
        
     
            $mail->addReplyTo('example@example.com');
        
        $mail->addAddress($r['mail'], $r['name']); // Add a recipient


        // Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name



        // $mail->AddEmbeddedImage('files/source/main.png', 'logo','main');

        $mail->isHTML(true);

        if ($m['mess'] == "issue1") {
            $message = $m['content'];
        } else if ($m['mess'] == "confirm" || $m['mess'] == "recovery") {
            // Content
            $message = '<style type="text/css">
            title{text-align:center;}
            </style>
    <table width="100%" style="text-align:center;" cellspacing="0" cellpadding="0">
  <tr>
      <td>
          <table cellspacing="0" cellpadding="0" style="margin:auto;">
              <tr>
                  <td width="100%" align="center" style="border-radius: 2px;">
                      <a href="" target="_blank" style=" border-radius: 2px;display:block;margin:auto;text-align:center;color:#fff;">
                          <img alt="portal" src="" 
                          width="200px" style="display: block;margin:auto;"> 
                      </a>
                  </td>
              </tr>
          </table>
      </td>
  </tr>
</table>
<table style="width:100%;text-align:center;">
            <tr>
            <td>
    <h1 style="padding:4px;border-radius: 2px;font-family: Georgia;color: #000000;text-decoration: none;"><style>table{width:100%;text-align:center;}</style>' . (($m['mess'] == "confirm") ? "Confirm Your Email Address" : "Recover Your Password") . '</h1>
    <td></tr>
    </td>
    </table>
</tr>
    </table>
    
    
    <table width="100%" style="text-align:center;" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <table style="margin:auto;">
            <tr>
            <td>
    <span style="padding:4px;border-radius: 2px;font-family: Georgia;font-size: 18px; color: #000000;text-decoration: none;">Your ' . (($m['mess'] == "confirm") ? "confirmation" : "Recovery") . ' code is below - enter it into your open browser or click on the link</span>
    <br></br>
    <span style="padding:4px;border-radius: 2px;font-family: Georgia;font-size: 18px; color: #000000;text-decoration: none;">Please enter the code exactly how it is here into your browser</span>
    <td></tr>
    </table>
    </td>
</tr>
    </table>
<br></br>
<br></br>
<br></br>    
<table width="100%" style="text-align:center;" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <table style="text-align:center;" width="100%">
<tr>
    <td align="center" valign="middle" style="">
    <a target="_blank" href=' . $m['url'] . ' style="background-color:#ffa500; display: block; padding: 12px;max-width:700px; text-transform: capitalize;text-align:center;border-radius: 2px;font-family: Georgia;font-weight: 500; font-size: 14px;color:#ffffff;text-decoration: none;">' . (($m['mess'] == "confirm") ? "confirmation" : "Recovery") . '</a>
    </td>
</tr>
    </td>
    </table>
</tr>
    </table>

<br></br>

<table width="100%" style="text-align:center;" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <table style="margin:auto;">
            <tr>
            <td>
    <span style="padding:4px;border-radius: 2px;font-family: Georgia;font-size: 18px; color: #000000;text-decoration: none;">If you didn\'t request this email, there\'s nothing to worry about - you can safely ignore it.</span>
    <td></tr>
    </td>
    </table>
</tr>
    </table>'; //end herrre
        } else if ($m['mess'] == "save") {
            $message = '
    <table width="100%" cellspacing="0" cellpadding="0">
  <tr>
      <td>
          <table cellspacing="0" cellpadding="0" style="margin:auto;">
              <tr>
                  <td align="center" style="border-radius: 2px;">
                      <a href="/link/" target="_blank" style="padding: 8px 12px; border-radius: 2px;font-family: Helvetica, Arial, sans-serif;margin:auto;color: #ffffff;text-align:center;text-decoration: none;">
                          <img src="/link/" width="200px" style="display: block;
                          margin:auto;"> 
                      </a>
                  </td>
              </tr>
          </table>
      </td>
  </tr>
</table>
<table style="width:100%;text-align:center;">
            <tr>
            <td>
            <style>table{width:100%;text-align:center;}</style>
    <h1 style="padding:4px;border-radius: 2px;font-family: Georgia;color: #000000;text-decoration: none;">Profile Changes</h1>
    <td></tr>
    </td>
    </table>
</tr>
    </table>
    
    
    <table width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <table style="margin:auto;">
            <tr>
            <td>
    <span style="padding:4px;border-radius: 2px;font-family: Georgia;font-size: 18px; color: #000000;text-decoration: none;">Hello You Made Changes To Your Profile Earlier...Click The Link Below To Save Your Settings</span>
    <td></tr>
    </td>
    </table>
</tr>
    </table>
<br></br>
<br></br>
<br></br>   
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <table width="100%">
<tr>
    <td align="center" valign="middle" style="">
    <a target="_blank" href=' . $m['url'] . ' style="background-color:#ffa500; display: block; padding: 12px;max-width:700px; text-transform: capitalize;text-align:center;border-radius: 3px;font-family: Georgia;font-weight: 500; font-size: 14px;color:#ffffff;text-decoration: none; font-style: normal; ">Save Settings</a>
    </td>
</tr>
    </td>
    </table>
</tr>
    </table>

<br></br>
<br></br>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <table style="margin:auto;">
            <tr>
            <td>
    <span style="padding:4px;border-radius: 2px;font-family: Georgia;font-size: 18px; color: #000000;text-decoration: none;">If you didn\'t request this email, there\'s nothing to worry about - you can safely ignore it.</span>
    <td></tr>
    </td>
    </table>
</tr>
    </table>'; //end herrre

        } else if ($m['mess'] == "notify") {

            $message = '
    <table width="100%" cellspacing="0" cellpadding="0">
  <tr>
      <td>
          <table cellspacing="0" cellpadding="0" style="margin:auto;">
              <tr>
                  <td width="100%" align="center" style="border-radius: 2px;">
                      <a href="/link/" target="_blank" style=" border-radius: 2px;display:block;margin:auto;text-align:center;color:#fff;">
                          <img alt="/link/" src="/link/" width="200px" style="display: block;margin:auto;"> 
                      </a>
                  </td>
              </tr>
          </table>
      </td>
  </tr>
</table>
<table style="width:100%;text-align:center;">
            <tr>
            <td>
            
    <h1 style="padding:4px;border-radius: 2px;font-family: Georgia;color: #000000;text-decoration: none;">New Articles Ready For Review<style>table{width:100%;text-align:center;}</style></h1>
    <td></tr>
    </td>
    </table>
</tr>
    </table>

    <br></br>
    <table width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <table style="margin:auto;">
            <tr>
            <td>
    <span style="padding:4px;border-radius: 2px;font-family: Georgia;font-size: 18px; color: #000000;text-decoration: none;">Hello ' . $r['name'] . ' You Have Up To 5 or More Waiting For Your Approval Click On The Button Below To Login.</span>

<br></br>
<br></br>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <table width="100%">
<tr>
    <td align="center" valign="middle" style="">
    <a target="_blank" href=' . $m['url'] . ' style="background-color:#ffa500; display: block; padding: 12px;max-width:700px; text-transform: capitalize;text-align:center;border-radius: 2px;font-family: Georgia;font-weight: 500; font-size: 14px;color:#ffffff;text-decoration: none; font-style: normal;">Login</a>
    </td>
</tr>
    </td>
    </table>
</tr>
    </table>

<br></br>
<br></br>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <table style="margin:auto;">
            <tr>
            <td>
    <span style="padding:4px;border-radius: 2px;font-family: Georgia;font-size: 18px; color: #000000;text-decoration: none;">If this email doesn\'t seem familiar to you, there\'s nothing to worry about - you can safely ignore it.</span>
    <td></tr>
    </td>
    </table>
</tr>
    </table>';

        } else if ($m['mess'] == "ninfo") {
            $message = $m['info'];
        }
        $mail->Subject = $m['subject'];
        $mail->Body = $message;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
