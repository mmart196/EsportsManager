<?php
/*
MCCodes FREE
criminal.php Rev 1.1.0c
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
$q = mysql_query("SELECT * FROM tournaments ORDER by tourneyid ASC", $c);
$checky = mysql_fetch_array(mysql_query("SELECT * FROM tourneylog WHERE userid=$userid", $c));
if ($checky['userid'] != null)
{
print "You have already done a tournament today.";
return;
}
print
        "<b>Tournament List</b><br />
<table width='75%'><tr><th>Tournament</th><th>Entry Fee</th><th>Do</th></tr>";
while ($r = mysql_fetch_array($q))
{
    print
            "<tr style='background-color:gray'><td colspan='3'>{$r['name']}</td></tr>";
    $q2 =
            mysql_query("SELECT * FROM tournaments WHERE tourneyid={$r['tourneyid']}",
                    $c);
    while ($r2 = mysql_fetch_array($q2))
    {
        print
                "<tr><td>{$r2['type']}</td><td>Entry Fee:{$r2['entry']}  - Average Team Elo:{$r2['alo']} </td><td><a href='docompete.php?c={$r2['tourneyid']}'>Enter</a></td></tr>";
    }
}

print "You can only enter one tournament a day, so choose wisely!";
$h->endpage();