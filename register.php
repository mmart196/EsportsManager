<?php
/*
MCCodes FREE
register.php Rev 1.1.0c
Copyright (C) 2005-2012 Dabomstew

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

session_start();
require "mysql.php";
require "global_func.php";
print 
        <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/game.css" type="text/css" rel="stylesheet" />
<title>Battlerpg</title>
</head>
<body onload="getme();" bgcolor="#C3C3C3">
<img src="../logo.png" alt="Your Game Logo" />
<br />
EOF;
$ip = ($_SERVER['REMOTE_ADDR']);
if (file_exists('ipbans/' . $ip))
{
    die(
            "<b><span style='color: red; font-size: 120%'>
            Your IP has been banned, there is no way around this. GET REKT!
            </span></b>
            </body></html>");
}
if ($_POST['username'])
{
    $sm = 500;
    if ($_POST['promo'] == "gg")
    {
        $sm += 150;
    }
    $username = $_POST['username'];
    $username =
            mysql_real_escape_string(
                    htmlentities(stripslashes($username), ENT_QUOTES,
                            'ISO-8859-1'), $c);
    $q = mysql_query("SELECT * FROM users WHERE username='{$username}'", $c);
    if (mysql_num_rows($q))
    {
        print "Username already in use. Choose another.";
    }
    else if ($_POST['password'] != $_POST['cpassword'])
    {
        print "The passwords did not match, go back and try again.";
    }
    else
    {
        $_POST['ref'] = abs((int) $_POST['ref']);
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($_POST['ref'])
        {
            $q =
                    mysql_query(
                            "SELECT `lastip`
                             FROM `users`
                             WHERE `userid` = {$_POST['ref']}", $c);
            if (mysql_num_rows($q) == 0)
            {
                mysql_free_result($q);
                echo "Referrer does not exist.<br />
				&gt; <a href='register.php'>Back</a>";
                die('</body></html>');
            }
            $rem_IP = mysql_result($q, 0, 0);
            mysql_free_result($q);
            if ($rem_IP == $ip)
            {
                echo "No creating referral multies.<br />
				&gt; <a href='register.php'>Back</a>";
                die('</body></html>');
            }
        }
        
        $gender = $_POST['sex'];
        
        
        mysql_query(
                "INSERT INTO users (username, login_name, userpass, money, gender, email, lastip) VALUES( '{$username}', '{$username}', md5('{$_POST['password']}'), $sm, '{$gender}', '{$_POST['email']}', '$ip')", $c);            
                        
        $i = mysql_insert_id($c);
        mysql_query("INSERT INTO teams VALUES($i, 0, 0, 0, 0, 0, 0, 0)", $c);
        if ($_POST['ref'])
        { 
            $e_rip = mysql_real_escape_string($rem_IP, $c);
            $e_oip = mysql_real_escape_string($ip, $c);
        }
        print 
                "You have signed up, enjoy the game.<br />
&gt; <a href='login.php'>Login</a>";
    }
}
else
{
    $gref = abs((int) $_GET['REF']);
    $fref = $gref ? $gref : '';
    echo <<<EOF
    <h3>
      Battlerpg Registration
    </h3>
    <form action="register.php" method="post">
      Username: <input type="text" name="username" /><br />
      Password: <input type="password" name="password" /><br />
      Confirm Password: <input type="password" name="cpassword" /><br />
      Gender: 
      <input type="radio" name="sex" value="Male">Male
      <input type="radio" name="sex" value="Female">Female<br>
      Email: <input type="text" name="email" /><br />
      Promo Code: <input type="text" name="promo" /><br />
      <input type="hidden" name="ref" value='{$fref}' />
      <input type="submit" value="Submit" />
    </form><br />
    &gt; <a href='login.php'>Go Back</a>
EOF;
}
print "</body></html>";