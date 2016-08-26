<?php
/*
MCCodes FREE
viewuser.php Rev 1.1.0c
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
$lv = date('F j, Y, g:i a', $ir['laston']);
$h->userdata($ir, $fm);
$h->menuarea();

$_GET['u'] = abs((int) $_GET['u']);
if (!$_GET['u'])
{
    print "Invalid use of file";
}
else
{
    $q =
            mysql_query(
                    "SELECT u.*,us.*,h.* FROM users u LEFT JOIN teams us ON u.userid=us.userid  LEFT JOIN housing h ON u.houseid=h.houseid WHERE u.userid={$_GET['u']}", $c);
    if (mysql_num_rows($q) == 0)
    {
        print 
                "Sorry, we could not find a user with that ID, check your source.";
    }
    else
    {
    $r = mysql_fetch_array($q);
$topt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND role=0", $c));
$midt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND role=1", $c));
$adct = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND role=3", $c));
$supt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND role=4", $c));
$jungt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND role=2", $c));
$team = round(($topt['elo']+$midt['elo']+$jungt['elo']+$adct['elo']+$supt['elo'])/5);
    
    
    
    
        
        if ($r['user_level'] == 1)
        {
            $userl = "Member";
        }
        else if ($r['user_level'] == 2)
        {
            $userl = "Admin";
        }
        else if ($r['user_level'] == 3)
        {
            $userl = "Secretary";
        }
        else if ($r['user_level'] == 0)
        {
            $userl = "NPC";
        }
        else if ($r['user_level'] == 4)
        {
            $userl = "IRC Mod";
        }
        else
        {
            $userl = "Assistant";
        }
     
        $d = "";
        $la = time() - $r['laston'];
        $unit = "seconds";
        if ($la >= 60)
        {
            $la = (int) ($la / 60);
            $unit = "minutes";
        }
        if ($la >= 60)
        {
            $la = (int) ($la / 60);
            $unit = "hours";
            if ($la >= 24)
            {
                $la = (int) ($la / 24);
                $unit = "days";
            }
        }
        if ($r['donatordays'])
        {
         $color=$r['color'];
            $r['username'] = "<font color=$color>{$r['username']}</font>";
            $d =
                    "<img src='donator.gif' alt='Donator: {$r['donatordays']} Days Left' title='Donator: {$r['donatordays']} Days Left' />";
                   
        }
        if ($r['laston'] >= time() - 15 * 60)
        {
            $on = "<font color=green><b>Online</b></font>";
        }
        else
        {
            $on = "<font color=red><b>Offline</b></font>";
        }
        $propertyname = house($r['houseid']);
        
        print 
                "<h3>Profile for {$r['username']}</h3>";
                
if ($r['donatordays'] && $color!=NULL)
{
print"
<table width=75%><tr style='background:$color'>";
}
else
 {
 print"
<table width=75%><tr style='background:grey'>";
 }
print"
<th>General Info</th><th>Financial Info</th> <th>Display Pic</th></tr>
<tr><td>Name: {$r['username']} [{$r['userid']}] $d<br />
Gender: {$r['gender']}<br />
Online: $on<br />
Last Action: $la $unit ago<br />
<img src='../anime-green letter s.png'>Money: \${$r['money']}<br />
Property: {$propertyname}<br />";
       
        if ($r['display_pic'])
        {
            print 
                    "<img src='{$r['display_pic']}' width='150' height='150' alt='User Display Pic' title='User Display Pic' />";
        }
        else
        {
            print "This user has no display pic!";
        }
        print 
                "</td></tr>";
if ($r['donatordays'] && $color!=NULL)
{
print"
<tr style='background:$color'>";
}
else
 {
 print"
<tr style='background:grey'>";
 }
$top = player('Top');
$mid = player('Mid');
$jungle = player('Jungle');
$adc = player('ADC');
$support = player('Support');
$coach = player('Coach');
$analyst = player('Analyst');
print"
<th>Team Info</th><th>Links</th>{$sh}</tr>";
print"<tr><td>
Team Elo: {$team} <br />
Top: {$top} <br />
Mid: {$mid} <br />
Jungle: {$jungle}<br />
ADC: {$adc}<br />
Support: {$support}<br />
Coach: {$coach}<br />
Analyst: {$analyst}<br />";
        print "</td></tr></table>";
    }
}
$h->endpage();