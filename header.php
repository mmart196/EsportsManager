<?php
/*
MCCodes FREE
header.php Rev 1.1.0c
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



if (strpos($_SERVER['PHP_SELF'], "header.php") !== false)
{
    exit;
}

class headers
{


    function startheaders()
    {
       
         global $ir;
        echo <<<EOF
        
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/game.css" type="text/css" rel="stylesheet" />
<title>Battlerpg</title>
</head>
<body style='background-color: #C3C3C3;'>


EOF;
    }


    function userdata($ir, $fm, $dosessh = 1)
    {
        global $c, $userid;
        $ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
        mysql_query(
                "UPDATE users SET laston=" . time()
                        . ",lastip='$ip' WHERE userid=$userid", $c);
        if (!$ir['email'])
        {
            die(
                    "<body>Your account may be broken. GG.");
        }
        if ($dosessh && isset($_SESSION['attacking']))
        {
            if ($_SESSION['attacking'] > 0)
            {
                print "You lost all your EXP for running from the fight.";
                mysql_query("UPDATE users SET exp=0 WHERE userid=$userid", $c);
                $_SESSION['attacking'] = 0;
            }
        }
        $d = "";
        $u = $ir['username'];
        if ($ir['donatordays'])
        {
         $color=$ir['color'];
            $u = "<font color=$color>{$ir['username']}</font>";
            $d =
                    "<img src='donator.gif' alt='Donator: {$ir['donatordays']} Days Left' title='Donator: {$ir['donatordays']} Days Left' />";
        }
        
        
        
print "<body bgcolor='#C3C3C3'>";

print"<table width=100%><tr><td><img src='logo.png'></td>";
$ra = ranking();

$topt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid AND role=0", $c));
$midt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid AND role=1", $c));
$adct = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid AND role=3", $c));
$supt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid AND role=4", $c));
$jungt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid AND role=2", $c));
$team = round(($topt['elo']+$midt['elo']+$jungt['elo']+$adct['elo']+$supt['elo'])/5);
print"
<td><img src='../anime-chatbox.png'> <b> Name:</b> {$u} {$ir['userid']}<br />
<img src='../anime-list.png'> <b> Team Elo:</b> {$team}<br />
<img src='../anime-green letter s.png'> <b> Money:</b> {$fm}<br />
<img src='../anime-letter S.png'><b> Fame:</b> {$ir['fame']}<br />
[<a href='logout.php'>Emergency Logout</a>]</td><td><br />
</tr></table></div>"; 

        print "<table width=100%><tr><td width=20% valign='top'>
";
        if ($ir['fedjail'])
        {
            $q =
                    mysql_query(
                            "SELECT * FROM fedjail WHERE fed_userid=$userid",
                            $c);
            $r = mysql_fetch_array($q);
            die(
                    "<b><font color=red size=+1>You have been put in the Battlerpg Federal Jail for {$r['fed_days']} day(s).<br />
Reason: {$r['fed_reason']}</font></b></body></html>");
        }
        if (file_exists('ipbans/' . $ip))
        {
            die(
                    "<b><font color=red size=+1>Your IP has been banned, there is no way around this.</font></b></body></html>");
        }
        
  
        
    }

    function menuarea()
    {
        include "mainmenu.php";
        global $ir, $c;
        print "</td><td valign='top'>
";  
    }

    function endpage()
    {
        $year = date('Y');
	         echo <<<EOF
<head>
        </td></tr></table>
        <div style='font-style: italic; text-align: center'>
      		Powered by codes made by Dabomstew & Exelight. Copyright &copy; {$year} Michael Martinez.
      		
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- battlerpg -->
<ins class="adsbygoogle"
     style="display:inline-block;width:320px;height:50px"
     data-ad-client="ca-pub-3447922182622936"
     data-ad-slot="4021943801"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

    	</div>
        </body>
       
		</html>
	
EOF;

    }

}
?>