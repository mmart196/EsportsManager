<?php
/*
MCCodes FREE
docrime.php Rev 1.1.0c
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
require "global_func.php";
if ($_SESSION['loggedin'] == 0)
{
    header("Location: login.php");
    exit;
}
$userid = $_SESSION['userid'];
require "header.php";
$h = new headers;
$h->startheaders();
include "mysql.php";
global $c;
$is =
        mysql_query(
                "SELECT u.*,us.* FROM users u LEFT JOIN teams us ON u.userid=us.userid WHERE u.userid=$userid",
                $c) or die(mysql_error());
$ir = mysql_fetch_array($is);
$fm = money_formatter($ir['money']);
$cm = money_formatter($ir['crystals'], '');
$lv = date('F j, Y, g:i a', $ir['laston']);
$h->userdata($ir,  $fm);
$h->menuarea();
$_GET['c'] = abs((int) $_GET['c']);
if (!$_GET['c'])
{
    print "Invalid Tournament";
    return;
}
$agecheckk = mysql_query("SELECT * FROM players WHERE userid=$userid", $c);
while($agecheck=mysql_fetch_array($agecheckk))
{
if ($agecheck['age'] > 25 && $agecheck['role'] < 5)
{
print "One of your teammates is too old";
return;
break;
}
}
$checky = mysql_fetch_array(mysql_query("SELECT * FROM tourneylog WHERE userid=$userid", $c));
if ($checky['userid'] == $userid)
{
print "You have already done a tournament today";
return;
}
else
{
    $q = mysql_query("SELECT * FROM tournaments WHERE tourneyid={$_GET['c']}", $c);
    if (mysql_num_rows($q) == 0)
    {
        echo 'Invalid Tournament.';
        $h->endpage();
        exit;
    }
    $r = mysql_fetch_array($q);
    if ($ir['money'] < $r['entry'])
    {
        print "You do not have enough money for the entry fee.";
    }
    else
    {
     $topt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid AND role=0", $c));
$midt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid AND role=1", $c));
$adct = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid AND role=3", $c));
$supt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid AND role=4", $c));
$jungt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid AND role=2", $c));
$teamelo = round(($topt['realelo']+$midt['realelo']+$jungt['realelo']+$adct['realelo']+$supt['realelo'])/5);   
$mood = ($topt['mood']+$midt['mood']+$jungt['mood']+$adct['mood']+$supt['mood'])/150;
$moodx = 1.1-$mood;
$luck = rand(0,10);
$luck = 1.05-($luck/100);
$teamelo = $teamelo*$moodx*$luck;
//print "TESTING: {$teamelo} <br>";
if ($teamelo > $r['alo'])
{
$modo = rand(2, 3);
$entry = $r['entry'];
$gain = $r['entry']*$modo;
$fain = $r['tourneyid'];
mysql_query("INSERT INTO tourneylog VALUES ($userid, 1, $fain)", $c);
mysql_query("UPDATE users SET money=money+$gain, fame=fame+$fain WHERE userid=$userid", $c);
$gain = money_formatter($gain);
print"Your team won 1st place! Here is your cash prize: {$gain}!";
}
else
{
mysql_query("UPDATE users SET money=money-$entry WHERE userid=$userid", $c);
mysql_query("INSERT INTO tourneylogs (userid, type, tourneyid) VALUES ($userid, 1, $fain)", $c);
print"Your team lost the tournament. Sometimes teammates aren't performing well or luck can be a factor too. Better luck next time!";
}

    }
}

$h->endpage();