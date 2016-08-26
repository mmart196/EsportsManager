<?php
/*
MCCodes FREE
userlist.php Rev 1.1.0c
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
$h->userdata($ir,$fm);
$h->menuarea();
$_GET['st'] = abs((int) $_GET['st']);
$st = ($_GET['st']) ? $_GET['st'] : 0;
$allowed_by = array('userid', 'username', 'money');
$by = (in_array($_GET['by'], $allowed_by)) ? $_GET['by'] : 'userid';
$allowed_ord = array('asc', 'desc', 'ASC', 'DESC');
$ord = (in_array($_GET['ord'], $allowed_ord)) ? $_GET['ord'] : 'ASC';
print "<h3>Userlist</h3>";
$cnt = mysql_query("SELECT userid FROM users", $c);
$membs = mysql_num_rows($cnt);
$pages = (int) ($membs / 100) + 1;
if ($membs % 100 == 0)
{
    $pages--;
}
print "Pages: ";
for ($i = 1; $i <= $pages; $i++)
{
    $stl = ($i - 1) * 100;
    print "<a href='userlist.php?st=$stl&by=$by&ord=$ord'>$i</a>&nbsp;";
}
print
        "<br />
Order By: <a href='userlist.php?st=$st&by=userid&ord=$ord'>User ID</a>&nbsp;| <a href='userlist.php?st=$st&by=username&ord=$ord'>Username</a>&nbsp;| <a href='userlist.php?st=$st&by=money&ord=$ord'>Money</a><br />
<a href='userlist.php?st=$st&by=$by&ord=asc'>Ascending</a>&nbsp;| <a href='userlist.php?st=$st&by=$by&ord=desc'>Descending</a><br /><br />";
$q =
        mysql_query(
                "SELECT u.* FROM users u ORDER BY $by $ord LIMIT $st,100",
                $c);
$no1 = $st + 1;
$no2 = $st + 100;
print
        "Showing users $no1 to $no2 by order of $by $ord.
<table width=75% border=2><tr style='background:gray'><th>ID</th><th>Name</th><th>Money</th><th>Fame</th><th>Team Elo</th><th>Online</th></tr>";
while ($r = mysql_fetch_array($q))
{
$topt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND role=0", $c));
$midt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND role=1", $c));
$adct = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND role=3", $c));
$supt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND role=4", $c));
$jungt = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND role=2", $c));
$team = round(($topt['elo']+$midt['elo']+$jungt['elo']+$adct['elo']+$supt['elo'])/5);


    $d = "";
    if ($r['donatordays'])
    {
    $color = $r['color'];
        $r['username'] = "<font color=$color>{$r['username']}</font>";
        $d =
                "<img src='donator.gif' alt='Donator: {$r['donatordays']} Days Left' title='Donator: {$r['donatordays']} Days Left' />";
    }
    print
            "<tr><td><center>{$r['userid']}</td><td><center><a href='viewuser.php?u={$r['userid']}'>{$r['username']} $d</a></td><td><center>\${$r['money']}</td><td><center>{$r['fame']}</td><td><center>{$team}</td><td>";
            
             if ($r['laston'] >= time() - 15 * 60)
    {
        print "<center><font color=green><b>Online</b></font>";
    }
    else
    {
        print "<center><font color=red><b>Offline</b></font>";
    }
    print "</td></tr>";
}
print "</table>";

$h->endpage();